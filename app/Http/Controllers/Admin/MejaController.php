<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index(Request $request)
    {
        $query = Meja::orderBy('nomor_meja');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_meja', $request->status);
        }

        // Pencarian berdasarkan nomor meja
        if ($request->filled('cari')) {
            $query->where('nomor_meja', 'like', '%' . $request->cari . '%');
        }

        $mejas = $query->get();

        $statuses = ['Tersedia', 'Terisi', 'Dibooking'];
        return view('admin.meja.index', compact('mejas', 'statuses'));
    }

    public function create()
    {
        return view('admin.meja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:meja,nomor_meja',
            'kapasitas' => 'required|integer|min:1',
            'status_meja' => 'required|in:Tersedia,Terisi,Dibooking',
        ]);

        Meja::create($request->all());

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Meja $meja)
    {
        return view('admin.meja.edit', compact('meja'));
    }

    public function update(Request $request, Meja $meja)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:10|unique:meja,nomor_meja,' . $meja->id_meja . ',id_meja',
            'kapasitas' => 'required|integer|min:1',
            'status_meja' => 'required|in:Tersedia,Terisi,Dibooking',
        ]);

        $meja->update($request->all());

        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Meja $meja)
    {
        $meja->delete();
        return redirect()->route('admin.meja.index')->with('success', 'Meja berhasil dihapus.');
    }

    public function toggleStatus(Meja $meja)
    {
        $statuses = ['Tersedia', 'Terisi', 'Dibooking'];
        $currentIndex = array_search($meja->status_meja, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $meja->update(['status_meja' => $statuses[$nextIndex]]);

        return redirect()->route('admin.meja.index')->with('success', 'Status meja berhasil diubah.');
    }
}
