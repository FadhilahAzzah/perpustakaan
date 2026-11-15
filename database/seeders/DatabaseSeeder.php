<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Perpustakaan\Models\Buku;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@perpus.test'], [
            'name' => 'Admin Perpustakaan',
            'password' => 'password',
            'role' => User::ROLE_ADMIN,
        ]);

        User::updateOrCreate(['email' => 'petugas@perpus.test'], [
            'name' => 'Petugas Perpustakaan',
            'password' => 'password',
            'role' => User::ROLE_PETUGAS,
        ]);

        $buku = [
            ['BK-001', 'Pemrograman Web dengan Laravel', 'Budi Santoso', 'Teknologi', 12],
            ['BK-002', 'Basis Data Relasional', 'Siti Rahmawati', 'Teknologi', 8],
            ['BK-003', 'Laskar Pelangi', 'Andrea Hirata', 'Novel', 4],
            ['BK-004', 'Pengantar Ekonomi Mikro', 'Dewi Lestari', 'Ekonomi', 15],
            ['BK-005', 'Sejarah Indonesia Modern', 'M.C. Ricklefs', 'Sejarah', 3],
        ];

        foreach ($buku as [$kode, $judul, $penulis, $kategori, $stok]) {
            Buku::updateOrCreate(['kode_buku' => $kode], compact('judul', 'penulis', 'kategori', 'stok'));
        }
    }
}
