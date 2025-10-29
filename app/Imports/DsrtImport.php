<?php

namespace App\Imports;

use App\Models\Dsrt;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\WilayahTugas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;

class DsrtImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    private $errors = [];
    private $imported = 0;
    private $skipped = 0;

    public function model(array $row)
    {
        // Padding kode
        $id_kec  = str_pad($row['id_kec'], 3, '0', STR_PAD_LEFT);
        $id_desa = str_pad($row['id_desa'], 6, '0', STR_PAD_LEFT);
        $id_bs   = str_pad($row['id_bs'], 4, '0', STR_PAD_LEFT);
        $id_nks  = str_pad($row['id_nks'], 6, '0', STR_PAD_LEFT);

        // Validasi Kecamatan
        $kecamatan = Kecamatan::where('id_kec', $id_kec)->first();
        if (!$kecamatan) {
            $this->errors[] = "id_kec '{$id_kec}' tidak ditemukan di database.";
            $this->skipped++;
            return null;
        }
        // Validasi Desa dan parent kecamatan
        $desa = Desa::where('id_desa', $id_desa)->first();
        if (!$desa) {
            $this->errors[] = "id_desa '{$id_desa}' tidak ditemukan di database.";
            $this->skipped++;
            return null;
        }
        if ($desa->id_kec !== $id_kec) {
            $this->errors[] = "id_desa '{$id_desa}' tidak sesuai dengan id_kec '{$id_kec}'.";
            $this->skipped++;
            return null;
        }
        // Validasi Blok Sensus & NKS
        $bloksensus = WilayahTugas::where('id_bs', $id_bs)
            ->where('id_desa', $id_desa)
            ->where('id_kec', $id_kec)
            ->first();
        if (!$bloksensus) {
            $this->errors[] = "id_bs '{$id_bs}' tidak ditemukan untuk desa & kec ini.";
            $this->skipped++;
            return null;
        }
        $nksValid = WilayahTugas::where('id_nks', $id_nks)
            ->where('id_bs', $id_bs)
            ->where('id_desa', $id_desa)
            ->where('id_kec', $id_kec)
            ->exists();
        if (!$nksValid) {
            $this->errors[] = "id_nks '{$id_nks}' tidak ditemukan untuk blok sensus ini.";
            $this->skipped++;
            return null;
        }
        // Validasi id_nurt range
        $nurtInt = intval($row['id_nurt']);
        if ($nurtInt < 1 || $nurtInt > 10) {
            $this->errors[] = "id_nurt '{$row['id_nurt']}' harus 1-10.";
            $this->skipped++;
            return null;
        }
        // Validasi respon
        $respon = ucfirst(strtolower($row['respon']));
        if (!in_array($respon, ['Respon', 'Non respon', 'Non Respon'])) {
            $this->errors[] = "Respon '{$row['respon']}' hanya boleh 'Respon' atau 'Non Respon'.";
            $this->skipped++;
            return null;
        }
        // Normalisasi "Non Respon"
        if ($respon === 'Non respon') $respon = 'Non Respon';

        $this->imported++;
        return new Dsrt([
            'id_kec'  => $id_kec,
            'id_desa' => $id_desa,
            'id_bs'   => $id_bs,
            'id_nks'  => $id_nks,
            'id_nurt' => $nurtInt,
            'respon'  => $respon,
        ]);
    }

    public function rules(): array
    {
        return [
            'id_kec'  => 'required|numeric|digits_between:1,3',
            'id_desa' => 'required|numeric|digits_between:1,6',
            'id_bs'   => 'required|numeric|digits_between:1,4',
            'id_nks'  => 'required|numeric|digits_between:1,6',
            'id_nurt' => 'required|numeric',
            'respon'  => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'id_kec.required'  => 'id_kec wajib diisi',
            'id_desa.required' => 'id_desa wajib diisi',
            'id_bs.required'   => 'id_bs wajib diisi',
            'id_nks.required'  => 'id_nks wajib diisi',
            'id_nurt.required' => 'id_nurt wajib diisi (1-10)',
            'respon.required'  => 'respon wajib diisi',
        ];
    }

    public function onError(\Throwable $e)
    {
        $this->errors[] = $e->getMessage();
        $this->skipped++;
    }
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $err = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            $this->errors[] = $err;
            $this->skipped++;
        }
    }
    public function getErrors()
    {
        return $this->errors;
    }
    public function getImported()
    {
        return $this->imported;
    }
    public function getSkipped()
    {
        return $this->skipped;
    }
}
