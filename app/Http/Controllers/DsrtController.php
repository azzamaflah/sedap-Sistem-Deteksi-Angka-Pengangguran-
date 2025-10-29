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

class DsrtController extends Controller
{
    public function index(Request $request)
    {
        $query = Dsrt::with(['kecamatan', 'desa', 'wilayahTugas']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id_kec', 'like', "%{$search}%")
                    ->orWhere('id_desa', 'like', "%{$search}%")
                    ->orWhere('id_bs', 'like', "%{$search}%")
                    ->orWhere('id_nks', 'like', "%{$search}%")
                    ->orWhere('id_nurt', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(20);
        return view('dsrt.dsrt', compact('data'));
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
            'respon' => 'required|in:Respon,Non Respon', // Ganti jadi 'respon'
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
            'respon' => 'required|in:Respon,Non Respon', // Ganti jadi 'respon'
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
                return back()->with('success', "Import berhasil: $ok data.");
            }
            if (!empty($err)) {
                $msg = "Import {$ok} berhasil, {$skip} gagal.\n" . implode("\n", array_slice($err, 0, 5));
                return back()->with('warning', $msg);
            }
            return back()->with('error', "Import gagal!\n" . implode("\n", $err));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
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
