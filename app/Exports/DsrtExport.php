<?php

namespace App\Exports;

use App\Models\Dsrt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DsrtExport implements FromCollection, WithHeadings
{
    protected $ids, $dataFormat;
    public function __construct($ids, $dataFormat = 'formatted')
    {
        $this->ids = $ids;
        $this->dataFormat = $dataFormat;
    }
    public function collection()
    {
        $dsrtQuery = Dsrt::whereIn('no', $this->ids)->with(['kecamatan', 'desa', 'wilayahTugas']);
        return $dsrtQuery->get()->map(function ($item) {
            if ($this->dataFormat === 'formatted') {
                return [
                    'Kecamatan'    => $item->kecamatan->nama_kec ?? $item->id_kec,
                    'Desa'         => $item->desa->nama_desa ?? $item->id_desa,
                    'ID BS'        => $item->id_bs,
                    'ID NKS'       => $item->id_nks,
                    'No. Urut RT'  => $item->id_nurt,
                    'Respon'       => $item->respon,
                ];
            } else {
                return [
                    'id_kec'   => $item->id_kec,
                    'id_desa'  => $item->id_desa,
                    'id_bs'    => $item->id_bs,
                    'id_nks'   => $item->id_nks,
                    'id_nurt'  => $item->id_nurt,
                    'respon'   => $item->respon,
                ];
            }
        });
    }
    public function headings(): array
    {
        return $this->dataFormat === 'formatted'
            ? ['Kecamatan', 'Desa', 'ID BS', 'ID NKS', 'No. Urut RT', 'Respon']
            : ['id_kec', 'id_desa', 'id_bs', 'id_nks', 'id_nurt', 'respon'];
    }
}
