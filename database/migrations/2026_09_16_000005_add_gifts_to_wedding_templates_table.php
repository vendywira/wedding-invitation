<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The gift section used to be hardcoded to BNI + BRI + one "kirim kado"
     * address. It now lives in a JSON list so entries can be added, removed and
     * reordered freely, while the existing values are carried over untouched.
     */
    public function up(): void
    {
        Schema::table('wedding_templates', function (Blueprint $table) {
            $table->json('gifts')->nullable()->after('template_settings');
        });

        foreach (DB::table('wedding_templates')->get() as $template) {
            $assets = json_decode((string) $template->assets_config, true) ?: [];

            DB::table('wedding_templates')->where('id', $template->id)->update([
                'gifts' => json_encode([
                    [
                        'id' => 'gift-1',
                        'type' => 'bank',
                        'label' => 'Bank BNI',
                        'number' => $assets['bank_bni_number'] ?? '557xxxx',
                        'holder' => $assets['bank_bni_name'] ?? 'LINDA',
                        'address' => '',
                        'default_logo' => 'assets/vintage/vendor/bni.png',
                    ],
                    [
                        'id' => 'gift-2',
                        'type' => 'bank',
                        'label' => 'Bank BRI',
                        'number' => $assets['bank_bri_number'] ?? '00500xxx',
                        'holder' => $assets['bank_bri_name'] ?? 'MUKHSIN',
                        'address' => '',
                        'default_logo' => 'assets/vintage/vendor/Bank-Rakyat-Indonesia-BRI.png',
                    ],
                    [
                        'id' => 'gift-3',
                        'type' => 'address',
                        'label' => 'KIRIM KADO',
                        'number' => '',
                        'holder' => $assets['physical_gift_name'] ?? 'LINDA',
                        'address' => $assets['physical_gift_address'] ?? 'Jl. Jaya Mangku, Kutai Kartanegara',
                        'default_logo' => null,
                    ],
                ]),
            ]);

            // Keep an already uploaded bank logo visible under its new key.
            $logoMoves = [
                'bank_bni_logo' => 'gift_logo_gift-1',
                'bank_bri_logo' => 'gift_logo_gift-2',
            ];

            $assetsChanged = false;

            foreach ($logoMoves as $oldKey => $newKey) {
                if (! empty($assets[$oldKey])) {
                    $assets[$newKey] = $assets[$oldKey];
                    $assetsChanged = true;
                }
            }

            if ($assetsChanged) {
                DB::table('wedding_templates')->where('id', $template->id)->update([
                    'assets_config' => json_encode($assets),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('wedding_templates', function (Blueprint $table) {
            $table->dropColumn('gifts');
        });
    }
};
