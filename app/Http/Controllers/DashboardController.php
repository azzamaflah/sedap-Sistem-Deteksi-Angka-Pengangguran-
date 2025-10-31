<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WilayahTugas;
use App\Models\Dsrt;
use App\Models\Responden;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Counts
        $totalWilayahTugas = WilayahTugas::count();
        $totalDsrt = Dsrt::count();
        $totalResponden = Responden::count();
        $totalPengguna = User::count();

        // ===== GRAFIK 1: Bekerja vs Pengangguran =====
        $statusPekerjaan = Responden::select('status_pekerjaan', DB::raw('count(*) as total'))
            ->whereNotNull('status_pekerjaan')
            ->groupBy('status_pekerjaan')
            ->get();

        $bekerja = 0;
        $pengangguran = 0;
        $lainnya = 0;

        foreach ($statusPekerjaan as $status) {
            if (in_array(strtolower($status->status_pekerjaan), ['bekerja', 'bekerja penuh', 'bekerja paruh waktu'])) {
                $bekerja += $status->total;
            } elseif (in_array(strtolower($status->status_pekerjaan), ['pengangguran', 'mencari pekerjaan', 'pengangguran terbuka'])) {
                $pengangguran += $status->total;
            } else {
                $lainnya += $status->total;
            }
        }

        $chartStatusPekerjaan = [
            'labels' => ['Bekerja', 'Pengangguran', 'Lainnya'],
            'data' => [$bekerja, $pengangguran, $lainnya],
            'colors' => ['#28a745', '#dc3545', '#6c757d']
        ];

        // ===== GRAFIK 2: Progress Entri Rumah Tangga by NKS =====
        $progressNKS = Dsrt::select('id_nks', DB::raw('count(*) as total'))
            ->whereNotNull('id_nks')
            ->groupBy('id_nks')
            ->orderBy('total', 'desc')
            ->limit(10) // Top 10 NKS
            ->get();

        $chartNKS = [
            'labels' => $progressNKS->pluck('id_nks')->toArray(),
            'data' => $progressNKS->pluck('total')->toArray(),
            'backgroundColor' => $this->generateColors(count($progressNKS))
        ];

        // ===== GRAFIK 3: Progress Entri Responden by Pengawas =====
        // Asumsi: Responden punya relasi ke User (pengawas) melalui created_by atau id_user
        // Sesuaikan dengan struktur database Anda

        // Opsi 1: Jika ada kolom created_by atau id_user di tabel responden
        $progressPengawas = Responden::select('respondens.created_by', 'users.name', DB::raw('count(*) as total'))
            ->leftJoin('users', 'respondens.created_by', '=', 'users.id')
            ->whereNotNull('respondens.created_by')
            ->groupBy('respondens.created_by', 'users.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Opsi 2: Jika menggunakan relasi melalui wilayah_tugas
        if ($progressPengawas->isEmpty()) {
            $progressPengawas = DB::table('respondens')
                ->join('wilayah_tugas', function ($join) {
                    $join->on('respondens.id_kec', '=', 'wilayah_tugas.id_kec')
                        ->on('respondens.id_desa', '=', 'wilayah_tugas.id_desa');
                })
                ->join('users', 'wilayah_tugas.id_user', '=', 'users.id')
                ->select('users.id', 'users.name', DB::raw('count(respondens.no) as total'))
                ->groupBy('users.id', 'users.name')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get();
        }

        // Opsi 3: Fallback - Group by User yang punya data
        if ($progressPengawas->isEmpty()) {
            $progressPengawas = User::select('users.id', 'users.name', DB::raw('0 as total'))
                ->limit(10)
                ->get();
        }

        $chartPengawas = [
            'labels' => $progressPengawas->pluck('name')->toArray(),
            'data' => $progressPengawas->pluck('total')->toArray(),
            'backgroundColor' => $this->generateColors(count($progressPengawas))
        ];

        return view('dashboard', compact(
            'totalWilayahTugas',
            'totalDsrt',
            'totalResponden',
            'totalPengguna',
            'chartStatusPekerjaan',
            'chartNKS',
            'chartPengawas'
        ));
    }

    /**
     * Generate random colors for chart
     */
    private function generateColors($count)
    {
        $colors = [
            '#007bff',
            '#28a745',
            '#ffc107',
            '#dc3545',
            '#17a2b8',
            '#6f42c1',
            '#fd7e14',
            '#20c997',
            '#e83e8c',
            '#6c757d',
            '#343a40',
            '#f8f9fa',
            '#00d4ff',
            '#ff6b6b',
            '#4ecdc4'
        ];

        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $colors[$i % count($colors)];
        }

        return $result;
    }
}
