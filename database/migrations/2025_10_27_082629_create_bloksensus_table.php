<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bloksensus', function (Blueprint $table) {
            $table->integer('no')->autoIncrement();
            $table->string('id_kec', 50);
            $table->string('id_desa', 50);
            $table->string('id_bs', 50);
            $table->string('id_nks', 50)->nullable();
            $table->string('nama', 50)->nullable();

            $table->primary(['no', 'id_kec', 'id_desa', 'id_bs']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bloksensus');
    }
};
