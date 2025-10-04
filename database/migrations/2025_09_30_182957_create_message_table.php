<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guest_code');
            $table->integer('guest_count');
            $table->enum('attendance', ['Hadir', 'Tidak Hadir']);
            $table->text('message')->nullable();
            $table->string('category');
            $table->string('location');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
