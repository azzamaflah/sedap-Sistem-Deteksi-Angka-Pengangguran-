<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bloksensus', function (Blueprint $table) {
            $table->year('tahun')->default(date('Y'))->after('nama');
            $table->index('tahun'); // Index untuk performa query
        });
    }

    public function down()
    {
        Schema::table('bloksensus', function (Blueprint $table) {
            $table->dropColumn('tahun');
        });
    }
};
