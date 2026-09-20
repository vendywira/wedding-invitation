<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tamu yang membuka link umum (tanpa `?to=`) tidak punya baris di tabel
     * `guests`, sehingga `guest_id` yang dikirim form kosong. Kolom ini awalnya
     * NOT NULL + foreign key, jadi ucapan dari link umum gagal disimpan dengan
     * error foreign key / not null (`guest_id`).
     *
     * `change()` sekadar melonggarkan kolom menjadi nullable — foreign key-nya
     * tetap ada, jadi ucapan yang memang punya tamu tetap terhubung.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('guest_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('guest_id')->nullable(false)->change();
        });
    }
};
