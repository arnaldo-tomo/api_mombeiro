<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::orderBy('created_at', 'desc')->get();
        return view('alerts.index', compact('alerts'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Recebendo requisição para /api/alerts', ['data' => $request->all()]);

            $request->validate([
                'user_name' => 'required|string|max:255',
                'user_phone' => 'required|string|max:20',
                'message' => 'nullable|string|max:1000',
                'photo' => 'nullable|image|max:10240', // 10MB max
                'video' => 'nullable', // 50MB max
                'audio' => 'nullable', // 10MB max
                'location' => 'required|string',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'is_emergency' => 'boolean',
                'metadata' => 'nullable|json'
            ]);

            // Handle file uploads
            $photoPath = null;
            $videoPath = null;
            $audioPath = null;

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('alerts/photos', 'public');
                \Log::info('Foto salva em:', ['path' => $photoPath]);
            }

            if ($request->hasFile('video')) {
                $videoPath = $request->file('video')->store('alerts/videos', 'public');
                \Log::info('Vídeo salvo em:', ['path' => $videoPath]);
            }

            if ($request->hasFile('audio')) {
                $audioPath = $request->file('audio')->store('alerts/audios', 'public');
                \Log::info('Áudio salvo em:', ['path' => $audioPath]);
            }

            $alert = Alert::create([
                'user_name' => $request->user_name,
                'user_phone' => $request->user_phone,
                'message' => $request->message,
                'photo' => $photoPath,
                'video' => $videoPath,
                'audio' => $audioPath,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_emergency' => $request->boolean('is_emergency', false),
                'metadata' => $request->metadata ? json_decode($request->metadata, true) : null,
            ]);

            \Log::info('Alerta criado com sucesso:', ['alert_id' => $alert->id]);

            return response()->json([
                'success' => true,
                'message' => 'Alerta criado com sucesso!',
                'alert' => $alert
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar alerta:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar alerta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, Alert $alert)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved'
        ]);

        $alert->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!',
            'alert' => $alert
        ]);
    }

    public function destroy(Alert $alert)
    {
        try {
            // Deletar todos os arquivos de mídia se existirem
            if ($alert->photo && Storage::disk('public')->exists($alert->photo)) {
                Storage::disk('public')->delete($alert->photo);
            }

            if ($alert->video && Storage::disk('public')->exists($alert->video)) {
                Storage::disk('public')->delete($alert->video);
            }

            if ($alert->audio && Storage::disk('public')->exists($alert->audio)) {
                Storage::disk('public')->delete($alert->audio);
            }

            $alert->delete();

            return response()->json([
                'success' => true,
                'message' => 'Alerta deletado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar alerta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAlerts()
    {
        $alerts = Alert::orderBy('created_at', 'desc')->get();
        return response()->json($alerts);
    }

    public function getStatistics()
    {
        $today = today();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        $stats = [
            'total' => Alert::count(),
            'pending' => Alert::where('status', 'pending')->count(),
            'in_progress' => Alert::where('status', 'in_progress')->count(),
            'resolved' => Alert::where('status', 'resolved')->count(),
            'emergency' => Alert::where('is_emergency', true)->count(),
            'today' => Alert::whereDate('created_at', $today)->count(),
            'this_week' => Alert::where('created_at', '>=', $thisWeek)->count(),
            'this_month' => Alert::where('created_at', '>=', $thisMonth)->count(),
            'with_media' => Alert::where(function ($query) {
                $query->whereNotNull('photo')
                    ->orWhereNotNull('video')
                    ->orWhereNotNull('audio');
            })->count(),
        ];

        return response()->json($stats);
    }

    public function getMediaFile(Alert $alert, $type)
    {
        if (!in_array($type, ['photo', 'video', 'audio'])) {
            abort(404);
        }

        $filePath = $alert->{$type};

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        return Storage::disk('public')->response($filePath);
    }

    // Métodos adicionais para API

    public function getByStatus($status)
    {
        if (!in_array($status, ['pending', 'in_progress', 'resolved'])) {
            return response()->json(['error' => 'Status inválido'], 400);
        }

        $alerts = Alert::where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($alerts);
    }

    public function getEmergencyAlerts()
    {
        $alerts = Alert::emergency()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($alerts);
    }

    public function getTodayAlerts()
    {
        $alerts = Alert::today()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($alerts);
    }

    public function getUserAlerts($phone)
    {
        $alerts = Alert::where('user_phone', $phone)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($alerts);
    }

    public function getNearbyAlerts($lat, $lng, $radius = 10)
    {
        // Buscar alertas em um raio específico (em km)
        $alerts = Alert::selectRaw("
                *, (
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )
                ) AS distance
            ", [$lat, $lng, $lat])
            ->having('distance', '<', $radius)
            ->orderBy('distance')
            ->get();

        return response()->json($alerts);
    }

    public function mapView()
    {
        $alerts = Alert::orderBy('created_at', 'desc')->get();
        return view('alerts.map', compact('alerts'));
    }
}
