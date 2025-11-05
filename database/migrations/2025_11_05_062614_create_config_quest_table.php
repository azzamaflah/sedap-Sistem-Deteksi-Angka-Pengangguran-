<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('config_quest', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // r7_1, r7_2, dll
            $table->string('label'); // Label pertanyaan
            $table->text('description')->nullable();
            $table->string('type')->default('radio'); // radio, text, textarea
            $table->json('options')->nullable(); // ['Ya', 'Tidak']
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('config_rumus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_rumus')->unique();
            $table->text('deskripsi')->nullable();
            $table->text('kondisi_bekerja'); // JSON kondisi
            $table->text('kondisi_pengangguran'); // JSON kondisi
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed data default
        DB::table('config_quest')->insert([
            ['key' => 'r7_1', 'label' => 'Quest 1: Apakah bekerja minggu lalu?', 'type' => 'radio', 'options' => json_encode(['Ya', 'Tidak']), 'order' => 1, 'is_active' => true],
            ['key' => 'r7_2', 'label' => 'Quest 2: Apakah memiliki pekerjaan tetap?', 'type' => 'radio', 'options' => json_encode(['Ya', 'Tidak']), 'order' => 2, 'is_active' => true],
            ['key' => 'r7_3', 'label' => 'Quest 3: Apakah punya usaha/bisnis?', 'type' => 'radio', 'options' => json_encode(['Ya', 'Tidak']), 'order' => 3, 'is_active' => true],
            ['key' => 'r8_1', 'label' => 'Quest 4: Apakah membantu usaha keluarga?', 'type' => 'radio', 'options' => json_encode(['Ya', 'Tidak']), 'order' => 4, 'is_active' => true],
            ['key' => 'r9_1', 'label' => 'Quest 5: Lapangan usaha/bidang pekerjaan', 'type' => 'text', 'options' => null, 'order' => 5, 'is_active' => true],
            ['key' => 'r9_3', 'label' => 'Quest 6: Jabatan/posisi pekerjaan', 'type' => 'text', 'options' => null, 'order' => 6, 'is_active' => true],
            ['key' => 'r20_1', 'label' => 'Quest 7: Mencari pekerjaan tambahan?', 'type' => 'radio', 'options' => json_encode(['Ya', 'Tidak']), 'order' => 7, 'is_active' => true],
            ['key' => 'r20_2', 'label' => 'Quest 8: Bersedia menerima pekerjaan tambahan?', 'type' => 'radio', 'options' => json_encode(['Ya', 'Tidak']), 'order' => 8, 'is_active' => true],
            ['key' => 'r20_4', 'label' => 'Quest 9: Alasan mencari pekerjaan tambahan', 'type' => 'textarea', 'options' => null, 'order' => 9, 'is_active' => true],
        ]);

        DB::table('config_rumus')->insert([
            'nama_rumus' => 'Rumus Status Ketenagakerjaan V1',
            'deskripsi' => 'Rumus default untuk menentukan status Bekerja/Pengangguran',
            'kondisi_bekerja' => json_encode([
                'operator' => 'OR',
                'conditions' => [
                    ['field' => 'r7_1', 'value' => 'Ya'],
                    ['field' => 'r7_2', 'value' => 'Ya'],
                    ['field' => 'r7_3', 'value' => 'Ya'],
                    ['field' => 'r8_1', 'value' => 'Ya'],
                ]
            ]),
            'kondisi_pengangguran' => json_encode([
                'operator' => 'AND',
                'conditions' => [
                    ['field' => 'r7_1', 'value' => 'Tidak'],
                    ['field' => 'r7_2', 'value' => 'Tidak'],
                    ['field' => 'r7_3', 'value' => 'Tidak'],
                    ['field' => 'r8_1', 'value' => 'Tidak'],
                ]
            ]),
            'is_active' => true,
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('config_rumus');
        Schema::dropIfExists('config_quest');
    }
};
