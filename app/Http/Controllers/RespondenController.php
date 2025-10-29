<?php

namespace App\Http\Controllers;

use App\Models\Responden;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\WilayahTugas;
use App\Models\Dsrt;
use Illuminate\Http\Request;
use App\Exports\RespondenTemplateExport;
use App\Imports\RespondenImport;
use App\Exports\RespondenExport;
use Maatwebsite\Excel\Facades\Excel;

class RespondenController extends Controller
{
    public function index(Request $request)
    {
        $query = Responden::with(['kecamatan', 'desa', 'wilayahTugas']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_sample', 'like', "%{$search}%")
                    ->orWhere('id_kec', 'like', "%{$search}%")
                    ->orWhere('id_desa', 'like', "%{$search}%")
                    ->orWhere('id_nks', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate(20);
        return view('responden.responden', compact('data'));
    }

    public function create()
    {
        $kecamatan = Kecamatan::all();
        return view('responden.create', compact('kecamatan'));
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

    // AJAX: Get Nurt by NKS
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
        $validated = $request->validate([
            'id_kec' => 'required',
            'id_desa' => 'required',
            'id_bs' => 'required',
            'id_nks' => 'required',
            'id_nurt' => 'required',
            'nama_sample' => 'required|string|max:255',

            // Quest 1-4
            'r7_1' => 'required|in:Ya,Tidak',
            'r7_2' => 'nullable|in:Ya,Tidak',
            'r7_3' => 'nullable|in:Ya,Tidak',
            'r8_1' => 'nullable|in:Ya,Tidak',

            // Quest 5-6
            'r9_1' => 'nullable|string|max:255',
            'r9_3' => 'nullable|string|max:255',

            // Quest 7-9
            'r20_1' => 'nullable|in:Ya,Tidak',
            'r20_2' => 'nullable|in:Ya,Tidak',
            'r20_4' => 'nullable|string',
        ]);

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

        return view('responden.edit', compact('responden', 'kecamatan', 'desa', 'blokSensus', 'nks', 'nurt'));
    }

    public function update(Request $request, $no)
    {
        $responden = Responden::where('no', $no)->firstOrFail();

        $validated = $request->validate([
            'id_kec' => 'required',
            'id_desa' => 'required',
            'id_bs' => 'required',
            'id_nks' => 'required',
            'id_nurt' => 'required',
            'nama_sample' => 'required|string|max:255',

            // Quest 1-4: Ubah jadi nullable
            'r7_1' => 'required|in:Ya,Tidak',
            'r7_2' => 'nullable|in:Ya,Tidak',
            'r7_3' => 'nullable|in:Ya,Tidak',
            'r8_1' => 'nullable|in:Ya,Tidak',

            // Quest 5-6
            'r9_1' => 'nullable|string|max:255',
            'r9_3' => 'nullable|string|max:255',

            // Quest 7-9
            'r20_1' => 'nullable|in:Ya,Tidak',
            'r20_2' => 'nullable|in:Ya,Tidak',
            'r20_4' => 'nullable|string',
        ]);

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
