// database/migrations/2024_01_01_000001_create_guests_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->foreignId('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->integer('guest_attends')->default(0);
            $table->enum('attendance', ['Hadir', 'Tidak Hadir']);
            $table->boolean('is_opened')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guests');
    }
};
