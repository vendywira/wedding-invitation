<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE guests MODIFY COLUMN attendance ENUM('Hadir', 'Tidak Hadir', 'Belum Konfirmasi') DEFAULT 'Belum Konfirmasi'");
        }

        DB::table('guests')
            ->whereNull('attendance')
            ->update(['attendance' => 'Belum Konfirmasi']);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE guests MODIFY COLUMN attendance ENUM('Hadir', 'Tidak Hadir') DEFAULT 'Hadir'");
        }

        DB::table('guests')
            ->where('attendance', 'Belum Konfirmasi')
            ->update(['attendance' => 'Hadir']);
    }
};
