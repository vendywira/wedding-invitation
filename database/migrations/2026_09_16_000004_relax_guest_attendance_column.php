<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * `attendance` was created as an ENUM('Hadir', 'Tidak Hadir') and only the
     * MySQL driver ever widened it to include 'Belum Konfirmasi'. On SQLite the
     * CHECK constraint stayed behind, so creating a guest without a status — or
     * setting one back to "Belum Konfirmasi" — failed with a constraint error.
     *
     * A plain string column with a default behaves the same on every driver;
     * the allowed values are validated by the application.
     */
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('attendance')->default('Belum Konfirmasi')->change();
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->string('attendance')->default('Hadir')->change();
        });
    }
};
