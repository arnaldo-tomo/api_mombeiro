<?php
use App\Http\Controllers\AlertController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard Principal
    Route::get('/dashboard', [AlertController::class, 'index'])->name('dashboard');
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');

    // Gestão de Alertas
    Route::patch('/alerts/{alert}/status', [AlertController::class, 'updateStatus'])->name('alerts.update-status');
    Route::get('/alerts/{alert}', [AlertController::class, 'show'])->name('alerts.show');
    Route::delete('/alerts/{alert}', [AlertController::class, 'destroy'])->name('alerts.destroy');



    // Mapa de Alertas
    Route::get('/map', [AlertController::class, 'mapView'])->name('alerts.map');



    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Arquivos de Mídia
    Route::get('/alerts/{alert}/media/{type}', [AlertController::class, 'getMediaFile'])
         ->where('type', 'photo|video|audio')
         ->name('alerts.media');
});

require __DIR__.'/auth.php';