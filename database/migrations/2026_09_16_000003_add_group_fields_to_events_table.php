<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Display name of the invitation group (e.g. "Resepsi (Gedung)").
            $table->string('group_name')->nullable()->after('event_key');
            // The one group that the public /invitation link renders.
            $table->boolean('is_default')->default(false)->after('group_name');
            $table->unsignedInteger('sort_order')->default(0)->after('is_default');
        });

        // `event_key` is now the public URL slug. Previously the URL segment was
        // hardcoded (gedung => /p, rumah => /r), so renaming these two groups to
        // their real slug keeps every link that was already shared working.
        DB::table('events')->where('event_key', 'gedung')->update(['event_key' => 'p']);
        DB::table('events')->where('event_key', 'rumah')->update(['event_key' => 'r']);

        DB::table('events')->where('event_key', 'p')->update([
            'group_name' => 'Resepsi (Gedung)',
            'sort_order' => 0,
        ]);

        DB::table('events')->where('event_key', 'r')->update([
            'group_name' => 'Akad / Rumah',
            'sort_order' => 1,
        ]);

        // Exactly one group must feed the public /invitation link.
        if (DB::table('events')->where('is_default', true)->doesntExist()) {
            $firstId = DB::table('events')->orderBy('sort_order')->orderBy('id')->value('id');

            if ($firstId) {
                DB::table('events')->where('id', $firstId)->update(['is_default' => true]);
            }
        }
    }

    public function down(): void
    {
        // Custom groups added after this migration keep their slug in the
        // legacy `gedung`/`rumah` namespace only when they collide.
        DB::table('events')->where('event_key', 'p')->update(['event_key' => 'gedung']);
        DB::table('events')->where('event_key', 'r')->update(['event_key' => 'rumah']);

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['group_name', 'is_default', 'sort_order']);
        });
    }
};
