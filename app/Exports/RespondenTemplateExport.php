<?php

namespace App\Exports;

use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\WilayahTugas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RespondenTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        $kec = Kecamatan::first();
        $desa = $kec ? Desa::where('id_kec', $kec->id_kec)->first() : null;
        $bs = $desa ? WilayahTugas::where('id_desa', $desa->id_desa)->first() : null;

        return collect([
            [
                'id_kec'      => $kec ? $kec->id_kec : '010',
                'id_desa'     => $desa ? $desa->id_desa : '010001',
                'id_bs'       => $bs ? $bs->id_bs : '0001',
                'id_nks'      => $bs && $bs->id_nks ? $bs->id_nks : '000101',
                'id_nurt'     => 1,
                'nama_sample' => 'Budi Santoso',
                'r7_1'        => '',
                'r7_2'        => '',
                'r7_3'        => '',
                'r8_1'        => '',
                'r9_1'        => '',
                'r9_3'        => '',
                'r20_1'       => '',
                'r20_2'       => '',
                'r20_4'       => '',
            ],
            [
                'id_kec'      => $kec ? $kec->id_kec : '010',
                'id_desa'     => $desa ? $desa->id_desa : '010001',
                'id_bs'       => $bs ? $bs->id_bs : '0001',
                'id_nks'      => $bs && $bs->id_nks ? $bs->id_nks : '000101',
                'id_nurt'     => 2,
                'nama_sample' => 'Siti Nurhaliza',
                'r7_1'        => '1',
                'r7_2'        => '2',
                'r7_3'        => '',
                'r8_1'        => '',
                'r9_1'        => '',
                'r9_3'        => '',
                'r20_1'       => '',
                'r20_2'       => '',
                'r20_4'       => '',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            'id_kec',
            'id_desa',
            'id_bs',
            'id_nks',
            'id_nurt',
            'nama_sample',
            'r7_1',
            'r7_2',
            'r7_3',
            'r8_1',
            'r9_1',
            'r9_3',
            'r20_1',
            'r20_2',
            'r20_4'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
        ]);
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 12,
            'C' => 10,
            'D' => 12,
            'E' => 10,
            'F' => 20,
            'G' => 8,
            'H' => 8,
            'I' => 8,
            'J' => 8,
            'K' => 8,
            'L' => 8,
            'M' => 8,
            'N' => 8,
            'O' => 8,
        ];
    }
}
