<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desa', function (Blueprint $table) {
            $table->integer('no')->autoIncrement();
            $table->string('id_desa', 50);
            $table->string('id_kec', 50);
            $table->string('nama_desa', 50)->nullable();
            $table->string('nama_kec', 50)->nullable();

            $table->primary(['no', 'id_desa', 'id_kec']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};
