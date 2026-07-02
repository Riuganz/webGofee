<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id('id_reservasi');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_meja')->nullable()->constrained('meja', 'id_meja')->onDelete('set null');
            $table->date('tanggal');
            $table->time('jam');
            $table->integer('jumlah_orang')->default(1);
            $table->enum('tipe', ['dine-in', 'pick-up'])->default('dine-in');
            $table->text('catatan')->nullable();
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status_reservasi', ['Menunggu Konfirmasi', 'Diterima', 'Selesai', 'Dibatalkan'])->default('Menunggu Konfirmasi');
            $table->timestamps();
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->foreignId('id_reservasi')->constrained('reservasi', 'id_reservasi')->onDelete('cascade');
            $table->foreignId('id_menu')->constrained('menu', 'id_menu')->onDelete('cascade');
            $table->integer('jumlah_beli');
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('reservasi');
    }
};
