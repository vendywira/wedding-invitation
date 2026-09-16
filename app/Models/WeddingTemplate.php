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
        'gallery',
        'template_settings',
        'gifts',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sections_config' => 'array',
        'styling_config' => 'array',
        'assets_config' => 'array',
        'gallery' => 'array',
        'template_settings' => 'array',
        'gifts' => 'array',
    ];

    /**
     * Gift types the invitation knows how to render.
     */
    public const GIFT_TYPES = ['bank', 'ewallet', 'address'];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($model) {
            // Only deactivate others when this model is being set to active
            // and already has an ID (not a brand-new unsaved record)
            if ($model->is_active && $model->exists) {
                static::where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });
    }

    /**
     * Activate this template (deactivate all others).
     */
    public function activateTemplate(): bool
    {
        // Deactivate all others first
        static::where('id', '!=', $this->id)->update(['is_active' => false]);

        // Then activate this one
        return $this->update(['is_active' => true]);
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

    public function getAssetUrl(string $key, ?string $default = null): ?string
    {
        $path = $this->getAsset($key, $default);

        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'template-assets/')
            ? asset('storage/'.$path)
            : asset($path);
    }

    public function getGalleryImageUrl(array $image): ?string
    {
        $path = $image['path'] ?? null;

        return $path ? asset('storage/'.ltrim($path, '/')) : null;
    }

    public function getStyle(string $key, ?string $default = null): ?string
    {
        return $this->styling_config[$key] ?? $default;
    }

    public function getSetting(string $key, ?string $default = null): ?string
    {
        return $this->template_settings[$key] ?? $default;
    }

    public function getGalleryImages(): array
    {
        return $this->gallery ?? [];
    }

    /**
     * Gift entries shown in the "Wedding Gift" section, in display order.
     *
     * Every entry is normalised so the invitation can render it without
     * guessing: `type` decides the layout (bank/e-wallet number vs. a shipping
     * address) and `id` also keys its uploaded logo (`gift_logo_<id>`).
     *
     * @return array<int, array<string, string|null>>
     */
    public function getGifts(): array
    {
        $gifts = $this->gifts ?? [];

        $normalised = [];

        foreach (array_values($gifts) as $index => $gift) {
            if (! is_array($gift)) {
                continue;
            }

            $type = (string) ($gift['type'] ?? 'bank');

            $normalised[] = [
                'id' => (string) ($gift['id'] ?? 'gift-'.($index + 1)),
                'type' => in_array($type, self::GIFT_TYPES, true) ? $type : 'bank',
                'label' => (string) ($gift['label'] ?? ''),
                'number' => (string) ($gift['number'] ?? ''),
                'holder' => (string) ($gift['holder'] ?? ''),
                'address' => (string) ($gift['address'] ?? ''),
                'default_logo' => $gift['default_logo'] ?? null,
            ];
        }

        return $normalised;
    }

    /**
     * Logo of a gift entry: the uploaded file when there is one, otherwise the
     * bundled default that shipped with the template.
     */
    public function getGiftLogoUrl(array $gift): ?string
    {
        $path = $this->getAsset('gift_logo_'.$gift['id'], $gift['default_logo'] ?? null);

        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'template-assets/')
            ? asset('storage/'.$path)
            : asset($path);
    }

    public function addGalleryImage(string $path, ?string $caption = null): void
    {
        $gallery = $this->gallery ?? [];
        $gallery[] = [
            'path' => $path,
            'caption' => $caption,
            'created_at' => now()->toDateTimeString(),
        ];
        $this->update(['gallery' => $gallery]);
    }

    public function removeGalleryImage(int $index): void
    {
        $gallery = $this->gallery ?? [];
        if (isset($gallery[$index])) {
            unset($gallery[$index]);
            $this->update(['gallery' => array_values($gallery)]);
        }
    }

    public function reorderGallery(array $orderedIds): void
    {
        $gallery = $this->gallery ?? [];
        $reordered = [];
        $used = [];

        foreach ($orderedIds as $id) {
            foreach ($gallery as $position => $item) {
                if (($item['path'] ?? null) === $id && ! isset($used[$position])) {
                    $reordered[] = $item;
                    $used[$position] = true;
                    break;
                }
            }
        }

        // Append any image that was not part of the submitted order so a
        // partial payload can never silently drop photos.
        foreach ($gallery as $position => $item) {
            if (! isset($used[$position])) {
                $reordered[] = $item;
            }
        }

        $this->update(['gallery' => $reordered]);
    }
}
