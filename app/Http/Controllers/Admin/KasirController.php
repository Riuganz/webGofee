<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function create()
    {
        $menus = Menu::with('kategori')->where('stok_status', 'Tersedia')->get();
        $mejas = Meja::where('status_meja', 'Tersedia')->get();
        return view('admin.kasir.create', compact('menus', 'mejas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'tipe' => 'required|in:dine-in,pick-up',
            'id_meja' => 'required_if:tipe,dine-in|exists:meja,id_meja',
            'items' => 'required|array',
            'items.*.id_menu' => 'required|exists:menu,id_menu',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        // Create reservasi with walk-in customer (no user account)
        $total = 0;
        $items = [];
        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['id_menu']);
            $subtotal = $menu->harga * $item['jumlah'];
            $total += $subtotal;
            $items[] = [
                'id_menu' => $menu->id_menu,
                'jumlah_beli' => $item['jumlah'],
                'subtotal' => $subtotal,
            ];
        }

        $reservasi = Reservasi::create([
            'id_user' => auth()->id(),
            'id_meja' => $request->tipe === 'dine-in' ? $request->id_meja : null,
            'tanggal' => now()->toDateString(),
            'jam' => now()->toTimeString(),
            'jumlah_orang' => 1,
            'tipe' => $request->tipe,
            'catatan' => 'Walk-in: ' . $request->nama_pelanggan,
            'total_harga' => $total,
            'status_reservasi' => 'Selesai',
        ]);

        // Update status meja menjadi Terisi jika dine-in
        if ($request->tipe === 'dine-in' && $request->id_meja) {
            Meja::where('id_meja', $request->id_meja)->update(['status_meja' => 'Terisi']);
        }

        foreach ($items as $item) {
            DetailPesanan::create([
                'id_reservasi' => $reservasi->id_reservasi,
                'id_menu' => $item['id_menu'],
                'jumlah_beli' => $item['jumlah_beli'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        return redirect()->route('admin.kasir.create')
            ->with('success', 'Pesanan walk-in berhasil dicatat. Total: Rp ' . number_format($total, 0, ',', '.'));
    }
}
