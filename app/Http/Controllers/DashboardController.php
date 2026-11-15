<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Modules\Perpustakaan\Models\Buku;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'totalJudul' => Buku::count(),
            'totalStok' => (int) Buku::sum('stok'),
            'totalKategori' => Buku::distinct()->count('kategori'),
            'stokMenipis' => Buku::where('stok', '<=', 5)->count(),
            'terbaru' => Buku::latest('id')->take(5)->get(),
        ]);
    }
}
