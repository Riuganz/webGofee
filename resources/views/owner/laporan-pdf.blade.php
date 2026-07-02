<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi - {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 14px; font-weight: normal; color: #666; margin-bottom: 16px; }
        .summary { margin-bottom: 20px; }
        .summary p { margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #1a252f; color: #fff; font-weight: 600; }
        tr:nth-child(even) { background: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .section-title { background: #343a40; color: #fff; padding: 6px 10px; font-weight: 600; margin-top: 24px; font-size: 13px; }
    </style>
</head>
<body>
    <h1>Laporan Transaksi DRIP GO.FEE</h1>
    <h2>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h2>

    <div class="summary">
        <p><strong>Total Transaksi Sukses:</strong> {{ $totalTransactions }}</p>
        <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>

    @if($dailyBreakdown->count() > 0)
        <div class="section-title">Rekapitulasi Harian</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="text-center">Total Transaksi</th>
                    <th class="text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyBreakdown as $db)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($db->date)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $db->total_transaksi }}</td>
                        <td class="text-right">Rp {{ number_format($db->pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="section-title">Detail Transaksi</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Pelanggan</th>
                <th>Tipe</th>
                <th>Tanggal</th>
                <th class="text-right">Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasis as $r)
                <tr>
                    <td>{{ $r->id_reservasi }}</td>
                    <td>{{ $r->user->name ?? 'Walk-in' }}</td>
                    <td>{{ $r->tipe }}</td>
                    <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y H:i') }}</td>
                    <td class="text-right">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $r->status_reservasi }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data transaksi.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
