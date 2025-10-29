<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responden', function (Blueprint $table) {
            $table->integer('no')->autoIncrement();
            $table->string('id_kec', 50);
            $table->string('id_desa', 50);
            $table->string('id_bs', 50);
            $table->string('id_nks', 50)->nullable();
            $table->string('id_nurt', 50);
            $table->string('nama_sample', 50);
            $table->string('r7_1', 255)->nullable();
            $table->string('r7_2', 255)->nullable();
            $table->string('r7_3', 255)->nullable();
            $table->string('r8_1', 255)->nullable();
            $table->string('r9_1', 255)->nullable();
            $table->string('r9_3', 255)->nullable();
            $table->string('r20_1', 255)->nullable();
            $table->string('r20_2', 255)->nullable();
            $table->string('r20_4', 255)->nullable();
            $table->string('bekerja', 50)->nullable();
            $table->string('pengangguran', 50)->nullable();

            $table->primary(['no', 'id_kec', 'id_desa', 'id_bs', 'id_nurt', 'nama_sample']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responden');
    }
};
