<?php

namespace App\Http\Controllers;

use App\Models\WilayahTugas;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\User;
use Illuminate\Http\Request;
use App\Imports\WilayahTugasImport;
use App\Exports\WilayahTugasTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WilayahTugasExport;
use Illuminate\Support\Facades\DB; // <-- 1. TAMBAHKAN INI
use Illuminate\Support\Facades\Log; // <-- Tambahkan ini untuk error handling

class WilayahTugasController extends Controller
{
    /**
     * Tampilkan daftar wilayah tugas dengan filter.
     */
    public function index(Request $request)
    {
        $query = WilayahTugas::with(['kecamatan', 'desa', 'pengawas']);

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
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhereHas('kecamatan', function ($q) use ($search) {
                        $q->where('nama_kec', 'like', "%{$search}%");
                    })
                    ->orWhereHas('desa', function ($q) use ($search) {
                        $q->where('nama_desa', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pengawas', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
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
        // Menggunakan 'bloksensus' sesuai nama tabel di SQL
        $availableYears = DB::table('bloksensus') 
                            ->select(DB::raw('YEAR(created_at) as year'))
                            ->whereNotNull('created_at')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        // 4. Modifikasi paginasi untuk menyimpan parameter filter
        $data = $query->paginate(20)->appends($request->except('page'));

        // 5. Kirim data ke view
        return view('wilayahTugas.wilayahTugas', compact(
            'data',
            'availableYears',  // <-- Kirim tahun ke view
            'selectedYear',    // <-- Kirim filter aktif
            'selectedSemester' // <-- Kirim filter aktif
        ));
    }

    public function create()
    {
        $kecamatan = Kecamatan::all();
        $pengawas = User::all();
        return view('wilayahTugas.create', compact('kecamatan', 'pengawas'));
    }

    public function getDesaByKecamatan($id_kec)
    {
        $desa = Desa::where('id_kec', $id_kec)->get();
        return response()->json($desa);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kec' => 'required|string|max:50',
            'id_desa' => 'required|string|max:50',
            'id_bs' => 'required|string|max:50',
            'id_nks' => 'nullable|string|max:50',
            'id_user' => 'nullable|exists:users,id',
        ]);

        if ($request->filled('id_user')) {
            $user = User::find($request->id_user);
            if ($user) {
                $validated['nama'] = $user->name; // Ambil nama dari tabel users
            }
        }

        WilayahTugas::create($validated);

        return redirect()->route('wilayahTugas.index')
            ->with('success', 'Data wilayah tugas berhasil ditambahkan');
    }

    public function edit($no)
    {
        $wilayahTugas = WilayahTugas::where('no', $no)->firstOrFail();
        $kecamatan = Kecamatan::all();
        $desa = Desa::where('id_kec', $wilayahTugas->id_kec)->get();
        $pengawas = User::all();
        return view('wilayahTugas.edit', compact('wilayahTugas', 'kecamatan', 'desa', 'pengawas'));
    }

    public function update(Request $request, $no)
    {
        $wilayahTugas = WilayahTugas::where('no', $no)->firstOrFail();

        $validated = $request->validate([
            'id_kec' => 'required|string|max:50',
            'id_desa' => 'required|string|max:50',
            'id_bs' => 'required|string|max:50',
            'id_nks' => 'nullable|string|max:50',
            'id_user' => 'nullable|exists:users,id',
        ]);

        if ($request->filled('id_user')) {
            $user = User::find($request->id_user);
            if ($user) {
                $validated['nama'] = $user->name; // Ambil nama dari tabel users
            }
        } else {
             // Jika id_user dikosongkan, hapus juga nama pengawas
            $validated['nama'] = null;
            $validated['id_user'] = null;
        }
        
        $wilayahTugas->update($validated);
        return redirect()->route('wilayahTugas.index')
            ->with('success', 'Data wilayah tugas berhasil diupdate');
    }

    public function destroy($no)
    {
        $wilayahTugas = WilayahTugas::where('no', $no)->firstOrFail();
        $wilayahTugas->delete();

        return redirect()->route('wilayahTugas.index')
            ->with('success', 'Data wilayah tugas berhasil dihapus');
    }

    // ===== IMPORT EXCEL =====

    public function downloadTemplate()
    {
        return Excel::download(new WilayahTugasTemplateExport, 'template_wilayah_tugas.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new WilayahTugasImport();
            Excel::import($import, $request->file('file'));

            $imported = $import->getImported();
            $skipped = $import->getSkipped();
            $errors = $import->getErrors();

            // ✅ Tampilkan hasil import dengan detail
            if ($imported > 0 && empty($errors)) {
                return redirect()->route('wilayahTugas.index')
                    ->with('success', "✅ Berhasil import {$imported} data wilayah tugas!");
            }

            if ($imported > 0 && !empty($errors)) {
                $message = "⚠️ Import selesai dengan catatan:\n\n";
                $message .= "✅ Berhasil: {$imported} data\n";
                $message .= "❌ Gagal/Dilewati: {$skipped} data\n\n";
                $message .= "Detail Error:\n";
                $message .= implode("\n", array_slice($errors, 0, 10));

                if (count($errors) > 10) {
                    $message .= "\n\n... dan " . (count($errors) - 10) . " error lainnya";
                }

                return redirect()->route('wilayahTugas.index')
                    ->with('warning', $message);
            }

            if ($imported == 0 && !empty($errors)) {
                $message = "❌ Import gagal! Tidak ada data yang berhasil diimport.\n\n";
                $message .= "Detail Error:\n";
                $message .= implode("\n", array_slice($errors, 0, 10));

                if (count($errors) > 10) {
                    $message .= "\n\n... dan " . (count($errors) - 10) . " error lainnya";
                }

                return redirect()->route('wilayahTugas.index')
                    ->with('error', $message);
            }
            
            if ($imported == 0 && $skipped > 0 && empty($errors)) {
                return redirect()->route('wilayahTugas.index')
                       ->with('warning', "⚠️ Import selesai. {$skipped} data dilewati (kemungkinan duplikat atau tidak valid). Tidak ada data baru yang ditambahkan.");
            }

            return redirect()->route('wilayahTugas.index')
                ->with('success', 'Import selesai! Tidak ada data baru yang ditambahkan.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            $message = "❌ Validasi Excel gagal!\n\n";
            $message .= implode("\n", array_slice($errorMessages, 0, 10));

            if (count($errorMessages) > 10) {
                $message .= "\n\n... dan " . (count($errorMessages) - 10) . " error lainnya";
            }

            return redirect()->route('wilayahTugas.index')
                ->with('error', $message);
        } catch (\Exception $e) {
            Log::error('Import Exception: ' . $e->getMessage());

            return redirect()->route('wilayahTugas.index')
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $ids = $request->input('selected', []);
        $dataFormat = $request->input('data_format', 'formatted');
        $outputFormat = $request->input('output_format', 'excel');
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih untuk di-export!');
        }
        $export = new WilayahTugasExport($ids, $dataFormat);
        $filename = 'wilayah_tugas_terpilih_' . now()->format('Ymd_His');
        if ($outputFormat === 'csv') {
            return Excel::download($export, $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
        }
        return Excel::download($export, $filename . '.xlsx');
    }
}