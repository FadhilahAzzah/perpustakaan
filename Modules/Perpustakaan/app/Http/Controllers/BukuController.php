<?php

namespace Modules\Perpustakaan\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Perpustakaan\Http\Requests\BukuRequest;
use Modules\Perpustakaan\Models\Buku;

class BukuController extends Controller
{
    /** Halaman utama (hanya kerangka; data dimuat lewat fetch). */
    public function index(): View
    {
        return view('perpustakaan::buku.index');
    }

    /** READ: daftar buku (JSON) + pencarian + paginasi. */
    public function data(Request $request): JsonResponse
    {
        $query = Buku::query();

        if ($cari = trim((string) $request->query('search'))) {
            $query->where(function ($q) use ($cari) {
                $q->where('kode_buku', 'like', "%{$cari}%")
                  ->orWhere('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%")
                  ->orWhere('kategori', 'like', "%{$cari}%");
            });
        }

        return response()->json($query->latest('id')->paginate(10));
    }

    /** CREATE: admin & petugas. */
    public function store(BukuRequest $request): JsonResponse
    {
        $buku = Buku::create($request->validated());

        return response()->json([
            'message' => 'Buku "'.$buku->judul.'" berhasil ditambahkan.',
            'data' => $buku,
        ], 201);
    }

    /** UPDATE: hanya admin (dijaga middleware role:admin di route). */
    public function update(BukuRequest $request, Buku $buku): JsonResponse
    {
        $buku->update($request->validated());

        return response()->json([
            'message' => 'Buku "'.$buku->judul.'" berhasil diperbarui.',
            'data' => $buku,
        ]);
    }

    /** DELETE: hanya admin (dijaga middleware role:admin di route). */
    public function destroy(Buku $buku): JsonResponse
    {
        $judul = $buku->judul;
        $buku->delete();

        return response()->json(['message' => 'Buku "'.$judul.'" berhasil dihapus.']);
    }
}
