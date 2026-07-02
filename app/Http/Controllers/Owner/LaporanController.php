<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class LaporanController extends Controller
{
    private function getQueryData($startDate, $endDate)
    {
        return Reservasi::with(['user', 'meja', 'detailPesanans.menu'])
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->get();
    }

    private function getBreakdownData($startDate, $endDate)
    {
        return Reservasi::select(
            DB::raw('DATE(tanggal) as date'),
            DB::raw('COUNT(*) as total_transaksi'),
            DB::raw('SUM(CASE WHEN status_reservasi = "Selesai" THEN total_harga ELSE 0 END) as pendapatan')
        )
            ->whereDate('tanggal', '>=', $startDate)
            ->whereDate('tanggal', '<=', $endDate)
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('date')
            ->get();
    }

    public function index(Request $request)
    {
        $startDate = $request->start_date ?: now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?: now()->toDateString();

        $reservasis = $this->getQueryData($startDate, $endDate);
        $totalRevenue = $reservasis->where('status_reservasi', 'Selesai')->sum('total_harga');
        $totalTransactions = $reservasis->where('status_reservasi', 'Selesai')->count();

        // Daily breakdown
        $dailyBreakdown = $this->getBreakdownData($startDate, $endDate);

        return view('owner.laporan', compact(
            'reservasis', 'totalRevenue', 'totalTransactions',
            'dailyBreakdown', 'startDate', 'endDate'
        ));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->start_date ?: now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?: now()->toDateString();

        $reservasis = $this->getQueryData($startDate, $endDate);
        $titles = ['ID', 'Pelanggan', 'Tipe', 'Tanggal', 'Total', 'Status'];

        $callback = function() use ($reservasis, $titles) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $titles);

            foreach ($reservasis as $r) {
                fputcsv($file, [
                    $r->id_reservasi,
                    $r->user->name ?? 'Walk-in',
                    $r->tipe,
                    Carbon::parse($r->tanggal)->format('d/m/Y H:i'),
                    number_format($r->total_harga, 0, ',', '.'),
                    $r->status_reservasi,
                ]);
            }

            fclose($file);
        };

        $fileName = 'laporan-transaksi-' . $startDate . '-sampai-' . $endDate . '.csv';

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date ?: now()->startOfMonth()->toDateString();
        $endDate = $request->end_date ?: now()->toDateString();

        $reservasis = $this->getQueryData($startDate, $endDate);
        $totalRevenue = $reservasis->where('status_reservasi', 'Selesai')->sum('total_harga');
        $totalTransactions = $reservasis->where('status_reservasi', 'Selesai')->count();
        $dailyBreakdown = $this->getBreakdownData($startDate, $endDate);

        $pdf = Pdf::loadView('owner.laporan-pdf', compact(
            'reservasis', 'totalRevenue', 'totalTransactions',
            'dailyBreakdown', 'startDate', 'endDate'
        ));

        $fileName = 'laporan-transaksi-' . $startDate . '-sampai-' . $endDate . '.pdf';

        return $pdf->download($fileName);
    }
}
