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

        // Broadcast do evento para tempo real
        // broadcast(new \App\Events\NewAlertCreated($alert));

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

        // Broadcast da atualização
        broadcast(new \App\Events\AlertStatusUpdated($alert));

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!',
            'alert' => $alert
        ]);
    }

    public function show(Alert $alert)
    {
        return response()->json($alert);
    }

    public function getAlerts()
    {
        $alerts = Alert::orderBy('created_at', 'desc')->get();
        return response()->json($alerts);
    }
}