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
            $table->string('guest_code')->unique();
            $table->enum('category', ['utama', 'denpasar']);
            $table->string('location');
            $table->integer('max_guests')->default(1);
            $table->boolean('is_opened')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guests');
    }
};
