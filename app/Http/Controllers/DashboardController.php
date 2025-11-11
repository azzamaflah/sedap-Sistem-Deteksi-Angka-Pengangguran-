<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WilayahTugas;
use App\Models\Dsrt;
use App\Models\Responden;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ===== 1. LOGIKA FILTER =====
        
        // --- Cari tahun default (tahun data terakhir) jika tidak ada request 'year' ---
        $maxRespondenYear = (int)Responden::max(DB::raw('YEAR(created_at)'));
        $maxDsrtYear = (int)Dsrt::max(DB::raw('YEAR(created_at)'));
        $maxWilayahYear = (int)WilayahTugas::max(DB::raw('YEAR(created_at)'));
        $defaultYear = max($maxRespondenYear, $maxDsrtYear, $maxWilayahYear, (int)date('Y'));

        // --- Ambil input filter ---
        $selectedYear = $request->input('year', $defaultYear); 
        $selectedSemester = $request->input('semester');

        // --- Buat daftar tahun untuk dropdown (GABUNGAN DARI SEMUA TABEL) ---
        
        // ======================= PERBAIKAN DI SINI =======================
        // Filter whereNotNull('created_at') harus ada di DALAM setiap query
        
        $yearsResponden = DB::table('responden')
                            ->select(DB::raw('YEAR(created_at) as year'))
                            ->whereNotNull('created_at') // <-- DIPINDAH KE SINI
                            ->distinct();
                            
        $yearsDsrt = DB::table('dsrt')
                       ->select(DB::raw('YEAR(created_at) as year'))
                       ->whereNotNull('created_at') // <-- DITAMBAHKAN DI SINI
                       ->distinct();
                       
        $yearsWilayah = DB::table('bloksensus')
                          ->select(DB::raw('YEAR(created_at) as year'))
                          ->whereNotNull('created_at') // <-- DITAMBAHKAN DI SINI
                          ->distinct();
        
        $availableYears = $yearsResponden->union($yearsDsrt)->union($yearsWilayah)
                            // whereNotNull('year') <-- DIHAPUS DARI SINI
                            ->orderBy('year', 'desc')
                            ->pluck('year');
        // ================================================================

        // --- Buat string untuk tampilan judul chart ---
        $filterDisplay = "Tahun $selectedYear";
        if ($selectedSemester == 1) $filterDisplay .= " (Semester 1)";
        if ($selectedSemester == 2) $filterDisplay .= " (Semester 2)";
        if (!$selectedSemester) $filterDisplay .= " (Semua Semester)";

        // --- Closure untuk filter semester ---
        $applySemesterFilter = function ($query) use ($selectedSemester) {
            if ($selectedSemester == 1) {
                $query->whereMonth('created_at', '>=', 1)
                      ->whereMonth('created_at', '<=', 6);
            } elseif ($selectedSemester == 2) {
                $query->whereMonth('created_at', '>=', 7)
                      ->whereMonth('created_at', '<=', 12);
            }
        };

        // --- Closure untuk filter semester (dengan alias tabel 'r') ---
        $applySemesterFilterAliasR = function ($query) use ($selectedSemester) {
            if ($selectedSemester == 1) {
                $query->whereMonth('r.created_at', '>=', 1)
                      ->whereMonth('r.created_at', '<=', 6);
            } elseif ($selectedSemester == 2) {
                $query->whereMonth('r.created_at', '>=', 7)
                      ->whereMonth('r.created_at', '<=', 12);
            }
        };

        // ===== 2. TOTAL COUNTS (DENGAN FILTER) =====
        $totalWilayahTugas = WilayahTugas::whereYear('created_at', $selectedYear);
        $totalDsrt = Dsrt::whereYear('created_at', $selectedYear);
        $totalResponden = Responden::whereYear('created_at', $selectedYear);
        
        if ($selectedSemester) {
            $applySemesterFilter($totalWilayahTugas);
            $applySemesterFilter($totalDsrt);
            $applySemesterFilter($totalResponden);
        }

        $totalWilayahTugas = $totalWilayahTugas->count();
        $totalDsrt = $totalDsrt->count();
        $totalResponden = $totalResponden->count();
        $totalPengguna = User::count(); // Total pengguna tidak difilter

        
        // ===== 3. GRAFIK (DENGAN FILTER) =====

        // --- GRAFIK 1: Bekerja vs Pengangguran ---
        $statusKetenagakerjaanQuery = Responden::select(
                DB::raw('SUM(CASE WHEN bekerja = "1" THEN 1 ELSE 0 END) as total_bekerja'),
                DB::raw('SUM(CASE WHEN pengangguran = "1" THEN 1 ELSE 0 END) as total_pengangguran'),
                DB::raw('SUM(CASE WHEN (bekerja IS NULL OR bekerja != "1") AND (pengangguran IS NULL OR pengangguran != "1") THEN 1 ELSE 0 END) as total_lainnya')
            )
            ->whereYear('created_at', $selectedYear);
        
        if ($selectedSemester) {
            $applySemesterFilter($statusKetenagakerjaanQuery);
        }
        $statusKetenagakerjaan = $statusKetenagakerjaanQuery->first();

        $chartStatusPekerjaan = [
            'labels' => ['Bekerja', 'Pengangguran', 'Lainnya (Sekolah, ART, dll)'],
            'data' => [
                (int) ($statusKetenagakerjaan->total_bekerja ?? 0),
                (int) ($statusKetenagakerjaan->total_pengangguran ?? 0),
                (int) ($statusKetenagakerjaan->total_lainnya ?? 0)
            ],
            'colors' => ['#28a745', '#dc3545', '#6c757d']
        ];

        // --- GRAFIK 2: Progress Entri Rumah Tangga by NKS ---
        $progressNKSQuery = Dsrt::select('id_nks', DB::raw('count(*) as total'))
            ->whereNotNull('id_nks')
            ->whereYear('created_at', $selectedYear);
        
        if ($selectedSemester) {
            $applySemesterFilter($progressNKSQuery);
        }
        
        $progressNKS = $progressNKSQuery->groupBy('id_nks')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $chartNKS = [
            'labels' => $progressNKS->pluck('id_nks')->toArray(),
            'data' => $progressNKS->pluck('total')->toArray(),
            'backgroundColor' => $this->generateColors(count($progressNKS))
        ];

        // --- GRAFIK 3: Progress Entri Responden by Pengawas ---
        $progressPengawasQuery = Responden::from('responden as r')
            ->join('bloksensus as bs', function ($join) {
                $join->on('r.id_kec', '=', 'bs.id_kec')
                     ->on('r.id_desa', '=', 'bs.id_desa')
                     ->on('r.id_bs', '=', 'bs.id_bs');
            })
            ->join('users as u', 'bs.id_user', '=', 'u.id')
            ->select('u.name', DB::raw('count(r.no) as total'))
            ->whereYear('r.created_at', $selectedYear);

        if ($selectedSemester) {
            $applySemesterFilterAliasR($progressPengawasQuery);
        }

        $progressPengawas = $progressPengawasQuery->groupBy('u.id', 'u.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        $chartPengawas = [
            'labels' => $progressPengawas->pluck('name')->toArray(),
            'data' => $progressPengawas->pluck('total')->toArray(),
            'backgroundColor' => $this->generateColors(count($progressPengawas))
        ];


        // --- GRAFIK 4: Sebaran Pengangguran per Kecamatan ---
        $sebaranPengangguranQuery = Responden::select(
                'k.nama_kec',
                DB::raw('SUM(CASE WHEN r.pengangguran = "1" THEN 1 ELSE 0 END) as jumlah_pengangguran')
            )
            ->from('responden as r')
            ->join('kec as k', 'r.id_kec', '=', 'k.id_kec')
            ->whereYear('r.created_at', $selectedYear)
            ->where('r.pengangguran', '1');
            
        if ($selectedSemester) {
            $applySemesterFilterAliasR($sebaranPengangguranQuery);
        }

        $sebaranPengangguran = $sebaranPengangguranQuery->groupBy('k.nama_kec')
            ->orderBy('jumlah_pengangguran', 'DESC')
            ->get();

        $chartSebaranPengangguran = [
            'labels' => $sebaranPengangguran->pluck('nama_kec'),
            'data'   => $sebaranPengangguran->pluck('jumlah_pengangguran'),
            'colors' => $this->generateColors(count($sebaranPengangguran))
        ];


        // ===== 4. Kirim semua data ke View =====
        return view('dashboard', compact(
            'totalWilayahTugas',
            'totalDsrt',
            'totalResponden',
            'totalPengguna',
            'availableYears', 
            'selectedYear',   
            'selectedSemester', 
            'filterDisplay',    
            'chartStatusPekerjaan',
            'chartNKS',
            'chartPengawas',
            'chartSebaranPengangguran'
        ));
    }

    /**
     * Generate random colors for chart
     */
    private function generateColors($count)
    {
        $colors = [
            '#007bff', '#28a745', 
        ];

        if ($count == 0) {
            return [];
        }

        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $colors[$i % count($colors)];
        }

        return $result;
    }
}