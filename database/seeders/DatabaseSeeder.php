<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin DRIP',
            'email' => 'admin@dripgofee.com',
            'no_wa' => '081234567890',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Owner
        User::create([
            'name' => 'Farhan Nur Faturohman (Amao)',
            'email' => 'owner@dripgofee.com',
            'no_wa' => '081234567891',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
        ]);

        // Create Sample Customer
        User::create([
            'name' => 'Pelanggan Demo',
            'email' => 'customer@dripgofee.com',
            'no_wa' => '081234567892',
            'password' => Hash::make('customer123'),
            'role' => 'customer',
        ]);

        // Create Meja
        $mejas = [
            ['nomor_meja' => 'M01', 'kapasitas' => 2, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M02', 'kapasitas' => 2, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M03', 'kapasitas' => 4, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M04', 'kapasitas' => 4, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M05', 'kapasitas' => 4, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M06', 'kapasitas' => 6, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M07', 'kapasitas' => 2, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M08', 'kapasitas' => 2, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M09', 'kapasitas' => 6, 'status_meja' => 'Tersedia'],
            ['nomor_meja' => 'M10', 'kapasitas' => 8, 'status_meja' => 'Tersedia'],
        ];
        foreach ($mejas as $meja) {
            Meja::create($meja);
        }

        // Create Kategori
        $kategoris = [
            ['nama_kategori' => 'Kopi'],
            ['nama_kategori' => 'Non-Kopi'],
            ['nama_kategori' => 'Makanan Ringan'],
            ['nama_kategori' => 'Makanan Berat'],
            ['nama_kategori' => 'Minuman Segar'],
        ];
        foreach ($kategoris as $kat) {
            Kategori::create($kat);
        }

        // Create Menu
        $menus = [
            ['nama_menu' => 'Espresso', 'deskripsi' => 'Kopi hitam pekat khas Italia', 'harga' => 15000, 'id_kategori' => 1, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Cappuccino', 'deskripsi' => 'Espresso dengan busa susu lembut', 'harga' => 25000, 'id_kategori' => 1, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Latte', 'deskripsi' => 'Kopi dengan susu steam creamy', 'harga' => 27000, 'id_kategori' => 1, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Mocha', 'deskripsi' => 'Perpaduan kopi dan coklat', 'harga' => 30000, 'id_kategori' => 1, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Matcha Latte', 'deskripsi' => 'Teh matcha premium dengan susu', 'harga' => 28000, 'id_kategori' => 2, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Chocolate', 'deskripsi' => 'Minuman coklat panas/dingin', 'harga' => 25000, 'id_kategori' => 2, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Red Velvet', 'deskripsi' => 'Minuman red velvet creamy', 'harga' => 28000, 'id_kategori' => 2, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'French Fries', 'deskripsi' => 'Kentang goreng renyah', 'harga' => 20000, 'id_kategori' => 3, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Nachos', 'deskripsi' => 'Nachos dengan saus keju', 'harga' => 22000, 'id_kategori' => 3, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Pisang Goreng', 'deskripsi' => 'Pisang goreng crispy toping coklat', 'harga' => 18000, 'id_kategori' => 3, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Nasi Goreng', 'deskripsi' => 'Nasi goreng spesial dengan telur', 'harga' => 35000, 'id_kategori' => 4, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Mie Goreng', 'deskripsi' => 'Mie goreng dengan sayuran dan telur', 'harga' => 30000, 'id_kategori' => 4, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Chicken Katsu', 'deskripsi' => 'Ayam katsu dengan nasi dan salad', 'harga' => 38000, 'id_kategori' => 4, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Lemon Tea', 'deskripsi' => 'Teh dengan perasan lemon segar', 'harga' => 15000, 'id_kategori' => 5, 'stok_status' => 'Tersedia'],
            ['nama_menu' => 'Orange Juice', 'deskripsi' => 'Jus jeruk segar asli', 'harga' => 18000, 'id_kategori' => 5, 'stok_status' => 'Tersedia'],
        ];
        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}

