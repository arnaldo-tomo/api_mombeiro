<?php
use App\Http\Controllers\AlertController;
use Illuminate\Support\Facades\Route;
Route::middleware('api')->group(function () {
    Route::post('/alerts', [AlertController::class, 'store']);
    Route::get('/alerts', [AlertController::class, 'getAlerts']);
    Route::get('/alerts/{alert}', [AlertController::class, 'show']);

// Endpoints específicos
Route::get('/alerts-list', [AlertController::class, 'getAlerts']);
Route::get('/statistics', [AlertController::class, 'getStatistics']);

// Filtros e busca
Route::get('/alerts/status/{status}', [AlertController::class, 'getByStatus']);
Route::get('/alerts/emergency', [AlertController::class, 'getEmergencyAlerts']);
Route::get('/alerts/today', [AlertController::class, 'getTodayAlerts']);
Route::get('/alerts/user/{phone}', [AlertController::class, 'getUserAlerts']);

// Localização e mapas
Route::get('/alerts/near/{lat}/{lng}/{radius?}', [AlertController::class, 'getNearbyAlerts']);

// Teste de conectividade
Route::get('/test', function () {
    return response()->json([
        'message' => 'API funcionando!',
        'timestamp' => now(),
        'version' => '2.0.0'
    ]);
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'database' => 'connected',
        'storage' => 'available',
        'timestamp' => now()
    ]);
});
});