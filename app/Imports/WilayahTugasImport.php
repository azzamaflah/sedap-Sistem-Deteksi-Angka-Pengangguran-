<?php

namespace App\Imports;

use App\Models\WilayahTugas;
use App\Models\Kecamatan;
use App\Models\Desa;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class WilayahTugasImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsEmptyRows,
    SkipsOnError,
    SkipsOnFailure
{
    private $errors = [];
    private $imported = 0;
    private $skipped = 0;

    public function model(array $row)
    {
        // ✅ STEP 1: Padding kode
        $id_kec = str_pad($row['id_kec'], 3, '0', STR_PAD_LEFT);
        $id_desa = str_pad($row['id_desa'], 6, '0', STR_PAD_LEFT);
        $id_bs = str_pad($row['id_bs'], 4, '0', STR_PAD_LEFT);
        $id_nks = !empty($row['id_nks']) ? str_pad($row['id_nks'], 6, '0', STR_PAD_LEFT) : null;

        // ✅ STEP 2: Validasi id_kec ada di database
        $kecamatan = Kecamatan::where('id_kec', $id_kec)->first();
        if (!$kecamatan) {
            $error = "Baris {$id_bs}: Kode kecamatan '{$row['id_kec']}' tidak ditemukan di database";
            $this->errors[] = $error;
            $this->skipped++;
            Log::warning("Import Error: " . $error);
            return null; // Skip row ini
        }

        // ✅ STEP 3: Validasi id_desa ada di database
        $desa = Desa::where('id_desa', $id_desa)->first();
        if (!$desa) {
            $error = "Baris {$id_bs}: Kode desa '{$row['id_desa']}' tidak ditemukan di database";
            $this->errors[] = $error;
            $this->skipped++;
            Log::warning("Import Error: " . $error);
            return null; // Skip row ini
        }

        // ✅ STEP 4: Validasi id_desa sesuai dengan id_kec (relasi parent-child)
        if ($desa->id_kec !== $id_kec) {
            $error = "Baris {$id_bs}: Desa '{$desa->nama_desa}' (kode: {$id_desa}) TIDAK TERMASUK dalam kecamatan '{$kecamatan->nama_kec}' (kode: {$id_kec}). " .
                "Desa ini seharusnya ada di kecamatan dengan kode '{$desa->id_kec}'.";
            $this->errors[] = $error;
            $this->skipped++;
            Log::warning("Import Error: " . $error);
            return null; // Skip row ini
        }

        // ✅ STEP 5: Cari pengawas di tabel users berdasarkan name
        $id_user = null;
        if (!empty($row['nama_pengawas'])) {
            $nama_pengawas = trim($row['nama_pengawas']);
            $user = User::where('name', 'LIKE', '%' . $nama_pengawas . '%')->first();

            if ($user) {
                $id_user = $user->id;
            } else {
                // Warning tapi tetap import (id_user = null)
                $warning = "Baris {$id_bs}: Pengawas '{$nama_pengawas}' tidak ditemukan di database users. Data tetap diimport tanpa pengawas.";
                $this->errors[] = $warning;
                Log::warning("Import Warning: " . $warning);
            }
        }

        // ✅ STEP 6: DUPLIKAT ID_BS & ID_NKS DIPERBOLEHKAN
        // ⚠️ CATATAN: id_bs dan id_nks boleh duplikat karena:
        //    - 1 id_nks bisa punya banyak data (sampai 10 data)
        //    - Bisa ada beberapa data dengan id_bs yang sama
        // ❌ TIDAK ADA VALIDASI DUPLIKAT DI SINI

        // ✅ STEP 7: Import berhasil
        $this->imported++;
        Log::info("Import Success: BS={$id_bs}, NKS={$id_nks}, Desa={$desa->nama_desa}, Kec={$kecamatan->nama_kec}, User={$id_user}");

        return new WilayahTugas([
            'id_kec'  => $id_kec,
            'id_desa' => $id_desa,
            'id_bs'   => $id_bs,
            'id_nks'  => $id_nks,
            'id_user' => $id_user,
        ]);
    }

    /**
     * ✅ VALIDASI TINGKAT EXCEL (sebelum masuk ke model)
     */
    public function rules(): array
    {
        return [
            'id_kec' => [
                'required',
                'numeric',
                'min:1',
                'max:999',
            ],
            'id_desa' => [
                'required',
                'numeric',
                'min:1',
                'max:999999',
            ],
            'id_bs' => [
                'required',
                'numeric',
                'min:1',
                'max:9999',
                // ✅ TIDAK ADA VALIDASI UNIQUE - BOLEH DUPLIKAT
            ],
            'id_nks' => [
                'nullable',
                'numeric',
                'max:999999',
                // ✅ TIDAK ADA VALIDASI UNIQUE - BOLEH DUPLIKAT
            ],
            'nama_pengawas' => [
                'nullable',
                'string',
                'max:100',
            ],
        ];
    }

    /**
     * ✅ CUSTOM VALIDATION MESSAGES
     */
    public function customValidationMessages()
    {
        return [
            'id_kec.required' => 'Kolom id_kec harus diisi',
            'id_kec.numeric' => 'Kolom id_kec harus berupa angka (contoh: 10, 060, 070)',
            'id_kec.min' => 'Kode kecamatan minimal 1',
            'id_kec.max' => 'Kode kecamatan maksimal 3 digit',

            'id_desa.required' => 'Kolom id_desa harus diisi',
            'id_desa.numeric' => 'Kolom id_desa harus berupa angka (contoh: 10001, 060003)',
            'id_desa.min' => 'Kode desa minimal 1',
            'id_desa.max' => 'Kode desa maksimal 6 digit',

            'id_bs.required' => 'Kolom id_bs harus diisi',
            'id_bs.numeric' => 'Kolom id_bs harus berupa angka (contoh: 1, 0001)',
            'id_bs.min' => 'Kode BS minimal 1',
            'id_bs.max' => 'Kode BS maksimal 4 digit',

            'id_nks.numeric' => 'Kolom id_nks harus berupa angka (contoh: 101, 000101)',
            'id_nks.max' => 'Kode NKS maksimal 6 digit',

            'nama_pengawas.string' => 'Nama pengawas harus berupa teks',
            'nama_pengawas.max' => 'Nama pengawas maksimal 100 karakter',
        ];
    }

    /**
     * ✅ CUSTOM VALIDATION ATTRIBUTES (untuk penamaan kolom di error message)
     */
    public function customValidationAttributes()
    {
        return [
            'id_kec' => 'Kode Kecamatan',
            'id_desa' => 'Kode Desa',
            'id_bs' => 'Kode BS',
            'id_nks' => 'Kode NKS',
            'nama_pengawas' => 'Nama Pengawas',
        ];
    }

    /**
     * ✅ HANDLE ERROR SAAT IMPORT
     */
    public function onError(\Throwable $e)
    {
        $error = "Error sistem: " . $e->getMessage();
        $this->errors[] = $error;
        $this->skipped++;
        Log::error('WilayahTugas Import Exception: ' . $e->getMessage());
    }

    /**
     * ✅ HANDLE VALIDATION FAILURE
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $error = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            $this->errors[] = $error;
            $this->skipped++;
            Log::warning("Import Validation Failed: " . $error);
        }
    }

    /**
     * ✅ GETTER UNTUK REPORT
     */
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
