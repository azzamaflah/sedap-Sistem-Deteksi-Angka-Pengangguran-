<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigQuest extends Model
{
    use HasFactory;

    protected $table = 'config_quest';

    protected $fillable = [
        'key',
        'label',
        'description',
        'type',
        'options',
        'is_active',
        'order',
        'conditional_target', // DITAMBAH: Field untuk menyimpan aturan bersyarat
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'conditional_target' => 'array', // DITAMBAH: Cast sebagai array (JSON)
    ];

    /**
     * Get all active quest ordered
     */
    public static function getActiveQuest()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
    /**
     * Helper untuk mendapatkan options dalam format array
     */
    public function getOptionsArray()
    {
        if (is_array($this->options)) {
            return $this->options;
        }

        // Antisipasi kalau options nyimpan string, misal: 'Ya, Tidak'
        if (is_string($this->options) && !empty($this->options)) {
            return array_map('trim', explode(',', $this->options));
        }

        return [];
    }
    
    /**
     * Get quest by key
     */
    public static function getByKey($key)
    {
        return self::where('key', $key)->first();
    }
}