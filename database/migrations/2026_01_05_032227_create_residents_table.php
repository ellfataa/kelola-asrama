<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel rooms.
            // Jika kamar dihapus, data penghuni ikut terhapus (cascade) atau bisa diatur lain.
            // Disini kita pakai cascade untuk kemudahan development.
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');

            $table->string('name');

            // NIK atau NIM (Identitas unik)
            $table->string('identity_number')->unique();

            $table->string('phone')->nullable();

            // Tanggal mulai masuk asrama
            $table->date('entry_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
