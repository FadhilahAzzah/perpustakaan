<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan') | Perpustakaan Kampus</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:wght@500;600&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/perpustakaan.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-perpus">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <i class="bi bi-journal-bookmark-fill me-2"></i>Perpustakaan Kampus
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama"
                aria-controls="menuUtama" aria-expanded="false" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuUtama">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perpustakaan.*') ? 'active' : '' }}" href="{{ route('perpustakaan.buku.index') }}">Data Buku</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-3 text-white">
                <a href="{{ route('profile.edit') }}" class="user-chip text-decoration-none">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ auth()->user()->name }}</span>
                    <span class="badge badge-role">{{ ucfirst(auth()->user()->role) }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">
    @yield('content')
</main>

<div id="alert-box" class="alert-floating" aria-live="polite"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
