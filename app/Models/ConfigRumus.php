<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigRumus extends Model
{
    use HasFactory;

    protected $table = 'config_rumus';

    protected $fillable = [
        'nama_rumus',
        'deskripsi',
        'kondisi_bekerja',
        'kondisi_pengangguran',
        'is_active',
    ];

    protected $casts = [
        'kondisi_bekerja' => 'array',
        'kondisi_pengangguran' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get active rumus
     */
    public static function getActiveRumus()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Evaluate kondisi terhadap data responden
     */
    public function evaluateKondisi($kondisi, $responden)
    {
        $operator = $kondisi['operator'] ?? 'AND';
        $conditions = $kondisi['conditions'] ?? [];

        $results = [];
        foreach ($conditions as $condition) {
            $field = $condition['field'];
            $expectedValue = $condition['value'];
            $actualValue = $responden->{$field};

            $results[] = ($actualValue == $expectedValue);
        }

        if ($operator === 'AND') {
            return !in_array(false, $results);
        } else { // OR
            return in_array(true, $results);
        }
    }
}
