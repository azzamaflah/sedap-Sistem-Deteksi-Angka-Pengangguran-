<?php

namespace App\Http\Controllers;

use App\Models\Dsrt;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\WilayahTugas;
use Illuminate\Http\Request;
use App\Exports\DsrtTemplateExport;
use App\Imports\DsrtImport;
use App\Exports\DsrtExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; // <-- 1. TAMBAHKAN INI
use Illuminate\Support\Facades\Log; // <-- Tambahkan ini untuk error handling

class DsrtController extends Controller
{
    public function index(Request $request)
    {
        $query = Dsrt::with(['kecamatan', 'desa', 'wilayahTugas']);

        // Ambil input filter
        $search = $request->input('search');
        $selectedYear = $request->input('year');
        $selectedSemester = $request->input('semester');

        // Filter: Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_kec', 'like', "%{$search}%")
                    ->orWhere('id_desa', 'like', "%{$search}%")
                    ->orWhere('id_bs', 'like', "%{$search}%")
                    ->orWhere('id_nks', 'like', "%{$search}%")
                    ->orWhere('id_nurt', 'like', "%{$search}%")
                    ->orWhere('respon', 'like', "%{$search}%") // <-- Ditambahkan filter by respon
                    ->orWhereHas('kecamatan', function ($q) use ($search) {
                        $q->where('nama_kec', 'like', "%{$search}%");
                    })
                    ->orWhereHas('desa', function ($q) use ($search) {
                        $q->where('nama_desa', 'like', "%{$search}%");
                    });
            });
        }

        // ===== 2. LOGIKA FILTER SEMESTER BARU =====
        if ($selectedYear) {
            $query->whereYear('created_at', $selectedYear);
        }

        if ($selectedSemester) {
            if ($selectedSemester == 1) {
                // Semester 1: Januari - Juni
                $query->whereMonth('created_at', '>=', 1)
                      ->whereMonth('created_at', '<=', 6);
            } elseif ($selectedSemester == 2) {
                // Semester 2: Juli - Desember
                $query->whereMonth('created_at', '>=', 7)
                      ->whereMonth('created_at', '<=', 12);
            }
        }
        // ==========================================

        // 3. Ambil data tahun unik untuk dropdown filter
        // PASTIKAN MENGAMBIL DARI TABEL 'dsrt'
        $availableYears = DB::table('dsrt') 
                            ->select(DB::raw('YEAR(created_at) as year'))
                            ->whereNotNull('created_at')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        // 4. Modifikasi paginasi untuk menyimpan parameter filter
        $data = $query->paginate(20)->appends($request->except('page'));

        // 5. Kirim data ke view
        return view('dsrt.dsrt', compact(
            'data',
            'availableYears',
            'selectedYear',
            'selectedSemester'
        ));
    }

    public function create()
    {
        $kecamatan = Kecamatan::all();
        return view('dsrt.create', compact('kecamatan'));
    }

    // AJAX: Get Desa by Kecamatan
    public function getDesaByKecamatan($id_kec)
    {
        $desa = Desa::where('id_kec', $id_kec)->get();
        return response()->json($desa);
    }

    // AJAX: Get Blok Sensus by Desa
    public function getBlokSensusByDesa($id_desa)
    {
        $blokSensus = WilayahTugas::where('id_desa', $id_desa)
            ->select('id_bs')
            ->distinct()
            ->get();
        return response()->json($blokSensus);
    }

    // AJAX: Get NKS by Blok Sensus
    public function getNksByBlokSensus($id_bs)
    {
        // Perbaiki: Ambil NKS berdasarkan 'id_bs' DAN 'id_desa' (jika perlu)
        // Untuk saat ini, asumsikan 'id_bs' cukup unik atau konteksnya sudah benar
        $nks = WilayahTugas::where('id_bs', $id_bs)
            ->whereNotNull('id_nks')
            ->select('id_nks')
            ->distinct()
            ->get();
        return response()->json($nks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kec' => 'required|string|max:50',
            'id_desa' => 'required|string|max:50',
            'id_bs' => 'required|string|max:50',
            'id_nks' => 'nullable|string|max:50',
            'id_nurt' => 'required|string|max:50',
            'respon' => 'required|in:Respon,Non Respon',
        ]);

        Dsrt::create($validated);

        return redirect()->route('dsrt.index')
            ->with('success', 'Data Sampel Rumah Tangga berhasil ditambahkan');
    }

    public function edit($no)
    {
        $dsrt = Dsrt::where('no', $no)->firstOrFail();
        $kecamatan = Kecamatan::all();
        $desa = Desa::where('id_kec', $dsrt->id_kec)->get();
        $blokSensus = WilayahTugas::where('id_desa', $dsrt->id_desa)
            ->select('id_bs')
            ->distinct()
            ->get();
        $nks = WilayahTugas::where('id_bs', $dsrt->id_bs)
            ->whereNotNull('id_nks')
            ->select('id_nks')
            ->distinct()
            ->get();

        return view('dsrt.edit', compact('dsrt', 'kecamatan', 'desa', 'blokSensus', 'nks'));
    }

    public function update(Request $request, $no)
    {
        $dsrt = Dsrt::where('no', $no)->firstOrFail();

        $validated = $request->validate([
            'id_kec' => 'required|string|max:50',
            'id_desa' => 'required|string|max:50',
            'id_bs' => 'required|string|max:50',
            'id_nks' => 'nullable|string|max:50',
            'id_nurt' => 'required|string|max:50',
            'respon' => 'required|in:Respon,Non Respon',
        ]);

        $dsrt->update($validated);

        return redirect()->route('dsrt.index')
            ->with('success', 'Data Sampel Rumah Tangga berhasil diupdate');
    }

    public function destroy($no)
    {
        $dsrt = Dsrt::where('no', $no)->firstOrFail();
        $dsrt->delete();

        return redirect()->route('dsrt.index')
            ->with('success', 'Data Sampel Rumah Tangga berhasil dihapus');
    }
    
    public function downloadTemplate()
    {
        return Excel::download(new DsrtTemplateExport, 'template_dsrt.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);
        try {
            $importer = new DsrtImport();
            Excel::import($importer, $request->file('file'));
            
            $ok = $importer->getImported();
            $skip = $importer->getSkipped();
            $err = $importer->getErrors();

            if ($ok > 0 && empty($err)) {
                return back()->with('success', "✅ Import berhasil: {$ok} data ditambahkan/diupdate.");
            }
            
            if ($ok > 0 && !empty($err)) {
                 $msg = "⚠️ Import selesai dengan catatan:\n\n";
                 $msg .= "✅ Berhasil: {$ok} data\n";
                 $msg .= "❌ Gagal/Dilewati: {$skip} data\n\n";
                 $msg .= "Detail Error (maks 5):\n";
                 $msg .= implode("\n", array_slice($err, 0, 5));
                 return back()->with('warning', $msg);
            }

            if ($ok == 0 && !empty($err)) {
                 $msg = "❌ Import gagal! Tidak ada data yang berhasil diimport.\n\n";
                 $msg .= "Detail Error (maks 5):\n";
                 $msg .= implode("\n", array_slice($err, 0, 5));
                 return back()->with('error', $msg);
            }

             if ($ok == 0 && $skip > 0 && empty($err)) {
                return back()->with('warning', "⚠️ Import selesai. {$skip} data dilewati (kemungkinan duplikat). Tidak ada data baru yang ditambahkan.");
            }

            return back()->with('success', 'Import selesai! Tidak ada data baru yang ditambahkan.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
             }
             $message = "❌ Validasi Excel gagal!\n\n";
             $message .= implode("\n", array_slice($errorMessages, 0, 10));
             return back()->with('error', $message);
        } catch (\Exception $e) {
            Log::error('Import Exception: ' . $e->getMessage());
            return back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
    
    // Export dengan checkbox support
    public function export(Request $request)
    {
        $ids = $request->input('selected', []);
        $dataFormat = $request->input('data_format', 'formatted');
        $outputFormat = $request->input('output_format', 'excel');
        if (empty($ids)) {
            return back()->with('error', 'Pilih data yang akan di-export!');
        }
        return Excel::download(
            new DsrtExport($ids, $dataFormat),
            'sampel_rumah_tangga_' . now()->format('Ymd_His') . ($outputFormat === 'csv' ? '.csv' : '.xlsx'),
            $outputFormat === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
        );
    }
}