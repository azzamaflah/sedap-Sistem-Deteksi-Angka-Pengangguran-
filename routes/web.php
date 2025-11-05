<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WilayahTugasController;
use App\Http\Controllers\DsrtController;
use App\Http\Controllers\RespondenController;
use App\Http\Controllers\UserCustomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConfigQuestController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wilayah Tugas
    Route::resource('wilayahTugas', WilayahTugasController::class);
    Route::get('/api/desa/{id_kec}', [WilayahTugasController::class, 'getDesaByKecamatan'])->name('api.desa');
    Route::get('/wilayahTugas/template/download', [WilayahTugasController::class, 'downloadTemplate'])->name('wilayahTugas.template');
    Route::post('/wilayahTugas/import', [WilayahTugasController::class, 'import'])->name('wilayahTugas.import');
    Route::post('/wilayahTugas/export', [WilayahTugasController::class, 'export'])->name('wilayahTugas.export');

    // DSRT
    Route::resource('dsrt', DsrtController::class);
    Route::get('/api/dsrt/desa/{id_kec}', [DsrtController::class, 'getDesaByKecamatan'])->name('api.dsrt.desa');
    Route::get('/api/dsrt/bloksensus/{id_desa}', [DsrtController::class, 'getBlokSensusByDesa'])->name('api.dsrt.bloksensus');
    Route::get('/api/dsrt/nks/{id_bs}', [DsrtController::class, 'getNksByBlokSensus'])->name('api.dsrt.nks');
    Route::get('/dsrt/template/download', [DsrtController::class, 'downloadTemplate'])->name('dsrt.template');
    Route::post('/dsrt/import', [DsrtController::class, 'import'])->name('dsrt.import');
    Route::post('/dsrt/export', [DsrtController::class, 'export'])->name('dsrt.export');

    // Responden
    Route::resource('responden', RespondenController::class);
    Route::get('/responden/template/download', [RespondenController::class, 'downloadTemplate'])->name('responden.template');
    Route::post('/responden/import', [RespondenController::class, 'import'])->name('responden.import');
    Route::post('/responden/export', [RespondenController::class, 'export'])->name('responden.export');
    // AJAX Routes untuk Responden
    Route::get('/api/responden/desa/{id_kec}', [RespondenController::class, 'getDesaByKecamatan'])->name('api.responden.desa');
    Route::get('/api/responden/bloksensus/{id_desa}', [RespondenController::class, 'getBlokSensusByDesa'])->name('api.responden.bloksensus');
    Route::get('/api/responden/nks/{id_bs}', [RespondenController::class, 'getNksByBlokSensus'])->name('api.responden.nks');
    Route::get('/api/responden/nurt/{id_nks}', [RespondenController::class, 'getNurtByNks'])->name('api.responden.nurt');


    // Helper: Lihat daftar user untuk import
    Route::get('/wilayahTugas/list-users', function () {
        $users = \App\Models\User::select('id', 'name', 'email')->get();
        return response()->json($users);
    })->name('wilayahTugas.listUsers');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::resource('pengguna', UserCustomController::class);

    // Konfigurasi Quest & Rumus (BARU)
    Route::prefix('admin/config')->name('admin.config.')->group(function () {
        // Quest Management
        Route::get('/quest', [ConfigQuestController::class, 'indexQuest'])->name('quest');
        Route::put('/quest/{id}', [ConfigQuestController::class, 'updateQuest'])->name('quest.update');

        // Rumus Management
        Route::get('/rumus', [ConfigQuestController::class, 'indexRumus'])->name('rumus');
        Route::get('/rumus/{id}/edit', [ConfigQuestController::class, 'editRumus'])->name('rumus.edit');
        Route::put('/rumus/{id}', [ConfigQuestController::class, 'updateRumus'])->name('rumus.update');
        Route::post('/rumus/{id}/toggle', [ConfigQuestController::class, 'toggleRumus'])->name('rumus.toggle');


        // Help/Documentation
        Route::get('/help', function () {
            return view('admin.config-rumus.help');
        })->name('help');
    });
});
require __DIR__ . '/auth.php';
