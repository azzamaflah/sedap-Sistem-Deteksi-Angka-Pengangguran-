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

class DsrtTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        $kec = Kecamatan::first();
        $desa = $kec ? Desa::where('id_kec', $kec->id_kec)->first() : null;
        $bs = $desa ? WilayahTugas::where('id_desa', $desa->id_desa)->first() : null;

        return collect([
            [
                'id_kec'  => $kec ? $kec->id_kec : '010',
                'id_desa' => $desa ? $desa->id_desa : '010001',
                'id_bs'   => $bs ? $bs->id_bs : '0001',
                'id_nks'  => $bs && $bs->id_nks ? $bs->id_nks : '000101',
                'id_nurt' => 1,
                'respon'  => 'Respon',
            ],
            [
                'id_kec'  => $kec ? $kec->id_kec : '010',
                'id_desa' => $desa ? $desa->id_desa : '010001',
                'id_bs'   => $bs ? $bs->id_bs : '0001',
                'id_nks'  => $bs && $bs->id_nks ? $bs->id_nks : '000101',
                'id_nurt' => 2,
                'respon'  => 'Non Respon',
            ],
        ]);
    }

    public function headings(): array
    {
        return ['id_kec', 'id_desa', 'id_bs', 'id_nks', 'id_nurt', 'respon'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
        ]);
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 18,
            'C' => 12,
            'D' => 14,
            'E' => 12,
            'F' => 16,
        ];
    }
}
