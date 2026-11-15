@extends('perpustakaan::layouts.master')

@section('title', 'Data Buku')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <h1 class="page-title mb-1">Data Buku</h1>
        <p class="text-muted mb-0">
            @if (auth()->user()->isAdmin())
                Anda dapat menambah, mengubah, dan menghapus buku.
            @else
                Anda dapat melihat dan menambah buku. Mengubah dan menghapus hanya untuk admin.
            @endif
        </p>
    </div>
    <button type="button" class="btn btn-perpus-primary" id="btn-tambah">
        <i class="bi bi-plus-lg me-1"></i>Tambah buku
    </button>
</div>

<div class="card card-perpus">
    <div class="card-body p-3 p-lg-4">
        <div class="search-wrap mb-3">
            <i class="bi bi-search"></i>
            <input type="search" id="search" class="form-control" placeholder="Cari kode, judul, penulis, atau kategori">
        </div>

        <div class="table-responsive">
            <table class="table table-perpus align-middle mb-0">
                <thead>
                <tr>
                    <th style="width:60px">No</th>
                    <th>Kode</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Kategori</th>
                    <th class="text-center">Stok</th>
                    <th class="text-end" style="width:170px">Aksi</th>
                </tr>
                </thead>
                <tbody id="buku-tbody">
                <tr><td colspan="7" class="text-center py-5 text-muted">Memuat data buku...</td></tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
            <small class="text-muted" id="table-info"></small>
            <nav aria-label="Navigasi halaman"><ul class="pagination pagination-perpus mb-0" id="pagination"></ul></nav>
        </div>
    </div>
</div>

{{-- Modal tambah / ubah --}}
<div class="modal fade" id="modal-buku" tabindex="-1" aria-labelledby="modal-buku-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content modal-perpus" id="form-buku" novalidate>
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="modal-buku-title">Tambah buku</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="buku-id">

                <div class="mb-3">
                    <label for="kode_buku" class="form-label">Kode buku</label>
                    <input type="text" class="form-control" id="kode_buku" name="kode_buku" maxlength="20" placeholder="Contoh: BK-006">
                    <div class="invalid-feedback" data-error-for="kode_buku"></div>
                </div>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control" id="judul" name="judul" maxlength="150">
                    <div class="invalid-feedback" data-error-for="judul"></div>
                </div>
                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" class="form-control" id="penulis" name="penulis" maxlength="100">
                    <div class="invalid-feedback" data-error-for="penulis"></div>
                </div>
                <div class="row g-3">
                    <div class="col-sm-7">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" maxlength="50">
                        <div class="invalid-feedback" data-error-for="kategori"></div>
                    </div>
                    <div class="col-sm-5">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control" id="stok" name="stok" min="0" step="1" value="0">
                        <div class="invalid-feedback" data-error-for="stok"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-perpus-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-perpus-primary" id="btn-simpan">Simpan buku</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal konfirmasi hapus --}}
<div class="modal fade" id="modal-hapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content modal-perpus">
            <div class="modal-body text-center p-4">
                <div class="delete-icon mb-3"><i class="bi bi-trash3"></i></div>
                <h2 class="fs-5">Hapus buku ini?</h2>
                <p class="text-muted mb-4" id="hapus-nama"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-perpus-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-perpus-danger" id="btn-hapus-ya">Ya, hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.PERPUS = {
        dataUrl: @json(route('perpustakaan.buku.data')),
        baseUrl: @json(route('perpustakaan.buku.store')),
        loginUrl: @json(route('login')),
        isAdmin: @json(auth()->user()->isAdmin()),
    };
</script>
<script src="{{ asset('js/buku.js') }}"></script>
@endpush
