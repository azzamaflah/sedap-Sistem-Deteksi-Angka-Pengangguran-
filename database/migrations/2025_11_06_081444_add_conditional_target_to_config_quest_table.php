<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Menambahkan kolom 'conditional_target' ke tabel 'config_quest'
        Schema::table('config_quest', function (Blueprint $table) {
            // Tipe JSON digunakan karena di model ConfigQuest di-cast sebagai array.
            // Kolom ini akan menyimpan aturan bersyarat (conditional logic)
            $table->json('conditional_target')->nullable()->after('options');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Menghapus kolom 'conditional_target' jika migrasi di-rollback
        Schema::table('config_quest', function (Blueprint $table) {
            $table->dropColumn('conditional_target');
        });
    }
};