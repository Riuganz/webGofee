<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use App\Models\Menu;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $mejas = Meja::all();
        $menus = Menu::with('kategori')->where('stok_status', 'Tersedia')->get();
        $popularMenus = Menu::with('kategori')->where('stok_status', 'Tersedia')->inRandomOrder()->limit(4)->get();
        return view('customer.dashboard', compact('mejas', 'menus', 'popularMenus'));
    }
}
