<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responden extends Model
{
    use HasFactory;

    protected $table = 'responden';
    protected $primaryKey = 'no';
    public $timestamps = true;

    protected $fillable = [
        'id_kec',
        'id_desa',
        'id_bs',
        'id_nks',
        'id_nurt',
        'nama_sample',
        'r7_1',  // Quest 1
        'r7_2',  // Quest 2
        'r7_3',  // Quest 3
        'r8_1',  // Quest 4
        'r9_1',  // Quest 5
        'r9_3',  // Quest 6
        'r20_1', // Quest 7
        'r20_2', // Quest 8
        'r20_4', // Quest 9
        'bekerja',
        'pengangguran',
    ];

    // Relasi ke Kecamatan
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kec', 'id_kec');
    }

    // Relasi ke Desa
    public function desa()
    {
        return $this->belongsTo(Desa::class, 'id_desa', 'id_desa');
    }

    // Relasi ke Wilayah Tugas
    public function wilayahTugas()
    {
        return $this->belongsTo(WilayahTugas::class, 'id_bs', 'id_bs');
    }

    // Relasi ke DSRT
    public function dsrt()
    {
        return $this->belongsTo(Dsrt::class, 'id_nurt', 'id_nurt');
    }

    /**
     * Method BARU: Hitung status berdasarkan rumus dinamis dari config
     */
    public function hitungStatus()
    {
        // 1. Ambil rumus yang aktif
        $rumus = ConfigRumus::getActiveRumus();

        if (!$rumus) {
            // Fallback ke logic lama jika tidak ada rumus aktif
            $this->hitungStatusLegacy();
            return;
        }

        // 2. Evaluasi kondisi Bekerja (Menggunakan evaluateKondisi dan mengirimkan $this)
        if ($rumus->evaluateKondisi($rumus->kondisi_bekerja, $this)) {
            $this->bekerja = 'Bekerja';
            $this->pengangguran = null;

            // Logic Quest 7-9 tetap sama (jika Bekerja)
            if ($this->r20_1 == 'Ya' && $this->r20_2 == 'Ya') {
                $this->r20_4 = null; // Quest 9 tidak diisi
            }
        }
        // 3. Evaluasi kondisi Pengangguran
        elseif ($rumus->evaluateKondisi($rumus->kondisi_pengangguran, $this)) {
            $this->bekerja = null;
            $this->pengangguran = 'Pengangguran';

            // Reset Quest 5-6 karena tidak boleh diisi (jika Pengangguran)
            $this->r9_1 = null;
            $this->r9_3 = null;
        }
        // 4. Default
        else {
            $this->bekerja = null;
            $this->pengangguran = null;
        }
    }

    /**
     * Method LAMA: Fallback jika tidak ada rumus aktif
     */
    private function hitungStatusLegacy()
    {
        // Cek apakah salah satu dari Quest 1-4 dijawab "Ya"
        if ($this->r7_1 == 'Ya' || $this->r7_2 == 'Ya' || $this->r7_3 == 'Ya' || $this->r8_1 == 'Ya') {
            $this->bekerja = 'Bekerja';
            $this->pengangguran = null;

            if ($this->r20_1 == 'Ya' && $this->r20_2 == 'Ya') {
                $this->r20_4 = null;
            }
        }
        // Jika semua Quest 1-4 dijawab "Tidak"
        else if (
            $this->r7_1 == 'Tidak' && $this->r7_2 == 'Tidak' &&
            $this->r7_3 == 'Tidak' && $this->r8_1 == 'Tidak'
        ) {
            $this->bekerja = null;
            $this->pengangguran = 'Pengangguran';
            $this->r9_1 = null;
            $this->r9_3 = null;
        } else {
            $this->bekerja = null;
            $this->pengangguran = null;
        }
    }

    /**
     * Check apakah Quest 7-9 harus ditampilkan
     */
    public function shouldShowQuest789()
    {
        return ($this->r7_1 == 'Ya' || $this->r7_2 == 'Ya' ||
            $this->r7_3 == 'Ya' || $this->r8_1 == 'Ya');
    }
}