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


class WilayahTugasController extends Controller
{
    public function index(Request $request)
    {
        $query = WilayahTugas::with(['kecamatan', 'desa', 'pengawas']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_kec', 'like', "%{$search}%")
                    ->orWhere('id_desa', 'like', "%{$search}%")
                    ->orWhere('id_bs', 'like', "%{$search}%")
                    ->orWhere('id_nks', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(20);
        return view('wilayahTugas.wilayahTugas', compact('data'));
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
            'id_user' => 'required|exists:users,id',
        ]);

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
            'id_nks' => 'required|string|max:50',
            'id_user' => 'nullable|exists:user,id_user',
        ]);

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

            return redirect()->route('wilayahTugas.index')
                ->with('success', 'Import selesai!');
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
