<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Note: SQLite doesn't support changing column types, so we'll skip this for now
        // In production with MySQL/PostgreSQL, you could use:
        // $table->enum('event_type', ['pemberkatan', 'resepsi'])->default('resepsi')->change();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: SQLite doesn't support changing column types, so we'll skip this for now
        // In production with MySQL/PostgreSQL, you could use:
        // $table->enum('event_type', ['pemberkatan', 'resepsi'])->nullable()->change();
    }
};
