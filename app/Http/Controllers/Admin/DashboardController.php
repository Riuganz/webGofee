<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMeja = Meja::count();
        $totalMenu = Menu::count();
        $pendingReservasi = Reservasi::where('status_reservasi', 'Menunggu Konfirmasi')->count();
        $todayReservasi = Reservasi::whereDate('tanggal', today())->count();

        // Pendapatan hari ini (dari reservasi dengan status Selesai)
        $pendapatanHariIni = Reservasi::whereDate('tanggal', today())
            ->where('status_reservasi', 'Selesai')
            ->sum('total_harga');

        // Data grafik 7 hari terakhir
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartLabels[] = now()->subDays($i)->isoFormat('dddd');
            $chartData[] = Reservasi::whereDate('tanggal', $date)
                ->whereIn('status_reservasi', ['Selesai', 'Diterima'])
                ->count();
        }

        $reservasiTerbaru = Reservasi::with(['user', 'meja'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalMeja', 'totalMenu', 'pendingReservasi', 'todayReservasi',
            'pendapatanHariIni', 'chartLabels', 'chartData', 'reservasiTerbaru'
        ));
    }
}
