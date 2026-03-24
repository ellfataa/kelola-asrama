<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('resident_outs', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('identity_number');
        $table->string('phone')->nullable();
        // Kita simpan info kamar sebagai string/teks agar menjadi sejarah (history),
        // walau nanti kamar aslinya dihapus, data histori ini tidak akan hilang/error.
        $table->string('room_info');
        $table->date('entry_date');
        $table->date('exit_date');
        $table->text('reason')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resident_outs');
    }
};
