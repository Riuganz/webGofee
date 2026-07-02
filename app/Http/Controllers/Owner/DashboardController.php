<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's stats
        $todayReservasi = Reservasi::whereDate('tanggal', today())->count();
        $todayRevenue = Reservasi::whereDate('tanggal', today())
            ->where('status_reservasi', 'Selesai')
            ->sum('total_harga');
        $todaySuccess = Reservasi::whereDate('tanggal', today())
            ->where('status_reservasi', 'Selesai')
            ->count();

        // Weekly stats
        $weeklyRevenue = Reservasi::whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status_reservasi', 'Selesai')
            ->sum('total_harga');

        // Monthly stats
        $monthlyRevenue = Reservasi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->where('status_reservasi', 'Selesai')
            ->sum('total_harga');

        // Daily revenue for chart (last 7 days)
        $dailyRevenue = Reservasi::select(
            DB::raw('DATE(tanggal) as date'),
            DB::raw('SUM(total_harga) as revenue')
        )
            ->where('status_reservasi', 'Selesai')
            ->where('tanggal', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('date')
            ->get();

        // Recent reservations
        $recentReservasis = Reservasi::with(['user', 'meja'])
            ->latest()
            ->take(10)
            ->get();

        return view('owner.dashboard', compact(
            'todayReservasi', 'todayRevenue', 'todaySuccess',
            'weeklyRevenue', 'monthlyRevenue',
            'dailyRevenue', 'recentReservasis'
        ));
    }
}
