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
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_phone' => 'required|string|max:20',
            'message' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('alerts', 'public');
        }

        $alert = Alert::create([
            'user_name' => $request->user_name,
            'user_phone' => $request->user_phone,
            'message' => $request->message,
            'photo' => $photoPath,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alerta criado com sucesso!',
            'alert' => $alert
        ], 201);
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
            // Deletar foto se existir
            if ($alert->photo && Storage::disk('public')->exists($alert->photo)) {
                Storage::disk('public')->delete($alert->photo);
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
}