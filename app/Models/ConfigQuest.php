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
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
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
    public function getOptionsArray()
    {
        if (is_array($this->options)) {
            return $this->options;
        }

        // antisipasi kalau options nyimpan string, misal: 'Ya, Tidak'
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
