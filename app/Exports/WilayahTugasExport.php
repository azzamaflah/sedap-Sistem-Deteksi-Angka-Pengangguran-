<?php

// app/Exports/WilayahTugasExport.php
namespace App\Exports;

use App\Models\WilayahTugas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WilayahTugasExport implements FromCollection, WithHeadings
{
    protected $ids, $dataFormat;
    public function __construct($ids, $dataFormat = 'formatted')
    {
        $this->ids = $ids;
        $this->dataFormat = $dataFormat;
    }
    public function collection()
    {
        $query = WilayahTugas::with(['kecamatan', 'desa', 'pengawas'])
            ->whereIn('no', $this->ids);
        return $query->get()->map(function ($item) {
            if ($this->dataFormat === 'formatted') {
                return [
                    'Kecamatan'   => $item->kecamatan->nama_kec ?? $item->id_kec,
                    'Desa'        => $item->desa->nama_desa ?? $item->id_desa,
                    'ID BS'       => $item->id_bs,
                    'ID NKS'      => $item->id_nks,
                    'Pengawas'    => $item->pengawas->name ?? '',
                ];
            } else {
                return [
                    'id_kec'      => $item->id_kec,
                    'id_desa'     => $item->id_desa,
                    'id_bs'       => $item->id_bs,
                    'id_nks'      => $item->id_nks,
                    'id_user'     => $item->id_user,
                ];
            }
        });
    }
    public function headings(): array
    {
        if ($this->dataFormat === 'formatted') {
            return ['Kecamatan', 'Desa', 'ID BS', 'ID NKS', 'Pengawas'];
        } else {
            return ['id_kec', 'id_desa', 'id_bs', 'id_nks', 'id_user'];
        }
    }
}
