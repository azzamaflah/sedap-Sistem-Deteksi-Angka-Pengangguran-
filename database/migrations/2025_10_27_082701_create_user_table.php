<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('no')->autoIncrement();
            $table->string('id_user', 50);
            $table->string('nama', 50)->nullable();
            $table->string('user_name', 50)->nullable();
            $table->string('password', 255)->nullable(); // Ubah ke 255 untuk hash
            $table->string('email', 50)->nullable();

            $table->primary(['no', 'id_user']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
