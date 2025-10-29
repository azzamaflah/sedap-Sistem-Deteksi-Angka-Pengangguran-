<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kec', function (Blueprint $table) {
            $table->integer('no')->autoIncrement();
            $table->string('id_kec', 50);
            $table->string('nama_kec', 50)->nullable();

            $table->primary(['no', 'id_kec']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kec');
    }
};
