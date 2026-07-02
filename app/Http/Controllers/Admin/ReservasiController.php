<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasis = Reservasi::with(['user', 'meja', 'detailPesanans.menu'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.reservasi.index', compact('reservasis'));
    }

    public function show(Reservasi $reservasi)
    {
        $reservasi->load(['user', 'meja', 'detailPesanans.menu']);
        return view('admin.reservasi.show', compact('reservasi'));
    }

    public function terima(Reservasi $reservasi)
    {
        $reservasi->update(['status_reservasi' => 'Diterima']);

        if ($reservasi->meja) {
            $reservasi->meja->update(['status_meja' => 'Terisi']);
        }

        return redirect()->route('admin.reservasi.index')->with('success', 'Reservasi diterima.');
    }

    public function tolak(Reservasi $reservasi)
    {
        $reservasi->update(['status_reservasi' => 'Dibatalkan']);

        if ($reservasi->meja) {
            $reservasi->meja->update(['status_meja' => 'Tersedia']);
        }

        return redirect()->route('admin.reservasi.index')->with('success', 'Reservasi ditolak.');
    }

    public function selesai(Reservasi $reservasi)
    {
        $reservasi->update(['status_reservasi' => 'Selesai']);

        if ($reservasi->meja) {
            $reservasi->meja->update(['status_meja' => 'Tersedia']);
        }

        return redirect()->route('admin.reservasi.index')->with('success', 'Reservasi selesai.');
    }
}
