<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservasiController extends Controller
{
    public function createDineIn(Request $request)
    {
        $menus = Menu::with('kategori')->where('stok_status', 'Tersedia')->get();

        $tanggal = $request->query('tanggal', date('Y-m-d'));
        $jam = $request->query('jam', date('H:i'));
        $jumlahOrang = $request->query('jumlah_orang', 1);

        // Ambil semua meja (ketersediaan ditentukan dari tabel reservasi, bukan status_meja)
        $mejas = Meja::where('kapasitas', '>=', $jumlahOrang)
            ->get();

        // Ambil id_meja yang sudah dibooking di tanggal tersebut (untuk 1 hari penuh)
        $bookedMejaIds = Reservasi::where('tanggal', $tanggal)
            ->whereIn('status_reservasi', ['Menunggu Konfirmasi', 'Diterima'])
            ->pluck('id_meja')
            ->toArray();

        return view('customer.reservasi_dinein', compact('mejas', 'menus', 'bookedMejaIds', 'tanggal', 'jam', 'jumlahOrang'));
    }

    public function createPickUp()
    {
        $menus = Menu::with('kategori')->where('stok_status', 'Tersedia')->get();
        return view('customer.reservasi_pickup', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:dine-in,pick-up',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam' => 'required',
            'jumlah_orang' => 'required|integer|min:1',
            'id_meja' => 'required_if:tipe,dine-in|exists:meja,id_meja',
            'metode_bayar' => 'required|in:transfer,bayar_ditempat',
            'catatan' => 'nullable|string',
            'items' => 'required|array',
            'items.*.id_menu' => 'required|exists:menu,id_menu',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Validate table availability for dine-in
            if ($request->tipe === 'dine-in') {
                $meja = Meja::findOrFail($request->id_meja);
                // Double booking prevention (cekp berdasarkan tanggal, bukan jam)
                $existingReservasi = Reservasi::where('id_meja', $request->id_meja)
                    ->where('tanggal', $request->tanggal)
                    ->whereIn('status_reservasi', ['Menunggu Konfirmasi', 'Diterima'])
                    ->exists();

                if ($existingReservasi) {
                    return back()->withErrors(['id_meja' => 'Meja sudah dipesan untuk waktu tersebut.'])->withInput();
                }
            }

            // Calculate total
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

            // Create reservasi
            $reservasi = Reservasi::create([
                'id_user' => Auth::id(),
                'id_meja' => $request->tipe === 'dine-in' ? $request->id_meja : null,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'jumlah_orang' => $request->jumlah_orang,
                'tipe' => $request->tipe,
                'metode_bayar' => $request->metode_bayar,
                'catatan' => $request->catatan,
                'total_harga' => $total,
                'status_bayar' => $request->metode_bayar === 'transfer' ? 'belum_bayar' : 'belum_bayar',
                'status_reservasi' => $request->metode_bayar === 'transfer' ? 'Menunggu Konfirmasi' : 'Menunggu Konfirmasi',
            ]);

            // Create detail pesanan
            foreach ($items as $item) {
                DetailPesanan::create([
                    'id_reservasi' => $reservasi->id_reservasi,
                    'id_menu' => $item['id_menu'],
                    'jumlah_beli' => $item['jumlah_beli'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            // Jika bayar transfer, redirect ke halaman pembayaran
            if ($request->metode_bayar === 'transfer') {
                return redirect()->route('customer.payment.show', $reservasi->id_reservasi)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
            }

            return redirect()->route('customer.riwayat')
                ->with('success', 'Pesanan berhasil dibuat! Menunggu konfirmasi admin.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function riwayat()
    {
        $reservasis = Reservasi::with(['meja', 'detailPesanans.menu', 'pembayaranTerakhir'])
            ->where('id_user', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        return view('customer.riwayat', compact('reservasis'));
    }
}
