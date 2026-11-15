@extends('perpustakaan::layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="page-title mb-1">Halo, {{ auth()->user()->name }}</h1>
    <p class="text-muted mb-0">Ringkasan koleksi perpustakaan hari ini.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card card-perpus stat-card h-100"><div class="card-body">
            <div class="stat-icon bg-ink"><i class="bi bi-book"></i></div>
            <div class="stat-number">{{ $totalJudul }}</div>
            <div class="stat-label">Judul buku</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-perpus stat-card h-100"><div class="card-body">
            <div class="stat-icon bg-teal"><i class="bi bi-stack"></i></div>
            <div class="stat-number">{{ $totalStok }}</div>
            <div class="stat-label">Total eksemplar</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-perpus stat-card h-100"><div class="card-body">
            <div class="stat-icon bg-amber"><i class="bi bi-tags"></i></div>
            <div class="stat-number">{{ $totalKategori }}</div>
            <div class="stat-label">Kategori</div>
        </div></div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-perpus stat-card h-100"><div class="card-body">
            <div class="stat-icon bg-rose"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-number">{{ $stokMenipis }}</div>
            <div class="stat-label">Stok 5 atau kurang</div>
        </div></div>
    </div>
</div>

<div class="card card-perpus">
    <div class="card-body p-3 p-lg-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fs-5 mb-0 page-title">Buku terbaru</h2>
            <a href="{{ route('perpustakaan.buku.index') }}" class="btn btn-perpus-light btn-sm">Kelola data buku</a>
        </div>
        <div class="table-responsive">
            <table class="table table-perpus align-middle mb-0">
                <thead><tr><th>Kode</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th class="text-center">Stok</th></tr></thead>
                <tbody>
                @forelse ($terbaru as $b)
                    <tr>
                        <td><code>{{ $b->kode_buku }}</code></td>
                        <td class="fw-medium">{{ $b->judul }}</td>
                        <td>{{ $b->penulis }}</td>
                        <td><span class="badge badge-kategori">{{ $b->kategori }}</span></td>
                        <td class="text-center"><span class="badge {{ $b->stok <= 5 ? 'badge-stok-rendah' : 'badge-stok' }}">{{ $b->stok }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada buku. Tambahkan buku pertama di halaman Data Buku.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
