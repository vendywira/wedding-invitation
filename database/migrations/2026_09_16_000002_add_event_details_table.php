<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Name of the primary ceremony (e.g. "Akad Nikah", "Resepsi").
            $table->string('title')->nullable()->after('event_key');
        });

        // Additional ceremonies shown in the "Wedding Day" section of one
        // invitation. The parent `events` row stays the primary ceremony
        // (it drives the countdown, meta description and guest stats).
        Schema::create('event_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->string('title');
            $table->date('event_date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('finish_time')->nullable();
            $table->string('location')->nullable();
            $table->text('address')->nullable();
            $table->string('google_map_link')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_details');

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
