<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();                                   // BIGINT, Primary Key
            $table->string('kode_buku', 20)->unique();      // Kode unik buku
            $table->string('judul', 150);
            $table->string('penulis', 100);
            $table->string('kategori', 50);
            $table->integer('stok')->default(0);
            $table->timestamps();                           // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
