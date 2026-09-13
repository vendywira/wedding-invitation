<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeddingTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'is_active',
        'sections_config',
        'styling_config',
        'assets_config',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sections_config' => 'array',
        'styling_config' => 'array',
        'assets_config' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->is_active) {
                static::where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });
    }

    public function getSectionsAttribute(): array
    {
        return $this->sections_config['order'] ?? [];
    }

    public function isSectionEnabled(string $section): bool
    {
        return $this->sections_config['enabled'][$section] ?? true;
    }

    public function getAsset(string $key, ?string $default = null): ?string
    {
        return $this->assets_config[$key] ?? $default;
    }

    public function getStyle(string $key, ?string $default = null): ?string
    {
        return $this->styling_config[$key] ?? $default;
    }
}
