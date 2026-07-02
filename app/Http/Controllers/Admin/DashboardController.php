<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMeja = Meja::count();
        $totalMenu = Menu::count();
        $pendingReservasi = Reservasi::where('status_reservasi', 'Menunggu Konfirmasi')->count();
        $todayReservasi = Reservasi::whereDate('tanggal', today())->count();
        $reservasiTerbaru = Reservasi::with(['user', 'meja'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalMeja', 'totalMenu', 'pendingReservasi', 'todayReservasi', 'reservasiTerbaru'
        ));
    }
}
