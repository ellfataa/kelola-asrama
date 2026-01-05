<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            // Nomor kamar (A1, B2, dst) harus unik
            $table->string('number')->unique();

            // Kapasitas maksimal penghuni (misal: 2 orang)
            $table->integer('capacity');

            // Harga sewa per bulan/periode (12 digit, 0 desimal untuk Rupiah)
            $table->decimal('price', 12, 0);

            // Keterangan tambahan (AC, Kamar Mandi Dalam, dll)
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
