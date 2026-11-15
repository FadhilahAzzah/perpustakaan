<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Memeriksa hak akses berdasarkan kolom `role` pada tabel users.
 * Pemeriksaan dilakukan di sisi server (Laravel), bukan hanya menyembunyikan tombol di UI.
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan aksi ini.');
        }

        return $next($request);
    }
}
