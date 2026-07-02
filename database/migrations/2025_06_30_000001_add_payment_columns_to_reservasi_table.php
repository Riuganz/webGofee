<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->enum('metode_bayar', ['transfer', 'bayar_ditempat'])->default('bayar_ditempat')->after('catatan');
            $table->enum('status_bayar', ['belum_bayar', 'lunas', 'gagal'])->default('belum_bayar')->after('total_harga');
        });
    }

    public function down(): void
    {
        Schema::table('reservasi', function (Blueprint $table) {
            $table->dropColumn(['metode_bayar', 'status_bayar']);
        });
    }
};