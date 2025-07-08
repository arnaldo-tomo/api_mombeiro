<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_phone',
        'message',
        'photo',
        'video',        // Novo campo
        'audio',        // Novo campo
        'location',
        'latitude',
        'longitude',
        'status',
        'is_emergency', // Novo campo
        'metadata'      // Novo campo
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_emergency' => 'boolean',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">Pendente</span>',
            'in_progress' => '<span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full">Em Progresso</span>',
            'resolved' => '<span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Resolvido</span>',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-red-500',
            'in_progress' => 'bg-yellow-500',
            'resolved' => 'bg-green-500',
        };
    }

    public function hasMedia()
    {
        return $this->photo || $this->video || $this->audio;
    }

    public function getMediaCountAttribute()
    {
        $count = 0;
        if ($this->photo) $count++;
        if ($this->video) $count++;
        if ($this->audio) $count++;
        return $count;
    }

    public function getFormattedLocationAttribute()
    {
        return "{$this->latitude}, {$this->longitude}";
    }

    // Scope para alertas de emergência
    public function scopeEmergency($query)
    {
        return $query->where('is_emergency', true);
    }

    // Scope para alertas pendentes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope para alertas do dia
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}