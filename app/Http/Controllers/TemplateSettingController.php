<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\WeddingTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemplateSettingController extends Controller
{
    /**
     * Asset-specific validation rules.
     * Each key defines allowed dimensions, aspect ratio tolerance, and label.
     * auto_resize = true means oversized images are downscaled server-side
     * instead of being rejected (photos from modern phones can be huge).
     */
    private array $assetValidationRules = [
        'bride_photo' => [
            'label' => 'Foto Mempelai Wanita',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 6000,
            'aspect_ratio' => null,       // null = any ratio accepted
            'preferred_ratio' => '3:4',   // portrait preferred
            'ratio_tolerance' => 0.15,
            'auto_resize' => true,
        ],
        'groom_photo' => [
            'label' => 'Foto Mempelai Pria',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 6000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.15,
            'auto_resize' => true,
        ],
        'cover_photo' => [
            'label' => 'Foto Cover Depan (Sampul)',
            'min_width' => 600,
            'min_height' => 600,
            'max_width' => 4000,
            'max_height' => 6000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.35,
            'auto_resize' => true,
        ],
        'hero_photo' => [
            'label' => 'Foto Hero / A Journey of Love Begins',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 6000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.25,
            'auto_resize' => true,
        ],
        'story_image' => [
            'label' => 'Foto Cerita/Couple',
            'min_width' => 600,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '16:9',
            'ratio_tolerance' => 0.2,
            'auto_resize' => true,
        ],
        'logo_image' => [
            'label' => 'Logo Template',
            'min_width' => 100,
            'min_height' => 100,
            'max_width' => 2000,
            'max_height' => 2000,
            'aspect_ratio' => '1:1',       // square required
            'preferred_ratio' => '1:1',
            'ratio_tolerance' => 0.1,
            'auto_resize' => true,
        ],
        'flower_decoration' => [
            'label' => 'Dekorasi Bunga',
            'min_width' => 100,
            'min_height' => 100,
            'max_width' => 2000,
            'max_height' => 2000,
            'aspect_ratio' => null,
            'preferred_ratio' => '1:1',
            'ratio_tolerance' => 0.3,
            'auto_resize' => true,
        ],
        'lamp_decoration' => [
            'label' => 'Dekorasi Lampu',
            'min_width' => 100,
            'min_height' => 100,
            'max_width' => 2000,
            'max_height' => 3000,
            'aspect_ratio' => null,
            'auto_resize' => true,
        ],
        'curtain_decoration' => [
            'label' => 'Dekorasi Tirai',
            'min_width' => 200,
            'min_height' => 400,
            'max_width' => 2000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '1:3',
            'ratio_tolerance' => 0.2,
            'auto_resize' => true,
        ],
        'divider_image' => [
            'label' => 'Divider/Garis Pemisah',
            'min_width' => 200,
            'min_height' => 50,
            'max_width' => 3000,
            'max_height' => 1000,
            'aspect_ratio' => null,
            'preferred_ratio' => '4:1',
            'ratio_tolerance' => 0.3,
            'auto_resize' => true,
        ],
        'scroll_gif' => [
            'label' => 'Animasi Scroll',
            'min_width' => 50,
            'min_height' => 50,
            'max_width' => 500,
            'max_height' => 500,
            'aspect_ratio' => '1:1',
            'preferred_ratio' => '1:1',
            'ratio_tolerance' => 0.1,
            // no auto_resize: animated GIF cannot be safely re-encoded by GD
        ],
        'floral_border' => [
            'label' => 'Border Bunga',
            'min_width' => 400,
            'min_height' => 100,
            'max_width' => 4000,
            'max_height' => 2000,
            'aspect_ratio' => null,
            'preferred_ratio' => '5:1',
            'ratio_tolerance' => 0.3,
            'auto_resize' => true,
        ],
        'bg_slide_1' => [
            'label' => 'Background Slide 1',
            'min_width' => 800,
            'min_height' => 600,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '16:9',
            'ratio_tolerance' => 0.3,
            'auto_resize' => true,
        ],
        'bg_slide_2' => [
            'label' => 'Background Slide 2',
            'min_width' => 800,
            'min_height' => 600,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '16:9',
            'ratio_tolerance' => 0.3,
            'auto_resize' => true,
        ],
        'closing_image' => [
            'label' => 'Foto Penutup / Terima Kasih',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 6000,
            'aspect_ratio' => null,
            'preferred_ratio' => '2:3',
            'ratio_tolerance' => 0.2,
            'auto_resize' => true,
        ],
        'gallery_1' => [
            'label' => 'Gallery Photo 1',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.2,
            'auto_resize' => true,
        ],
        'gallery_2' => [
            'label' => 'Gallery Photo 2',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.2,
            'auto_resize' => true,
        ],
        'dresscode_image' => [
            'label' => 'Gambar Dress Code',
            'min_width' => 400,
            'min_height' => 150,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '16:9',
            'ratio_tolerance' => 0.4,
            'auto_resize' => true,
        ],
        // Bank logos now belong to their gift entry (`gift_logo_<id>`), so they
        // are validated by the generic rules there and no longer live here.
        'bg_cover' => [
            'label' => 'Background Cover',
            'min_width' => 600,
            'min_height' => 600,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.4,
            'auto_resize' => true,
        ],
        'bg_paper' => [
            'label' => 'Background Kertas Bunga',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.5,
            'auto_resize' => true,
        ],
        'bg_section' => [
            'label' => 'Background Section',
            'min_width' => 600,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '16:9',
            'ratio_tolerance' => 0.5,
            'auto_resize' => true,
        ],
        'gift_card_bg' => [
            'label' => 'Background Kartu Hadiah',
            'min_width' => 400,
            'min_height' => 200,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '16:9',
            'ratio_tolerance' => 0.5,
            'auto_resize' => true,
        ],
        'modal_overlay' => [
            'label' => 'Background Cover Undangan',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.5,
            'auto_resize' => true,
        ],
        'desktop_cover' => [
            'label' => 'Cover Depan Desktop',
            'min_width' => 600,
            'min_height' => 600,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.5,
            'auto_resize' => true,
        ],
        'bg_all' => [
            'label' => 'Background Utama',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.5,
            'auto_resize' => true,
        ],
        'bg_plain_paper' => [
            'label' => 'Background Kertas Polos',
            'min_width' => 400,
            'min_height' => 400,
            'max_width' => 4000,
            'max_height' => 4000,
            'aspect_ratio' => null,
            'preferred_ratio' => '3:4',
            'ratio_tolerance' => 0.5,
            'auto_resize' => true,
        ],
    ];

    /**
     * Gallery image validation rules
     * Note: auto_resize = true means oversized images are downscaled server-side
     * instead of being rejected (photos from modern phones can exceed 6000px).
     */
    private array $galleryValidationRules = [
        'min_width' => 400,
        'min_height' => 400,
        'max_width' => 6000,
        'max_height' => 6000,
        'preferred_ratio' => '3:4',
        'ratio_tolerance' => 0.4,   // gallery is lenient — accepts portrait, landscape, square
        'auto_resize' => true,
        'max_file_size_mb' => 5,
        'max_gallery_count' => 50,
    ];

    /**
     * Validate image dimensions and aspect ratio.
     *
     * @return array{valid: bool, width?: int, height?: int, errors?: string[], warnings?: string[]}
     */
    private function validateImageDimensions(string $filePath, ?array $assetRule = null, ?array $galleryRule = null): array
    {
        $fullPath = Storage::disk('public')->path($filePath);

        if (! file_exists($fullPath)) {
            return ['valid' => false, 'errors' => ['File tidak ditemukan di storage']];
        }

        $imageInfo = @getimagesize($fullPath);

        if ($imageInfo === false) {
            return ['valid' => false, 'errors' => ['File bukan gambar yang valid']];
        }

        [$width, $height] = $imageInfo;

        $errors = [];
        $warnings = [];
        $rule = $assetRule ?? $galleryRule;

        if ($rule) {
            // Check minimum dimensions
            if (isset($rule['min_width']) && $width < $rule['min_width']) {
                $errors[] = "Lebar gambar terlalu kecil ({$width}px). Minimal {$rule['min_width']}px.";
            }
            if (isset($rule['min_height']) && $height < $rule['min_height']) {
                $errors[] = "Tinggi gambar terlalu kecil ({$height}px). Minimal {$rule['min_height']}px.";
            }

            // Check maximum dimensions
            if (isset($rule['max_width']) && $width > $rule['max_width']) {
                $errors[] = "Lebar gambar terlalu besar ({$width}px). Maksimal {$rule['max_width']}px.";
            }
            if (isset($rule['max_height']) && $height > $rule['max_height']) {
                $errors[] = "Tinggi gambar terlalu besar ({$height}px). Maksimal {$rule['max_height']}px.";
            }

            // Check strict aspect ratio (e.g. logo must be square)
            if (isset($rule['aspect_ratio']) && $rule['aspect_ratio']) {
                [$requiredW, $requiredH] = array_map('intval', explode(':', $rule['aspect_ratio']));
                $actualRatio = $width / $height;
                $requiredRatio = $requiredW / $requiredH;
                $tolerance = $rule['ratio_tolerance'] ?? 0.1;

                if (abs($actualRatio - $requiredRatio) / $requiredRatio > $tolerance) {
                    $errors[] = "Rasio gambar harus {$rule['aspect_ratio']} (saat ini {$width}:{$height}).";
                }
            }

            // Check preferred aspect ratio (warning only)
            if (! isset($rule['aspect_ratio']) && isset($rule['preferred_ratio']) && $rule['preferred_ratio']) {
                [$prefW, $prefH] = array_map('intval', explode(':', $rule['preferred_ratio']));
                $actualRatio = $width / $height;
                $preferredRatio = $prefW / $prefH;
                $tolerance = $rule['ratio_tolerance'] ?? 0.2;

                if (abs($actualRatio - $preferredRatio) / $preferredRatio > $tolerance) {
                    $label = $rule['label'] ?? 'Gambar';
                    $warnings[] = "{$label}: Rasio ideal adalah {$rule['preferred_ratio']}, saat ini {$width}:{$height}. Gambar akan tetap tersimpan namun mungkin tidak pas di template.";
                }
            }
        }

        return [
            'valid' => empty($errors),
            'width' => $width,
            'height' => $height,
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Downscale an image in-place if it exceeds the given dimensions.
     *
     * @return array{resized: bool, width: int, height: int, original_width?: int, original_height?: int, error?: string}
     */
    private function downscaleImage(string $path, int $maxWidth, int $maxHeight): array
    {
        $fullPath = Storage::disk('public')->path($path);

        if (! file_exists($fullPath)) {
            return ['resized' => false, 'width' => 0, 'height' => 0, 'error' => 'File tidak ditemukan di storage'];
        }

        $info = @getimagesize($fullPath);

        if ($info === false) {
            return ['resized' => false, 'width' => 0, 'height' => 0, 'error' => 'File bukan gambar yang valid'];
        }

        [$width, $height] = $info;
        $mime = $info['mime'] ?? '';

        if ($width <= $maxWidth && $height <= $maxHeight) {
            return ['resized' => false, 'width' => $width, 'height' => $height];
        }

        // GD decodes the whole image into raw pixels (~4 bytes/px), and EXIF rotation
        // adds another full-size copy. Make sure PHP can actually hold it, otherwise
        // the request would die with a fatal "memory exhausted" error.
        $estimatedBytes = $width * $height * 8;
        $memoryLimit = $this->memoryLimitBytes();

        if ($memoryLimit !== PHP_INT_MAX && $estimatedBytes > $memoryLimit * 0.8) {
            $targetLimitMb = (int) min(2048, ceil($estimatedBytes * 1.5 / 1024 / 1024));
            @ini_set('memory_limit', $targetLimitMb.'M');

            if ($this->memoryLimitBytes() < $estimatedBytes) {
                // Cannot secure enough memory — leave the file untouched so the
                // dimension validation reports a clear error instead of crashing.
                return ['resized' => false, 'width' => $width, 'height' => $height, 'error' => 'Memori server tidak cukup untuk memproses gambar sebesar ini'];
            }
        }

        $loader = match ($mime) {
            'image/jpeg', 'image/jpg' => 'imagecreatefromjpeg',
            'image/png' => 'imagecreatefrompng',
            'image/webp' => 'imagecreatefromwebp',
            default => null,
        };

        // Skip formats we cannot safely re-encode (e.g. animated GIF)
        if ($loader === null || ! function_exists($loader)) {
            return ['resized' => false, 'width' => $width, 'height' => $height];
        }

        $src = @$loader($fullPath);

        if ($src === false) {
            return ['resized' => false, 'width' => $width, 'height' => $height, 'error' => 'Gagal memproses gambar'];
        }

        // Respect EXIF orientation (phone photos) so the resized image is not rotated
        if ($mime === 'image/jpeg' && function_exists('exif_read_data')) {
            $exif = @exif_read_data($fullPath);
            $orientation = (int) ($exif['Orientation'] ?? 1);

            if ($orientation === 3 || $orientation === 6 || $orientation === 8) {
                $angle = match ($orientation) {
                    3 => 180,
                    6 => -90,
                    default => 90,
                };
                $rotated = @imagerotate($src, $angle, 0);

                if ($rotated !== false) {
                    imagedestroy($src);
                    $src = $rotated;
                }
            }
        }

        $width = imagesx($src);
        $height = imagesy($src);

        $scale = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = max(1, (int) floor($width * $scale));
        $newHeight = max(1, (int) floor($height * $scale));

        $dst = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG/WebP
        if (in_array($mime, ['image/png', 'image/webp'], true)) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        $saved = match ($mime) {
            'image/png' => imagepng($dst, $fullPath, 8),
            'image/webp' => imagewebp($dst, $fullPath, 85),
            default => imagejpeg($dst, $fullPath, 85),
        };

        imagedestroy($src);
        imagedestroy($dst);

        if (! $saved) {
            return ['resized' => false, 'width' => $width, 'height' => $height, 'error' => 'Gagal menyimpan gambar hasil resize'];
        }

        clearstatcache(true, $fullPath);

        return [
            'resized' => true,
            'width' => $newWidth,
            'height' => $newHeight,
            'original_width' => $info[0],
            'original_height' => $info[1],
        ];
    }

    /**
     * Parse the current PHP memory_limit into bytes (PHP_INT_MAX when unlimited).
     */
    private function memoryLimitBytes(): int
    {
        $limit = trim((string) ini_get('memory_limit'));

        if ($limit === '' || $limit === '-1') {
            return PHP_INT_MAX;
        }

        if (is_numeric($limit)) {
            return (int) $limit;
        }

        $value = (float) substr($limit, 0, -1);

        return match (strtolower(substr($limit, -1))) {
            'g' => (int) ($value * 1024 ** 3),
            'm' => (int) ($value * 1024 ** 2),
            'k' => (int) ($value * 1024),
            default => (int) $value,
        };
    }

    /**
     * Content-only view for the dashboard Settings tab.
     *
     * This is the single settings panel: the former standalone
     * `/template-settings` page was removed so there is only one place to
     * edit a template.
     */
    public function contentOnly()
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            $template = WeddingTemplate::first();
        }

        if (! $template) {
            return response()->json(['success' => false, 'message' => 'Tidak ada template'], 404);
        }

        return view('admin.template-settings-content', [
            'template' => $template,
            'assetValidationRules' => $this->assetValidationRules,
            'galleryValidationRules' => $this->galleryValidationRules,
            'groups' => $this->groups(),
            'gifts' => $template->getGifts(),
        ]);
    }

    /**
     * Update template settings (couple info, text content, etc.)
     *
     * Settings are free-form key/value pairs, so they are validated as scalar
     * strings instead of an exhaustive whitelist. A whitelist used to silently
     * drop keys that were not listed (section toggles, ayat, gift text, ...),
     * which made those fields look like they never saved.
     */
    public function updateSettings(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $validated = $request->validate([
            'template_settings' => 'nullable|array',
            'template_settings.*' => 'nullable|string|max:5000',
        ]);

        $newSettings = array_merge(
            $template->template_settings ?? [],
            $validated['template_settings'] ?? []
        );

        $template->update(['template_settings' => $newSettings]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan template berhasil disimpan',
            'settings' => $newSettings,
            'assets' => $template->assets_config ?? [],
        ]);
    }

    /**
     * Replace the whole gift list (bank accounts, e-wallets, shipping address).
     *
     * Every entry owns a logo slot keyed by its id (`gift_logo_<id>`), which is
     * uploaded/deleted through the generic asset endpoints; removing an entry
     * here also removes its uploaded logo.
     */
    public function updateGifts(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $validated = $request->validate([
            'gifts' => 'nullable|array|max:20',
            'gifts.*.id' => 'nullable|string|max:60',
            'gifts.*.type' => 'nullable|string|in:'.implode(',', WeddingTemplate::GIFT_TYPES),
            'gifts.*.label' => 'nullable|string|max:120',
            'gifts.*.number' => 'nullable|string|max:120',
            'gifts.*.holder' => 'nullable|string|max:120',
            'gifts.*.address' => 'nullable|string|max:1000',
            'gifts.*.default_logo' => 'nullable|string|max:255',
        ], [
            'gifts.max' => 'Maksimal 20 entri hadiah.',
        ]);

        $existing = $template->getGifts();
        $existingById = [];

        foreach ($existing as $gift) {
            $existingById[$gift['id']] = $gift;
        }

        $gifts = [];

        foreach ($validated['gifts'] ?? [] as $index => $gift) {
            // Rows without a label are simply ignored, so an empty "Tambah
            // Hadiah" row never blocks saving.
            if (blank($gift['label'] ?? null)) {
                continue;
            }

            $id = filled($gift['id'] ?? null) ? (string) $gift['id'] : 'gift-'.($index + 1);

            $gifts[] = [
                'id' => $id,
                'type' => $gift['type'] ?? 'bank',
                'label' => $gift['label'],
                'number' => $gift['number'] ?? '',
                'holder' => $gift['holder'] ?? '',
                'address' => $gift['address'] ?? '',
                // Entries seeded from the old fixed cards keep their bundled logo.
                'default_logo' => $gift['default_logo'] ?? ($existingById[$id]['default_logo'] ?? null),
            ];
        }

        $template->update(['gifts' => $gifts]);

        // Drop the uploaded logo of every entry that is no longer in the list.
        $keptIds = array_column($gifts, 'id');
        $assets = $template->assets_config ?? [];
        $assetsChanged = false;

        foreach (array_keys($existingById) as $removedId) {
            if (in_array($removedId, $keptIds, true)) {
                continue;
            }

            $key = 'gift_logo_'.$removedId;

            if (empty($assets[$key])) {
                continue;
            }

            if (Storage::disk('public')->exists($assets[$key])) {
                Storage::disk('public')->delete($assets[$key]);
            }

            unset($assets[$key]);
            $assetsChanged = true;
        }

        if ($assetsChanged) {
            $template->update(['assets_config' => $assets]);
        }

        return response()->json([
            'success' => true,
            'message' => count($gifts).' entri hadiah berhasil disimpan',
            'gifts' => $template->fresh()->getGifts(),
        ]);
    }

    /**
     * Every invitation group, in the order the admin arranged them.
     *
     * A group is one `events` row: `event_key` is the public URL slug
     * (/<event_key>/invitation), `group_name` is its dashboard label and its
     * `event_details` are the extra ceremonies of that invitation.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Event>
     */
    private function groups()
    {
        return Event::groups();
    }

    /**
     * Validation rules shared by the create/update group forms.
     *
     * @return array<string, mixed>
     */
    private function groupValidationRules(bool $creating): array
    {
        return [
            // Groups are addressed by id when edited, so the slug stays editable.
            'event_id' => $creating ? 'nullable' : 'required|integer|exists:events,id',
            'event_key' => 'nullable|string|max:60',
            'group_name' => ($creating ? 'required' : 'nullable').'|string|max:255',
            'title' => 'nullable|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'required|string|max:50',
            'finish_time' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:1000',
            'google_map_link' => 'nullable|string|max:1000',
            'is_default' => 'nullable|boolean',
            // Additional ceremonies. Rows without a title are simply ignored,
            // so an empty "Tambah Acara" row never blocks saving.
            'details' => 'nullable|array',
            'details.*.title' => 'nullable|string|max:255',
            'details.*.event_date' => 'nullable|date',
            'details.*.start_time' => 'nullable|string|max:50',
            'details.*.finish_time' => 'nullable|string|max:50',
            'details.*.location' => 'nullable|string|max:255',
            'details.*.address' => 'nullable|string|max:1000',
            'details.*.google_map_link' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Turn a name into a unique URL slug (public-facing part of the link).
     * An existing group is ignored when it is the row being updated.
     */
    private function uniqueGroupSlug(?string $requested, string $fallback, ?int $ignoreId = null): string
    {
        $base = Str::slug((string) ($requested ?: $fallback));

        if ($base === '') {
            $base = 'undangan';
        }

        $slug = $base;
        $suffix = 2;

        while (Event::where('event_key', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    /**
     * Replace a group's extra ceremonies with the submitted list, so ordering
     * and deletions are handled in a single save.
     *
     * @param  array<int, array<string, mixed>>  $details
     */
    private function syncGroupDetails(Event $event, array $details): void
    {
        $event->details()->delete();

        $position = 0;

        foreach ($details as $detail) {
            if (blank($detail['title'] ?? null)) {
                continue;
            }

            $event->details()->create([
                'title' => $detail['title'],
                'event_date' => $detail['event_date'] ?? null,
                'start_time' => $detail['start_time'] ?? null,
                'finish_time' => $detail['finish_time'] ?? null,
                'location' => $detail['location'] ?? null,
                'address' => $detail['address'] ?? null,
                'google_map_link' => $detail['google_map_link'] ?? null,
                'sort_order' => $position++,
            ]);
        }
    }

    /**
     * Group list as JSON, so the dashboard can refresh its guest dropdowns and
     * filters right after a group is created or deleted (no page reload).
     */
    public function listGroups()
    {
        return response()->json([
            'success' => true,
            'groups' => $this->groups()->map(function (Event $group) {
                return [
                    'slug' => $group->event_key,
                    'label' => $group->label,
                    'is_default' => (bool) $group->is_default,
                    'guest_count' => $group->guest_count,
                ];
            })->values(),
        ]);
    }

    /**
     * Create a new invitation group (its own URL, ceremonies and guest list).
     */
    public function storeGroup(Request $request)
    {
        $validated = $request->validate($this->groupValidationRules(true), [
            'group_name.required' => 'Nama grup undangan wajib diisi.',
            'event_date.required' => 'Tanggal acara utama wajib diisi.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'finish_time.required' => 'Waktu selesai wajib diisi.',
            'location.required' => 'Nama tempat/lokasi wajib diisi.',
        ]);

        $event = Event::create([
            'event_key' => $this->uniqueGroupSlug($validated['event_key'] ?? null, $validated['group_name']),
            'group_name' => $validated['group_name'],
            'title' => $validated['title'] ?? null,
            'event_date' => $validated['event_date'],
            'start_time' => $validated['start_time'],
            'finish_time' => $validated['finish_time'],
            'location' => $validated['location'],
            'address' => $validated['address'] ?? null,
            'google_map_link' => $validated['google_map_link'] ?? null,
            'sort_order' => (int) Event::max('sort_order') + 1,
        ]);

        $this->syncGroupDetails($event, $validated['details'] ?? []);

        // The first group ever created becomes the public /invitation target.
        if ($request->boolean('is_default') || Event::where('is_default', true)->doesntExist()) {
            $event->makeDefault();
        }

        return response()->json([
            'success' => true,
            'message' => 'Grup undangan "'.$event->label.'" berhasil dibuat',
            'event' => $event->fresh()->load('details'),
        ]);
    }

    /**
     * Delete an invitation group. Refuses while guests still belong to it, and
     * never removes the last remaining group.
     */
    public function destroyGroup(Request $request)
    {
        $validated = $request->validate([
            'event_key' => 'required|string|max:60|exists:events,event_key',
        ]);

        $event = Event::where('event_key', $validated['event_key'])->first();

        if (! $event) {
            return response()->json([
                'success' => false,
                'message' => 'Grup undangan tidak ditemukan',
            ], 404);
        }

        $guestCount = $event->guests()->count();

        if ($guestCount > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Grup "'.$event->label.'" masih punya '.$guestCount.' tamu. Pindahkan atau hapus tamunya terlebih dahulu.',
            ], 422);
        }

        if (Event::count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal harus ada satu grup undangan.',
            ], 422);
        }

        $label = $event->label;
        $wasDefault = $event->is_default;

        $event->delete();

        if ($wasDefault) {
            Event::defaultGroup()?->makeDefault();
        }

        return response()->json([
            'success' => true,
            'message' => 'Grup undangan "'.$label.'" berhasil dihapus',
        ]);
    }

    /**
     * Update one invitation group: its name/slug and its ceremonies (the primary
     * event plus any number of additional ceremonies shown after it in the
     * "Wedding Day" section of that invitation).
     */
    public function updateEvents(Request $request)
    {
        $validated = $request->validate($this->groupValidationRules(false));

        $event = Event::find($validated['event_id']);

        if (! $event) {
            return response()->json([
                'success' => false,
                'message' => 'Grup undangan tidak ditemukan',
            ], 422);
        }

        $attributes = collect($validated)->except(['details', 'is_default', 'event_id', 'event_key'])->all();

        // The public URL slug follows the group name unless it is set manually.
        $attributes['event_key'] = $this->uniqueGroupSlug(
            $validated['event_key'] ?? $event->event_key,
            $validated['group_name'] ?? $event->label,
            $event->id
        );

        $event->fill($attributes)->save();

        // A payload that omits `details` entirely (legacy/partial callers) leaves
        // the existing extra ceremonies untouched instead of silently wiping them.
        if ($request->has('details')) {
            $this->syncGroupDetails($event, $validated['details'] ?? []);
        }

        if ($request->boolean('is_default')) {
            $event->makeDefault();
        }

        $total = $event->details()->count() + 1;

        return response()->json([
            'success' => true,
            'message' => 'Data acara "'.$event->label.'" berhasil disimpan ('.$total.' acara)',
            'event' => $event->fresh()->load('details'),
        ]);
    }

    /**
     * Upload single image for template assets
     */
    public function uploadAsset(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg,png,webp,gif|max:8192',
            'asset_key' => 'required|string|max:255',
        ], [
            'file.required' => 'Tidak ada file yang terkirim. Coba pilih foto sekali lagi (file mungkin terlalu besar untuk server).',
            'file.image' => 'File bukan gambar yang valid. Format HEIC dari iPhone perlu dikonversi ke JPG dulu.',
            'file.mimes' => 'Format harus JPG, PNG, WebP, atau GIF.',
            'file.max' => 'Ukuran file maksimal 8MB. Perkecil foto terlebih dahulu.',
        ]);

        $file = $request->file('file');
        $assetKey = $request->input('asset_key');

        // Generate unique filename
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'-'.time().'.'.strtolower($file->getClientOriginalExtension());

        // Store in public/template-assets/{template_slug}/
        $path = $file->storeAs(
            'template-assets/'.$template->slug,
            $filename,
            'public'
        );

        // Validate image dimensions based on asset type
        $assetRule = $this->assetValidationRules[$assetKey] ?? null;

        // Auto-downscale oversized photos instead of rejecting them
        $resize = ['resized' => false];
        if ($assetRule && ($assetRule['auto_resize'] ?? false)) {
            $resize = $this->downscaleImage($path, $assetRule['max_width'], $assetRule['max_height']);
        }

        $dimensionCheck = $this->validateImageDimensions($path, $assetRule);

        if (! $dimensionCheck['valid']) {
            // Remove the uploaded file since validation failed
            Storage::disk('public')->delete($path);

            return response()->json([
                'success' => false,
                'message' => 'Validasi gambar gagal',
                'errors' => $dimensionCheck['errors'],
                'width' => $dimensionCheck['width'] ?? null,
                'height' => $dimensionCheck['height'] ?? null,
            ], 422);
        }

        // Update assets_config
        $currentAssets = $template->assets_config ?? [];
        // Remove old file if exists
        if (isset($currentAssets[$assetKey]) && Storage::disk('public')->exists($currentAssets[$assetKey])) {
            Storage::disk('public')->delete($currentAssets[$assetKey]);
        }
        $currentAssets[$assetKey] = $path;
        $template->update(['assets_config' => $currentAssets]);

        $response = [
            'success' => true,
            'message' => 'Asset berhasil diupload',
            'path' => $path,
            'url' => asset('storage/'.$path),
            'asset_key' => $assetKey,
            'width' => $dimensionCheck['width'],
            'height' => $dimensionCheck['height'],
        ];

        // Include warnings if any
        $warnings = $dimensionCheck['warnings'];

        if (! empty($resize['resized'])) {
            array_unshift(
                $warnings,
                "Asset di-resize otomatis dari {$resize['original_width']}x{$resize['original_height']} ke {$resize['width']}x{$resize['height']}px."
            );
            $response['message'] = 'Asset berhasil diupload (di-resize otomatis)';
        }

        if (! empty($warnings)) {
            $response['warnings'] = $warnings;

            if (! str_contains($response['message'], 'di-resize otomatis')) {
                $response['message'] .= ' (dengan catatan)';
            }
        }

        return response()->json($response);
    }

    /**
     * Upload backsound (file audio) untuk template.
     *
     * Terpisah dari uploadAsset() karena endpoint itu memvalidasi gambar
     * (`image|mimes:jpeg,...`) sekaligus mengukur dimensi/resize — semuanya
     * tidak berlaku untuk audio.
     */
    public function uploadAudio(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $request->validate([
            'file' => 'required|file|mimes:mp3,m4a,m4b,aac,ogg,oga,wav,flac|max:20480',
            'asset_key' => 'required|string|max:255',
        ], [
            'file.required' => 'Tidak ada file audio yang terkirim. Coba pilih file sekali lagi.',
            'file.mimes' => 'Format audio harus MP3, M4A, AAC, OGG, WAV, atau FLAC.',
            'file.max' => 'Ukuran file audio maksimal 20MB. Perkecil/kompres dulu filenya.',
        ]);

        $file = $request->file('file');
        $assetKey = $request->input('asset_key');

        // Nama file di-slug supaya aman dipakai di URL; kalau namanya tidak
        // menghasilkan slug (mis. judul non-latin), pakai nama generik.
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'backsound';
        $filename = $baseName.'-'.time().'.'.strtolower($file->getClientOriginalExtension());

        $path = $file->storeAs(
            'template-assets/'.$template->slug,
            $filename,
            'public'
        );

        $currentAssets = $template->assets_config ?? [];

        // File backsound lama diganti supaya storage tidak menumpuk file besar.
        if (isset($currentAssets[$assetKey]) && Storage::disk('public')->exists($currentAssets[$assetKey])) {
            Storage::disk('public')->delete($currentAssets[$assetKey]);
        }

        $currentAssets[$assetKey] = $path;
        $template->update(['assets_config' => $currentAssets]);

        return response()->json([
            'success' => true,
            'message' => 'Backsound berhasil diupload',
            'path' => $path,
            'url' => asset('storage/'.$path),
            'asset_key' => $assetKey,
            'size' => $file->getSize(),
        ]);
    }

    /**
     * Upload video pembuka (hero) untuk template.
     *
     * Terpisah dari uploadAsset() karena endpoint itu mewajibkan file gambar
     * sekaligus mengukur dimensi/resize — semuanya tidak berlaku untuk video.
     */
    public function uploadVideo(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $request->validate([
            'file' => 'required|file|mimes:mp4,m4v,webm,ogv,mov|max:20480',
            'asset_key' => 'required|string|max:255',
        ], [
            'file.required' => 'Tidak ada file video yang terkirim. Coba pilih file sekali lagi.',
            'file.mimes' => 'Format video harus MP4, M4V, WebM, OGV, atau MOV (H.264 MP4 paling aman).',
            'file.max' => 'Ukuran video maksimal 20MB. Kompres dulu videonya (mis. MP4 720p, 10-15 detik).',
        ]);

        $file = $request->file('file');
        $assetKey = $request->input('asset_key');

        // Nama file di-slug supaya aman dipakai di URL; kalau namanya tidak
        // menghasilkan slug (mis. judul non-latin), pakai nama generik.
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'video-pembuka';
        $filename = $baseName.'-'.time().'.'.strtolower($file->getClientOriginalExtension());

        $path = $file->storeAs(
            'template-assets/'.$template->slug,
            $filename,
            'public'
        );

        $currentAssets = $template->assets_config ?? [];

        // Video lama diganti supaya storage tidak menumpuk file besar.
        if (isset($currentAssets[$assetKey]) && Storage::disk('public')->exists($currentAssets[$assetKey])) {
            Storage::disk('public')->delete($currentAssets[$assetKey]);
        }

        $currentAssets[$assetKey] = $path;
        $template->update(['assets_config' => $currentAssets]);

        $size = (int) $file->getSize();

        $response = [
            'success' => true,
            'message' => 'Video pembuka berhasil diupload',
            'path' => $path,
            'url' => asset('storage/'.$path),
            'asset_key' => $assetKey,
            'size' => $size,
        ];

        // Video besar membuat undangan lambat dibuka di koneksi seluler, jadi
        // diingatkan (filenya tetap dipakai).
        if ($size > 10 * 1024 * 1024) {
            $response['warnings'] = [
                'Ukuran video '.round($size / 1048576, 1).'MB — tamu dengan koneksi lambat akan menunggu lama saat membuka undangan. Idealnya di bawah 10MB.',
            ];
        }

        return response()->json($response);
    }

    /**
     * Upload gallery images
     */
    public function uploadGallery(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $request->validate([
            'files' => 'required|array',
            'files.*' => 'image|mimes:jpeg,jpg,png,webp,gif|max:8192',
            'caption.*' => 'nullable|string|max:255',
        ], [
            'files.required' => 'Tidak ada foto yang terkirim. Coba pilih foto sekali lagi (ukuran total mungkin terlalu besar untuk server).',
            'files.*.image' => 'Salah satu file bukan gambar yang valid. Format HEIC dari iPhone perlu dikonversi ke JPG dulu.',
            'files.*.mimes' => 'Format foto harus JPG, PNG, WebP, atau GIF.',
            'files.*.max' => 'Ada foto yang lebih besar dari 8MB. Perkecil foto terlebih dahulu.',
        ]);

        $currentGallery = $template->getGalleryImages();
        $maxGallery = $this->galleryValidationRules['max_gallery_count'] ?? 50;

        if (count($currentGallery) + count($request->file('files')) > $maxGallery) {
            return response()->json([
                'success' => false,
                'message' => "Gallery maksimal {$maxGallery} foto. Saat ini sudah ada ".count($currentGallery).' foto.',
            ], 422);
        }        $uploadedImages = [];
        $allWarnings = [];
        $results = [];   // per-photo status for the admin UI

        foreach ($request->file('files') as $index => $file) {
            $originalName = $file->getClientOriginalName();
            $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)).'-'.time().'-'.$index.'.'.$file->getClientOriginalExtension();

            $path = $file->storeAs(
                'template-assets/'.$template->slug.'/gallery',
                $filename,
                'public'
            );

            // Auto-downscale oversized photos instead of rejecting them
            $resize = $this->downscaleImage(
                $path,
                $this->galleryValidationRules['max_width'],
                $this->galleryValidationRules['max_height']
            );

            // Validate image dimensions
            $dimensionCheck = $this->validateImageDimensions($path, null, $this->galleryValidationRules);

            if (! $dimensionCheck['valid']) {
                Storage::disk('public')->delete($path);

                $photoErrors = [];
                foreach ($dimensionCheck['errors'] as $err) {
                    $msg = 'Foto '.($index + 1).' ('.$originalName.') dilewati: '.$err;
                    $allWarnings[] = $msg;
                    $photoErrors[] = $msg;
                }

                $results[] = [
                    'name' => $originalName,
                    'status' => 'failed',
                    'errors' => $photoErrors,
                ];

                continue;
            }

            if ($resize['resized']) {
                $allWarnings[] = 'Foto '.($index + 1).' ('.$originalName.') di-resize otomatis dari '.$resize['original_width'].'x'.$resize['original_height'].' ke '.$resize['width'].'x'.$resize['height'].'px.';
            }

            $caption = $request->input("caption.{$index}") ?? null;

            $template->addGalleryImage($path, $caption);

            $imageData = [
                'path' => $path,
                'url' => asset('storage/'.$path),
                'caption' => $caption,
                'width' => $dimensionCheck['width'],
                'height' => $dimensionCheck['height'],
            ];

            $photoWarnings = [];

            if ($resize['resized']) {
                $photoWarnings[] = 'di-resize otomatis dari '.$resize['original_width'].'x'.$resize['original_height'].' ke '.$resize['width'].'x'.$resize['height'].'px';
            }

            if (! empty($dimensionCheck['warnings'])) {
                $imageData['warnings'] = $dimensionCheck['warnings'];
                $allWarnings = array_merge($allWarnings, $dimensionCheck['warnings']);
                $photoWarnings = array_merge($photoWarnings, $dimensionCheck['warnings']);
            }

            $results[] = [
                'name' => $originalName,
                'status' => 'success',
                'width' => $dimensionCheck['width'],
                'height' => $dimensionCheck['height'],
                'warnings' => $photoWarnings,
            ];

            $uploadedImages[] = $imageData;
        }

        if (empty($uploadedImages)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gambar gallery gagal',
                'errors' => $allWarnings,
                'results' => $results,
            ], 422);
        }

        $response = [
            'success' => true,
            'message' => count($uploadedImages).' gambar berhasil diupload ke gallery',
            'images' => $uploadedImages,
            'gallery' => $template->fresh()->getGalleryImages(),
            'results' => $results,
        ];

        if (! empty($allWarnings)) {
            $response['warnings'] = $allWarnings;
        }

        return response()->json($response);
    }

    /**
     * Find a gallery image's position by its storage path.
     *
     * The client's numeric index is never trusted: after a drag-and-drop
     * reorder it points at a different photo, so every request addresses the
     * image by path instead.
     *
     * @param  array<int, array<string, mixed>>  $gallery
     */
    private function galleryIndexByPath(array $gallery, $path): ?int
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        foreach ($gallery as $position => $item) {
            if (($item['path'] ?? null) === $path) {
                return (int) $position;
            }
        }

        return null;
    }

    /**
     * Delete gallery image
     */
    public function deleteGalleryImage(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $request->validate([
            'path' => 'required|string|max:1000',
        ]);

        $gallery = $template->getGalleryImages();
        $resolvedIndex = $this->galleryIndexByPath($gallery, $request->input('path'));

        if ($resolvedIndex === null) {
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ], 404);
        }

        $image = $gallery[$resolvedIndex];

        // Delete file from storage
        if (isset($image['path']) && Storage::disk('public')->exists($image['path'])) {
            Storage::disk('public')->delete($image['path']);
        }

        $template->removeGalleryImage($resolvedIndex);

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus dari gallery',
        ]);
    }

    /**
     * Reorder gallery images
     */
    public function reorderGallery(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $request->validate([
            'order' => 'required|array',
        ]);

        $template->reorderGallery($request->input('order'));

        return response()->json([
            'success' => true,
            'message' => 'Urutan gallery berhasil diubah',
            'gallery' => $template->getGalleryImages(),
        ]);
    }

    /**
     * Update gallery image caption
     */
    public function updateGalleryCaption(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $request->validate([
            'path' => 'required|string|max:1000',
            'caption' => 'nullable|string|max:255',
        ]);

        $gallery = $template->getGalleryImages();
        $resolvedIndex = $this->galleryIndexByPath($gallery, $request->input('path'));

        if ($resolvedIndex === null) {
            return response()->json([
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ], 404);
        }

        $gallery[$resolvedIndex]['caption'] = $request->input('caption');
        $template->update(['gallery' => $gallery]);

        return response()->json([
            'success' => true,
            'message' => 'Caption berhasil diupdate',
        ]);
    }

    /**
     * Delete asset from template
     */
    public function deleteAsset(Request $request)
    {
        $template = WeddingTemplate::where('is_active', true)->first();

        if (! $template) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada template aktif',
            ], 404);
        }

        $request->validate([
            'asset_key' => 'required|string|max:255',
        ]);

        $assetKey = $request->input('asset_key');
        $currentAssets = $template->assets_config ?? [];

        if (isset($currentAssets[$assetKey])) {
            // Delete file from storage
            if (Storage::disk('public')->exists($currentAssets[$assetKey])) {
                Storage::disk('public')->delete($currentAssets[$assetKey]);
            }
            unset($currentAssets[$assetKey]);
            $template->update(['assets_config' => $currentAssets]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Asset berhasil dihapus',
        ]);
    }
}
