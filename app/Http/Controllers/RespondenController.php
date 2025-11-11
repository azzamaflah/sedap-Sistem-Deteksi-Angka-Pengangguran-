<?php

namespace App\Http\Controllers;

use App\Models\Responden;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\WilayahTugas;
use App\Models\Dsrt;
use App\Models\ConfigQuest;
use Illuminate\Http\Request;
use App\Exports\RespondenTemplateExport;
use App\Imports\RespondenImport;
use App\Exports\RespondenExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB; // <-- 1. TAMBAHKAN INI
use Illuminate\Support\Facades\Log; // <-- 2. TAMBAHKAN INI

class RespondenController extends Controller
{
    public function index(Request $request)
    {
        $query = Responden::with(['kecamatan', 'desa', 'wilayahTugas']);

        // Ambil input filter
        $search = $request->input('search');
        $selectedYear = $request->input('year');
        $selectedSemester = $request->input('semester');

        // Filter: Pencarian (DIPERKUAT)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_sample', 'like', "%{$search}%")
                    ->orWhere('id_kec', 'like', "%{$search}%")
                    ->orWhere('id_desa', 'like', "%{$search}%")
                    ->orWhere('id_nks', 'like', "%{$search}%")
                    ->orWhere('id_bs', 'like', "%{$search}%")
                    ->orWhere('id_nurt', 'like', "%{$search}%")
                    ->orWhereHas('kecamatan', function ($q) use ($search) {
                        $q->where('nama_kec', 'like', "%{$search}%");
                    })
                    ->orWhereHas('desa', function ($q) use ($search) {
                        $q->where('nama_desa', 'like', "%{$search}%");
                    });
            });
        }

        // ===== 3. LOGIKA FILTER SEMESTER BARU =====
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

        // 4. Ambil data tahun unik untuk dropdown filter
        $availableYears = DB::table('responden') // <-- Mengambil dari tabel responden
                            ->select(DB::raw('YEAR(created_at) as year'))
                            ->whereNotNull('created_at')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        // 5. Modifikasi paginasi untuk menyimpan parameter filter
        $data = $query->paginate(20)->appends($request->except('page'));

        // 6. Kirim data ke view
        return view('responden.responden', compact(
            'data',
            'availableYears',
            'selectedYear',
            'selectedSemester'
        ));
    }


    public function create()
    {
        $kecamatan = Kecamatan::all();
        $quests = ConfigQuest::where('is_active', true)->orderBy('order')->get();
        return view('responden.create', compact('kecamatan', 'quests'));
    }

    // AJAX methods
    public function getDesaByKecamatan($id_kec)
    {
        $desa = Desa::where('id_kec', $id_kec)->get();
        return response()->json($desa);
    }

    public function getBlokSensusByDesa($id_desa)
    {
        $blokSensus = WilayahTugas::where('id_desa', $id_desa)
            ->select('id_bs')
            ->distinct()
            ->get();
        return response()->json($blokSensus);
    }

    public function getNksByBlokSensus($id_bs)
    {
        $nks = WilayahTugas::where('id_bs', $id_bs)
            ->whereNotNull('id_nks')
            ->select('id_nks')
            ->distinct()
            ->get();
        return response()->json($nks);
    }

    public function getNurtByNks($id_nks)
    {
        $nurt = Dsrt::where('id_nks', $id_nks)
            ->select('id_nurt')
            ->distinct()
            ->get();
        return response()->json($nurt);
    }

    public function store(Request $request)
    {
        $rules = [
            'id_kec' => 'required',
            'id_desa' => 'required',
            'id_bs' => 'required',
            'id_nks' => 'required',
            'id_nurt' => 'required',
            'nama_sample' => 'required|string|max:255',
        ];

        $quests = ConfigQuest::where('is_active', true)->get();
        foreach ($quests as $quest) {
            if (in_array($quest->type, ['radio', 'dropdown'])) {
                // Asumsi: $quest->options adalah array (via $casts di Model)
                $rules[$quest->key] = 'nullable|in:' . implode(',', $quest->options ?? []);
            } else {
                $rules[$quest->key] = 'nullable|string|max:255';
            }
        }

        $validated = $request->validate($rules);

        $responden = new Responden($validated);
        $responden->hitungStatus();
        $responden->save();

        return redirect()->route('responden.index')
            ->with('success', 'Data responden berhasil ditambahkan');
    }

    public function edit($no)
    {
        $responden = Responden::where('no', $no)->firstOrFail();
        $kecamatan = Kecamatan::all();
        $desa = Desa::where('id_kec', $responden->id_kec)->get();
        $blokSensus = WilayahTugas::where('id_desa', $responden->id_desa)
            ->select('id_bs')
            ->distinct()
            ->get();
        $nks = WilayahTugas::where('id_bs', $responden->id_bs)
            ->whereNotNull('id_nks')
            ->select('id_nks')
            ->distinct()
            ->get();
        $nurt = Dsrt::where('id_nks', $responden->id_nks)
            ->select('id_nurt')
            ->distinct()
            ->get();

        $quests = ConfigQuest::where('is_active', true)->orderBy('order')->get();

        return view('responden.edit', compact('responden', 'kecamatan', 'desa', 'blokSensus', 'nks', 'nurt', 'quests'));
    }

    public function update(Request $request, $no)
    {
        $responden = Responden::where('no', $no)->firstOrFail();

        $rules = [
            'id_kec' => 'required',
            'id_desa' => 'required',
            'id_bs' => 'required',
            'id_nks' => 'required',
            'id_nurt' => 'required',
            'nama_sample' => 'required|string|max:255',
        ];

        $quests = ConfigQuest::where('is_active', true)->get();
        foreach ($quests as $quest) {
            if (in_array($quest->type, ['radio', 'dropdown'])) {
                // Asumsi: $quest->options adalah array (via $casts di Model)
                 $rules[$quest->key] = 'nullable|in:' . implode(',', $quest->options ?? []);
            } else {
                $rules[$quest->key] = 'nullable|string|max:255';
            }
        }

        $validated = $request->validate($rules);

        $responden->fill($validated);
        $responden->hitungStatus();
        $responden->save();

        return redirect()->route('responden.index')
            ->with('success', 'Data responden berhasil diupdate');
    }

    public function destroy($no)
    {
        $responden = Responden::where('no', $no)->firstOrFail();
        $responden->delete();

        return redirect()->route('responden.index')
            ->with('success', 'Data responden berhasil dihapus');
    }

    // Methods Import/Export
    public function downloadTemplate()
    {
        return Excel::download(new RespondenTemplateExport, 'template_responden.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $importer = new RespondenImport();
            Excel::import($importer, $request->file('file'));

            $ok = $importer->getImported();
            $skip = $importer->getSkipped();
            $err = $importer->getErrors();

            if ($ok > 0 && empty($err)) {
                return back()->with('success', "✅ Import berhasil: $ok data ditambahkan/diupdate.");
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

    public function export(Request $request)
    {
        $ids = $request->input('selected', []);
        $dataFormat = $request->input('data_format', 'formatted');
        $outputFormat = $request->input('output_format', 'excel');

        if (empty($ids)) {
            return back()->with('error', 'Pilih data yang akan di-export!');
        }

        return Excel::download(
            new RespondenExport($ids, $dataFormat),
            'responden_' . now()->format('Ymd_His') . ($outputFormat === 'csv' ? '.csv' : '.xlsx'),
            $outputFormat === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
        );
    }
}