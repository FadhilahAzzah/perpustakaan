/**
 * CRUD Buku menggunakan fetch() tanpa reload halaman.
 * Alur: aksi pengguna -> fetch ke Laravel (JSON) -> tampilkan pesan -> loadBuku() memperbarui tabel.
 */
(function () {
    'use strict';

    const cfg = window.PERPUS;
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const $ = (id) => document.getElementById(id);

    const tbody = $('buku-tbody');
    const pagination = $('pagination');
    const info = $('table-info');
    const searchInput = $('search');
    const form = $('form-buku');
    const alertBox = $('alert-box');
    const formModal = new bootstrap.Modal($('modal-buku'));
    const deleteModal = new bootstrap.Modal($('modal-hapus'));
    const fields = ['kode_buku', 'judul', 'penulis', 'kategori', 'stok'];

    const state = { page: 1, search: '', rows: {}, deleteId: null };

    /* ---------- Helper ---------- */
    function esc(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    async function request(url, method = 'GET', body = null) {
        const res = await fetch(url, {
            method,
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: body ? JSON.stringify(body) : null,
        });

        const data = await res.json().catch(() => ({}));

        if (res.status === 401 || res.status === 419) { // sesi habis
            window.location.href = cfg.loginUrl;
            return new Promise(() => {});
        }
        if (!res.ok) {
            const err = new Error(data.message || 'Terjadi kesalahan pada server.');
            err.status = res.status;
            err.errors = data.errors || {};
            throw err;
        }
        return data;
    }

    function showAlert(type, message) {
        const el = document.createElement('div');
        el.className = `alert alert-${type} d-flex justify-content-between align-items-start`;
        el.setAttribute('role', 'alert');
        el.innerHTML = `<span>${esc(message)}</span><button type="button" class="btn-close ms-2" aria-label="Tutup"></button>`;
        el.querySelector('button').addEventListener('click', () => el.remove());
        alertBox.appendChild(el);
        setTimeout(() => el.remove(), 5000);
    }

    function clearErrors() {
        fields.forEach((f) => form.elements[f].classList.remove('is-invalid'));
        form.querySelectorAll('[data-error-for]').forEach((el) => (el.textContent = ''));
    }

    function showFieldErrors(errors) {
        Object.entries(errors).forEach(([name, messages]) => {
            const input = form.elements[name];
            const box = form.querySelector(`[data-error-for="${name}"]`);
            if (input) input.classList.add('is-invalid');
            if (box) box.textContent = messages[0];
        });
    }

    /* ---------- READ ---------- */
    async function loadBuku(page = state.page) {
        state.page = page;
        const qs = new URLSearchParams({ page, search: state.search });

        try {
            const res = await request(`${cfg.dataUrl}?${qs}`);

            // Jika halaman terakhir menjadi kosong (setelah hapus), mundur satu halaman.
            if (res.data.length === 0 && res.current_page > 1) {
                return loadBuku(res.current_page - 1);
            }
            renderRows(res);
            renderPagination(res);
        } catch (err) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-danger">Data buku gagal dimuat. Muat ulang halaman.</td></tr>';
            showAlert('danger', err.message);
        }
    }

    function renderRows(res) {
        state.rows = {};

        if (res.data.length === 0) {
            const msg = state.search ? 'Tidak ada buku yang cocok dengan pencarian.' : 'Belum ada buku. Klik "Tambah buku" untuk memulai.';
            tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted">${msg}</td></tr>`;
            info.textContent = '';
            return;
        }

        tbody.innerHTML = res.data.map((b, i) => {
            state.rows[b.id] = b;
            const aksi = cfg.isAdmin
                ? `<button class="btn btn-sm btn-perpus-warning me-1" data-action="edit" data-id="${b.id}"><i class="bi bi-pencil-square"></i> Ubah</button>
                   <button class="btn btn-sm btn-perpus-danger" data-action="delete" data-id="${b.id}"><i class="bi bi-trash3"></i> Hapus</button>`
                : '<span class="text-muted small">Hanya admin</span>';
            return `<tr>
                <td>${res.from + i}</td>
                <td><code>${esc(b.kode_buku)}</code></td>
                <td class="fw-medium">${esc(b.judul)}</td>
                <td>${esc(b.penulis)}</td>
                <td><span class="badge badge-kategori">${esc(b.kategori)}</span></td>
                <td class="text-center"><span class="badge ${b.stok <= 5 ? 'badge-stok-rendah' : 'badge-stok'}">${b.stok}</span></td>
                <td class="text-end text-nowrap">${aksi}</td>
            </tr>`;
        }).join('');

        info.textContent = `Menampilkan ${res.from}-${res.to} dari ${res.total} buku`;
    }

    function renderPagination(res) {
        if (res.last_page <= 1) { pagination.innerHTML = ''; return; }

        const item = (label, page, disabled = false, active = false) =>
            `<li class="page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${page}">${label}</a></li>`;

        let html = item('&laquo;', res.current_page - 1, res.current_page === 1);
        const start = Math.max(1, res.current_page - 2);
        const end = Math.min(res.last_page, res.current_page + 2);
        for (let p = start; p <= end; p++) html += item(p, p, false, p === res.current_page);
        html += item('&raquo;', res.current_page + 1, res.current_page === res.last_page);
        pagination.innerHTML = html;
    }

    /* ---------- CREATE & UPDATE ---------- */
    function openForm(buku = null) {
        clearErrors();
        form.reset();
        $('buku-id').value = buku ? buku.id : '';
        $('modal-buku-title').textContent = buku ? 'Ubah buku' : 'Tambah buku';
        $('btn-simpan').textContent = buku ? 'Simpan perubahan' : 'Simpan buku';
        if (buku) fields.forEach((f) => (form.elements[f].value = buku[f]));
        formModal.show();
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        const id = $('buku-id').value;
        const payload = {};
        fields.forEach((f) => (payload[f] = form.elements[f].value.trim()));

        const btn = $('btn-simpan');
        btn.disabled = true;

        try {
            const res = await request(id ? `${cfg.baseUrl}/${id}` : cfg.baseUrl, id ? 'PUT' : 'POST', payload);
            formModal.hide();
            showAlert('success', res.message);
            await loadBuku(id ? state.page : 1); // tabel diperbarui otomatis
        } catch (err) {
            if (err.status === 422) {
                showFieldErrors(err.errors);
            } else {
                formModal.hide();
                showAlert('danger', err.message);
            }
        } finally {
            btn.disabled = false;
        }
    });

    /* ---------- DELETE ---------- */
    $('btn-hapus-ya').addEventListener('click', async () => {
        const btn = $('btn-hapus-ya');
        btn.disabled = true;
        try {
            const res = await request(`${cfg.baseUrl}/${state.deleteId}`, 'DELETE');
            deleteModal.hide();
            showAlert('success', res.message);
            await loadBuku();
        } catch (err) {
            deleteModal.hide();
            showAlert('danger', err.message);
        } finally {
            btn.disabled = false;
        }
    });

    /* ---------- Event ---------- */
    $('btn-tambah').addEventListener('click', () => openForm());

    tbody.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;
        const buku = state.rows[btn.dataset.id];
        if (btn.dataset.action === 'edit') openForm(buku);
        if (btn.dataset.action === 'delete') {
            state.deleteId = buku.id;
            $('hapus-nama').textContent = `${buku.kode_buku} - ${buku.judul}`;
            deleteModal.show();
        }
    });

    pagination.addEventListener('click', (e) => {
        e.preventDefault();
        const link = e.target.closest('a[data-page]');
        if (link && !link.parentElement.classList.contains('disabled')) loadBuku(Number(link.dataset.page));
    });

    let timer;
    searchInput.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => { state.search = searchInput.value.trim(); loadBuku(1); }, 350);
    });

    loadBuku(1);
})();
