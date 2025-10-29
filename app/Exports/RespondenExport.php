<?php

namespace App\Exports;

use App\Models\Responden;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RespondenExport implements FromCollection, WithHeadings, WithStyles
{
    protected $ids, $dataFormat;
    public function __construct($ids, $dataFormat = 'formatted')
    {
        $this->ids = $ids;
        $this->dataFormat = $dataFormat;
    }
    public function collection()
    {
        $query = Responden::whereIn('no', $this->ids)->with(['kecamatan', 'desa']);
        $no = 1;

        return $query->get()->map(function ($item) use (&$no) {
            if ($this->dataFormat === 'formatted') {
                return [
                    'No'            => $no++,
                    'Kecamatan'     => $item->kecamatan->nama_kec ?? $item->id_kec,
                    'Desa'          => $item->desa->nama_desa ?? $item->id_desa,
                    'ID BS'         => $item->id_bs,
                    'ID NKS'        => $item->id_nks,
                    'No. Urut RT'   => $item->id_nurt,
                    'Nama Responden' => $item->nama_sample,
                    'R7_1'          => $item->r7_1,
                    'R7_2'          => $item->r7_2,
                    'R7_3'          => $item->r7_3,
                    'R8_1'          => $item->r8_1,
                    'R9_1'          => $item->r9_1,
                    'R9_3'          => $item->r9_3,
                    'R20_1'         => $item->r20_1,
                    'R20_2'         => $item->r20_2,
                    'R20_4'         => $item->r20_4,
                ];
            } else {
                return [
                    'No'          => $no++,
                    'id_kec'      => $item->id_kec,
                    'id_desa'     => $item->id_desa,
                    'id_bs'       => $item->id_bs,
                    'id_nks'      => $item->id_nks,
                    'id_nurt'     => $item->id_nurt,
                    'nama_sample' => $item->nama_sample,
                    'r7_1'        => $item->r7_1,
                    'r7_2'        => $item->r7_2,
                    'r7_3'        => $item->r7_3,
                    'r8_1'        => $item->r8_1,
                    'r9_1'        => $item->r9_1,
                    'r9_3'        => $item->r9_3,
                    'r20_1'       => $item->r20_1,
                    'r20_2'       => $item->r20_2,
                    'r20_4'       => $item->r20_4,
                ];
            }
        });
    }
    public function headings(): array
    {
        return $this->dataFormat === 'formatted'
            ? ['No', 'Kecamatan', 'Desa', 'ID BS', 'ID NKS', 'No. Urut RT', 'Nama Responden', 'R7_1', 'R7_2', 'R7_3', 'R8_1', 'R9_1', 'R9_3', 'R20_1', 'R20_2', 'R20_4']
            : ['No', 'id_kec', 'id_desa', 'id_bs', 'id_nks', 'id_nurt', 'nama_sample', 'r7_1', 'r7_2', 'r7_3', 'r8_1', 'r9_1', 'r9_3', 'r20_1', 'r20_2', 'r20_4'];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            ],
        ];
    }
}
