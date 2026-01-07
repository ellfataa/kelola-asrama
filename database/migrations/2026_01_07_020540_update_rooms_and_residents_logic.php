<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus kolom is_exclusive dari rooms (jika sudah ada)
        Schema::table('rooms', function (Blueprint $table) {
            // Cek dulu biar gak error kalau belum ada
            if (Schema::hasColumn('rooms', 'is_exclusive')) {
                $table->dropColumn('is_exclusive');
            }
        });

        // Tambah kolom bed_slot di residents
        Schema::table('residents', function (Blueprint $table) {
            // Menyimpan nomor kasur (1, 2, 3, atau 4)
            $table->integer('bed_slot')->after('room_id');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->boolean('is_exclusive')->default(false);
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->dropColumn('bed_slot');
        });
    }
};
