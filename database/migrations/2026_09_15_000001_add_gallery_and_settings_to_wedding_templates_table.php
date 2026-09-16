<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_templates', function (Blueprint $table) {
            $table->json('gallery')->nullable()->after('assets_config');
            $table->json('template_settings')->nullable()->after('gallery');
        });
    }

    public function down(): void
    {
        Schema::table('wedding_templates', function (Blueprint $table) {
            $table->dropColumn(['gallery', 'template_settings']);
        });
    }
};
