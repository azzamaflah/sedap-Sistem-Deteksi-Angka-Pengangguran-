<?php

namespace App\Exports;

use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class WilayahTugasTemplateExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        // ✅ Ambil contoh data dari database
        $kecamatan1 = Kecamatan::first();
        $kecamatan2 = Kecamatan::skip(1)->first();

        $desa1 = $kecamatan1 ? Desa::where('id_kec', $kecamatan1->id_kec)->first() : null;
        $desa2 = $kecamatan2 ? Desa::where('id_kec', $kecamatan2->id_kec)->first() : null;

        $user1 = User::first();

        // ✅ Contoh data dengan DUPLIKAT id_bs dan id_nks
        return collect([
            // Contoh 1: ID BS sama, ID NKS berbeda
            [
                'id_kec' => $kecamatan1->id_kec ?? '010',
                'id_desa' => $desa1->id_desa ?? '010001',
                'id_bs' => '0001',
                'id_nks' => '000101',
                'nama_pengawas' => $user1->name ?? 'admin',
            ],
            [
                'id_kec' => $kecamatan1->id_kec ?? '010',
                'id_desa' => $desa1->id_desa ?? '010001',
                'id_bs' => '0001', // ✅ ID BS SAMA dengan baris atas
                'id_nks' => '000102', // ID NKS berbeda
                'nama_pengawas' => $user1->name ?? 'admin',
            ],

            // Contoh 2: ID NKS sama, ID BS berbeda (1 NKS punya banyak data)
            [
                'id_kec' => $kecamatan1->id_kec ?? '010',
                'id_desa' => $desa1->id_desa ?? '010001',
                'id_bs' => '0002',
                'id_nks' => '000201', // ID NKS ini akan muncul di baris bawah juga
                'nama_pengawas' => $user1->name ?? 'admin',
            ],
            [
                'id_kec' => $kecamatan1->id_kec ?? '010',
                'id_desa' => $desa1->id_desa ?? '010001',
                'id_bs' => '0003',
                'id_nks' => '000201', // ✅ ID NKS SAMA dengan baris atas (1 NKS punya 2 data)
                'nama_pengawas' => $user1->name ?? 'admin',
            ],

            // Contoh 3: Kecamatan berbeda
            [
                'id_kec' => $kecamatan2->id_kec ?? '060',
                'id_desa' => $desa2->id_desa ?? '060003',
                'id_bs' => '0001', // ✅ Boleh pakai ID BS yang sama dengan data di kecamatan lain
                'id_nks' => '000101', // ✅ Boleh pakai ID NKS yang sama dengan data di kecamatan lain
                'nama_pengawas' => $user1->name ?? 'admin',
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
            'nama_pengawas',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // ✅ Style header (baris 1)
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // ✅ Border untuk semua data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:E{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // ✅ Center alignment untuk kolom angka
        $sheet->getStyle("A2:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ✅ Highlight baris dengan duplikat (untuk edukasi user)
        $sheet->getStyle('A3:E3')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFCC'], // Kuning muda
            ],
        ]);
        $sheet->getStyle('A5:E5')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFFCC'], // Kuning muda
            ],
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // id_kec
            'B' => 15,  // id_desa
            'C' => 12,  // id_bs
            'D' => 12,  // id_nks
            'E' => 25,  // nama_pengawas
        ];
    }
}
