<?php

namespace App\Http\Controllers;

use App\Models\WilayahTugas;
use App\Models\Dsrt;
use App\Models\Responden;
use App\Models\UserCustom;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWilayahTugas = WilayahTugas::count();
        $totalDsrt = Dsrt::count();
        $totalResponden = Responden::count();
        $totalPengguna = UserCustom::count();

        return view('dashboard', compact(
            'totalBlokSensus',
            'totalDsrt',
            'totalResponden',
            'totalPengguna'
        ));
    }
}
