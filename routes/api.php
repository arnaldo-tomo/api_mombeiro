<?php

use App\Http\Controllers\AlertController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // Rotas para o App Mobile
    Route::post('/alerts', [AlertController::class, 'store']);
    Route::get('/alerts', [AlertController::class, 'getAlerts']);
    Route::get('/alerts/{alert}', [AlertController::class, 'show']);

    // Teste de conectividade
    Route::get('/test', function () {
        return response()->json([
            'message' => 'API funcionando!',
            'timestamp' => now()
        ]);
    });
});