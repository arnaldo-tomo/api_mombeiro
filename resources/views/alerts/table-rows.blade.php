@forelse($alerts as $alert)
<tr class="hover:bg-gray-50 transition-all duration-200 {{ $alert->status === 'pending' ? 'bg-red-50 border-l-4 border-red-500' : '' }}" data-alert-id="{{ $alert->id }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-14 w-14">
                <div class="h-14 w-14 rounded-full bg-gradient-to-r from-red-400 to-red-600 flex items-center justify-center shadow-lg {{ $alert->status === 'pending' ? 'animate-pulse' : '' }}">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <div class="text-sm font-bold text-gray-900">{{ $alert->user_name }}</div>
                <div class="text-sm text-gray-500 flex items-center mt-1">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $alert->user_phone }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900 max-w-xs">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                <span class="font-medium">{{ $alert->location }}</span>
            </div>
            <div class="text-xs text-gray-500 bg-gray-100 rounded-md px-2 py-1 inline-block">
                📍 {{ $alert->latitude }}, {{ $alert->longitude }}
            </div>
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="text-sm text-gray-900 max-w-xs">
            <p class="mb-2">{{ $alert->message ?? '💬 Sem descrição adicional' }}</p>
            @if($alert->photo)
            <div class="mt-3">
                <img src="{{ asset('storage/' . $alert->photo) }}" alt="Foto do alerta" class="w-24 h-24 rounded-xl object-cover cursor-pointer shadow-lg hover:shadow-xl transition-all transform hover:scale-105" onclick="openImageModal('{{ asset('storage/' . $alert->photo) }}')">
            </div>
            @endif
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex flex-col space-y-2">
            <select class="status-select rounded-xl border-2 border-gray-200 text-sm shadow-lg focus:border-indigo-500 focus:ring-indigo-500 transition-all" data-alert-id="{{ $alert->id }}">
                <option value="pending" {{ $alert->status == 'pending' ? 'selected' : '' }}>🔴 Crítico</option>
                <option value="in_progress" {{ $alert->status == 'in_progress' ? 'selected' : '' }}>🟡 Atendendo</option>
                <option value="resolved" {{ $alert->status == 'resolved' ? 'selected' : '' }}>🟢 Resolvido</option>
            </select>
            @if($alert->status === 'pending')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">
                🚨 URGENTE
            </span>
            @elseif($alert->status === 'in_progress')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                ⚡ EM CAMPO
            </span>
            @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                ✅ FINALIZADO
            </span>
            @endif
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        <div class="flex flex-col">
            <div class="font-bold text-gray-900">📅 {{ $alert->created_at->format('d/m/Y') }}</div>
            <div class="text-gray-600">⏰ {{ $alert->created_at->format('H:i:s') }}</div>
            <div class="text-xs text-gray-500 mt-1">
                {{ $alert->created_at->diffForHumans() }}
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <div class="flex flex-col space-y-2">
            <div class="flex items-center space-x-2">
                <button onclick="viewOnMap({{ $alert->latitude }}, {{ $alert->longitude }})" class="inline-flex items-center px-3 py-2 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105 shadow-md">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Mapa
                </button>
                <button onclick="callEmergency('{{ $alert->user_phone }}')" class="inline-flex items-center px-3 py-2 bg-green-500 text-white text-xs font-medium rounded-lg hover:bg-green-600 transition-all transform hover:scale-105 shadow-md">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    Ligar
                </button>
            </div>
            <div class="flex items-center space-x-2">
                @if($alert->status === 'pending')
                <button onclick="updateAlertStatus({{ $alert->id }}, 'in_progress')" class="inline-flex items-center px-3 py-2 bg-yellow-500 text-white text-xs font-medium rounded-lg hover:bg-yellow-600 transition-all transform hover:scale-105 shadow-md">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Atender
                </button>
                @elseif($alert->status === 'in_progress')
                <button onclick="updateAlertStatus({{ $alert->id }}, 'resolved')" class="inline-flex items-center px-3 py-2 bg-green-500 text-white text-xs font-medium rounded-lg hover:bg-green-600 transition-all transform hover:scale-105 shadow-md">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Finalizar
                </button>
                @else
                <span class="inline-flex items-center px-3 py-2 bg-gray-300 text-gray-700 text-xs font-medium rounded-lg">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Concluído
                </span>
                @endif
                <button onclick="deleteAlert({{ $alert->id }})" class="inline-flex items-center px-3 py-2 bg-red-500 text-white text-xs font-medium rounded-lg hover:bg-red-600 transition-all transform hover:scale-105 shadow-md">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Excluir
                </button>
            </div>
        </div>
    </td>
</tr>
@empty
<tr id="empty-state">
    <td colspan="6" class="px-6 py-16 text-center">
        <div class="flex flex-col items-center">
            <div class="w-24 h-24 bg-gradient-to-r from-gray-200 to-gray-300 rounded-full flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">🎉 Tudo tranquilo!</h3>
            <p class="text-gray-500 text-lg">Nenhum alerta de emergência no momento.</p>
            <p class="text-gray-400 text-sm mt-2">Os alertas aparecerão aqui quando chegarem.</p>
        </div>
    </td>
</tr>
@endforelse

<script>
// Global functions for table interactions
window.viewOnMap = function(lat, lng) {
    window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
};

window.callEmergency = function(phone) {
    if (confirm(`Ligar para ${phone}?`)) {
        window.open(`tel:${phone}`, '_self');
    }
};

window.updateAlertStatus = function(alertId, status) {
    fetch(`/alerts/${alertId}/status`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('✅ Status atualizado com sucesso!', 'success');
            // Refresh page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification('❌ Erro ao atualizar status', 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showNotification('❌ Erro de conexão', 'error');
    });
};

window.deleteAlert = function(alertId) {
    if (confirm('⚠️ Tem certeza que deseja excluir este alerta?\n\nEsta ação não pode ser desfeita.')) {
        fetch(`/alerts/${alertId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('🗑️ Alerta excluído com sucesso!', 'success');
                // Remove row with animation
                const row = document.querySelector(`tr[data-alert-id="${alertId}"]`);
                if (row) {
                    row.style.transform = 'translateX(-100%)';
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        // Check if table is empty
                        const tbody = document.getElementById('alerts-table-body');
                        if (tbody && tbody.children.length === 0) {
                            window.location.reload();
                        }
                    }, 300);
                }
            } else {
                showNotification('❌ Erro ao excluir alerta', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('❌ Erro de conexão', 'error');
        });
    }
};

// Status change handler
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('status-select')) {
        const alertId = e.target.dataset.alertId;
        const newStatus = e.target.value;
        updateAlertStatus(alertId, newStatus);
    }
});
</script>