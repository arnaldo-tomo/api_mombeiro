<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
            <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            {{ __('Painel de Alertas de Incêndio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 mr-4">
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Alertas Pendentes</p>
                            <p class="text-2xl font-semibold text-gray-900" id="pending-count">{{ $alerts->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 mr-4">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Em Progresso</p>
                            <p class="text-2xl font-semibold text-gray-900" id="progress-count">{{ $alerts->where('status', 'in_progress')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 mr-4">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Resolvidos</p>
                            <p class="text-2xl font-semibold text-gray-900" id="resolved-count">{{ $alerts->where('status', 'resolved')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Alertas Recentes</h3>
                        <div class="flex items-center space-x-2">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-600">Online</span>
                            </div>
                            <button id="sound-toggle" class="p-2 rounded-md bg-gray-100 hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localização</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensagem</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="alerts-table-body" class="bg-white divide-y divide-gray-200">
                                @forelse($alerts as $alert)
                                <tr class="hover:bg-gray-50 transition-colors" data-alert-id="{{ $alert->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                                    <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $alert->user_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $alert->user_phone }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $alert->location }}</div>
                                        <div class="text-sm text-gray-500">{{ $alert->latitude }}, {{ $alert->longitude }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $alert->message ?? 'Sem mensagem' }}</div>
                                        @if($alert->photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $alert->photo) }}" alt="Foto do alerta" class="w-16 h-16 rounded-lg object-cover cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $alert->photo) }}')">
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <select class="status-select rounded-md border-gray-300 text-sm" data-alert-id="{{ $alert->id }}">
                                            <option value="pending" {{ $alert->status == 'pending' ? 'selected' : '' }}>Pendente</option>
                                            <option value="in_progress" {{ $alert->status == 'in_progress' ? 'selected' : '' }}>Em Progresso</option>
                                            <option value="resolved" {{ $alert->status == 'resolved' ? 'selected' : '' }}>Resolvido</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $alert->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="viewOnMap({{ $alert->latitude }}, {{ $alert->longitude }})" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Nenhum alerta encontrado</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg max-w-3xl max-h-3xl">
            <img id="modalImage" src="" alt="Foto do alerta" class="max-w-full max-h-full">
            <button onclick="closeImageModal()" class="mt-4 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Fechar
            </button>
        </div>
    </div>
</x-app-layout>
        // Configuração do Pusher (substitua pela sua chave)
        const pusher = new Pusher('your-pusher-key', {
            cluster: 'your-cluster'
        });

        // Som de notificação
        const notificationSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFApGn+DyvmrcBzCN1e/LdyMFoOPdKgYjqO7FfjkIKITN7NuPQAoUXrTp66hVFA==');

        let soundEnabled = true;

        // Channel para escutar os alertas
        const channel = pusher.subscribe('alerts');

        // Escuta novos alertas
        channel.bind('App\\Events\\NewAlertCreated', function(data) {
            if (soundEnabled) {
                notificationSound.play();
            }
            addNewAlertToTable(data.alert);
            updateStats();
            showNotification('Novo alerta recebido!', 'success');
        });

        // Escuta atualizações de status
        channel.bind('App\\Events\\AlertStatusUpdated', function(data) {
            updateAlertInTable(data.alert);
            updateStats();
            showNotification('Status do alerta atualizado!', 'info');
        });

        // Toggle do som
        document.getElementById('sound-toggle').addEventListener('click', function() {
            soundEnabled = !soundEnabled;
            this.style.opacity = soundEnabled ? '1' : '0.5';
        });

        // Atualização de status
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('status-select')) {
                const alertId = e.target.dataset.alertId;
                const newStatus = e.target.value;

                fetch(`/alerts/${alertId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Status atualizado com sucesso!', 'success');
                    }
                });
            }
        });

        // Funções auxiliares
        function addNewAlertToTable(alert) {
            const tableBody = document.getElementById('alerts-table-body');
            const newRow = createAlertRow(alert);
            tableBody.insertAdjacentHTML('afterbegin', newRow);
        }

        function updateAlertInTable(alert) {
            const row = document.querySelector(`tr[data-alert-id="${alert.id}"]`);
            if (row) {
                const select = row.querySelector('.status-select');
                select.value = alert.status;
            }
        }

        function createAlertRow(alert) {
            const photoHtml = alert.photo ?
                `<div class="mt-2">
                    <img src="/storage/${alert.photo}" alt="Foto do alerta" class="w-16 h-16 rounded-lg object-cover cursor-pointer" onclick="openImageModal('/storage/${alert.photo}')">
                </div>` : '';

            return `
                <tr class="hover:bg-gray-50 transition-colors animate-pulse" data-alert-id="${alert.id}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shr