<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 mr-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Alertas Pendentes</p>
                            <p class="text-3xl font-bold text-gray-900" id="pending-count">{{ $alerts->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 mr-4">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Em Progresso</p>
                            <p class="text-3xl font-bold text-gray-900" id="progress-count">{{ $alerts->where('status', 'in_progress')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 mr-4">
                            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 uppercase">Resolvidos</p>
                            <p class="text-3xl font-bold text-gray-900" id="resolved-count">{{ $alerts->where('status', 'resolved')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Control Panel -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-8">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Controle do Sistema</h3>
                        <div class="flex items-center space-x-4">
                            <!-- Status de Conexão -->
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                <span class="text-sm text-gray-600">Sistema Online</span>
                            </div>
                            <!-- Última Atualização -->
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span id="last-update">{{ now()->format('H:i:s') }}</span>
                            </div>
                            <!-- Toggle Som -->
                            <button id="sound-toggle" class="flex items-center px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                </svg>
                                <span id="sound-status">Som ON</span>
                            </button>
                            <!-- Atualizar Manual -->
                            <button id="refresh-btn" class="flex items-center px-3 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Atualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts Table -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Alertas de Emergência</h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Total: </span>
                            <span class="text-sm font-bold text-gray-900" id="total-alerts">{{ $alerts->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Usuário
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        Localização
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        Descrição
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Status
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Data/Hora
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                        Ações
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="alerts-table-body" class="bg-white divide-y divide-gray-200">
                            @forelse($alerts as $alert)
                            <tr class="hover:bg-gray-50 transition-colors {{ $alert->status === 'pending' ? 'bg-red-50' : '' }}" data-alert-id="{{ $alert->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-r from-red-400 to-red-600 flex items-center justify-center shadow-lg">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $alert->user_name }}</div>
                                            <div class="text-sm text-gray-500 flex items-center">
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
                                        <div class="flex items-center mb-1">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            {{ $alert->location }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $alert->latitude }}, {{ $alert->longitude }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs">
                                        {{ $alert->message ?? 'Sem descrição' }}
                                    </div>
                                    @if($alert->photo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $alert->photo) }}" alt="Foto do alerta" class="w-20 h-20 rounded-lg object-cover cursor-pointer shadow-md hover:shadow-lg transition-shadow" onclick="openImageModal('{{ asset('storage/' . $alert->photo) }}')">
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select class="status-select rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" data-alert-id="{{ $alert->id }}">
                                        <option value="pending" {{ $alert->status == 'pending' ? 'selected' : '' }}>🔴 Pendente</option>
                                        <option value="in_progress" {{ $alert->status == 'in_progress' ? 'selected' : '' }}>🟡 Em Progresso</option>
                                        <option value="resolved" {{ $alert->status == 'resolved' ? 'selected' : '' }}>🟢 Resolvido</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="font-medium">{{ $alert->created_at->format('d/m/Y') }}</div>
                                    <div class="text-gray-500">{{ $alert->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="viewOnMap({{ $alert->latitude }}, {{ $alert->longitude }})" class="text-blue-600 hover:text-blue-900 p-2 rounded-md hover:bg-blue-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </button>
                                        <button onclick="deleteAlert({{ $alert->id }})" class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="empty-state">
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum alerta encontrado</h3>
                                        <p class="text-gray-500">Quando houver alertas de emergência, eles aparecerão aqui.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl max-h-full overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Foto do Alerta</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <img id="modalImage" src="" alt="Foto do alerta" class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <span id="notification-text">Novo alerta recebido!</span>
        </div>
    </div>

    <script>
        // Variáveis globais
        let soundEnabled = true;
        let lastAlertCount = {{ $alerts->count() }};
        let currentAlerts = [];
        let isPolling = false;
        let pollingInterval = null;

        // Som de notificação
        const notificationSound = new Audio('{{ asset('som.mp3') }}');

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            initializePolling();
            setupEventListeners();
            loadInitialAlerts();
        });

        // Configurar event listeners
        function setupEventListeners() {
            // Toggle do som
            document.getElementById('sound-toggle').addEventListener('click', function() {
                soundEnabled = !soundEnabled;
                const button = this;
                const statusText = document.getElementById('sound-status');

                if (soundEnabled) {
                    button.className = button.className.replace('bg-gray-500', 'bg-blue-500');
                    statusText.textContent = 'Som ON';
                } else {
                    button.className = button.className.replace('bg-blue-500', 'bg-gray-500');
                    statusText.textContent = 'Som OFF';
                }
            });

            // Botão de refresh manual
            document.getElementById('refresh-btn').addEventListener('click', function() {
                checkForNewAlerts();
            });

            // Mudança de status
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('status-select')) {
                    const alertId = e.target.dataset.alertId;
                    const newStatus = e.target.value;
                    updateAlertStatus(alertId, newStatus);
                }
            });
        }

        // Inicializar polling
        function initializePolling() {
            if (isPolling) return;

            isPolling = true;
            // Verificar novos alertas a cada 5 segundos
            pollingInterval = setInterval(checkForNewAlerts, 5000);
        }

        // Parar polling
        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                isPolling = false;
            }
        }

        // Carregar alertas iniciais
        function loadInitialAlerts() {
            fetch('/api/alerts')
                .then(response => response.json())
                .then(data => {
                    currentAlerts = data;
                    updateStats();
                })
                .catch(error => console.error('Erro ao carregar alertas:', error));
        }

        // Verificar novos alertas
        function checkForNewAlerts() {
            fetch('/api/alerts')
                .then(response => response.json())
                .then(data => {
                    const newAlerts = data.filter(alert =>
                        !currentAlerts.some(existing => existing.id === alert.id)
                    );

                    if (newAlerts.length > 0) {
                        // Tocar som se habilitado
                        if (soundEnabled) {
                            notificationSound.play().catch(e => console.log('Erro ao tocar som:', e));
                        }

                        // Mostrar notificação
                        showNotification(`${newAlerts.length} novo(s) alerta(s) recebido(s)!`, 'success');

                        // Adicionar novos alertas à tabela
                        newAlerts.forEach(alert => {
                            addNewAlertToTable(alert);
                        });

                        // Atualizar array atual
                        currentAlerts = data;
                    }

                    // Verificar mudanças de status
                    data.forEach(alert => {
                        const existing = currentAlerts.find(existing => existing.id === alert.id);
                        if (existing && existing.status !== alert.status) {
                            updateAlertRowStatus(alert.id, alert.status);
                        }
                    });

                    // Atualizar estatísticas
                    updateStats();
                    updateLastUpdateTime();
                })
                .catch(error => {
                    console.error('Erro ao verificar novos alertas:', error);
                    showNotification('Erro ao verificar alertas', 'error');
                });
        }

        // Adicionar novo alerta à tabela
        function addNewAlertToTable(alert) {
            const tableBody = document.getElementById('alerts-table-body');
            const emptyState = document.getElementById('empty-state');

            // Remover estado vazio se existir
            if (emptyState) {
                emptyState.remove();
            }

            const newRow = createAlertRow(alert);
            tableBody.insertAdjacentHTML('afterbegin', newRow);

            // Adicionar animação de destaque
            const row = document.querySelector(`tr[data-alert-id="${alert.id}"]`);
            if (row) {
                row.classList.add('animate-pulse', 'bg-yellow-50');
                setTimeout(() => {
                    row.classList.remove('animate-pulse', 'bg-yellow-50');
                    if (alert.status === 'pending') {
                        row.classList.add('bg-red-50');
                    }
                }, 3000);
            }
        }

        // Criar linha de alerta
        function createAlertRow(alert) {
            const photoHtml = alert.photo ?
                `<div class="mt-2">
                    <img src="/storage/${alert.photo}" alt="Foto do alerta" class="w-20 h-20 rounded-lg object-cover cursor-pointer shadow-md hover:shadow-lg transition-shadow" onclick="openImageModal('/storage/${alert.photo}')">
                </div>` : '';

            const createdAt = new Date(alert.created_at);
            const formattedDate = createdAt.toLocaleDateString('pt-BR');
            const formattedTime = createdAt.toLocaleTimeString('pt-BR');

            return `
                <tr class="hover:bg-gray-50 transition-colors ${alert.status === 'pending' ? 'bg-red-50' : ''}" data-alert-id="${alert.id}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-r from-red-400 to-red-600 flex items-center justify-center shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${alert.user_name}</div>
                                <div class="text-sm text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    ${alert.user_phone}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 max-w-xs">
                            <div class="flex items-center mb-1">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                ${alert.location}
                            </div>
                            <div class="text-xs text-gray-500">${alert.latitude}, ${alert.longitude}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs">
                            ${alert.message || 'Sem descrição'}
                        </div>
                        ${photoHtml}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <select class="status-select rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" data-alert-id="${alert.id}">
                            <option value="pending" ${alert.status === 'pending' ? 'selected' : ''}>🔴 Pendente</option>
                            <option value="in_progress" ${alert.status === 'in_progress' ? 'selected' : ''}>🟡 Em Progresso</option>
                            <option value="resolved" ${alert.status === 'resolved' ? 'selected' : ''}>🟢 Resolvido</option>
                        </select>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="font-medium">${formattedDate}</div>
                        <div class="text-gray-500">${formattedTime}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <button onclick="viewOnMap(${alert.latitude}, ${alert.longitude})" class="text-blue-600 hover:text-blue-900 p-2 rounded-md hover:bg-blue-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                            <button onclick="deleteAlert(${alert.id})" class="text-red-600 hover:text-red-900 p-2 rounded-md hover:bg-red-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        // Atualizar status na linha
        function updateAlertRowStatus(alertId, newStatus) {
            const row = document.querySelector(`tr[data-alert-id="${alertId}"]`);
            if (row) {
                const select = row.querySelector('.status-select');
                if (select) {
                    select.value = newStatus;
                }

                // Atualizar cor de fundo
                row.classList.remove('bg-red-50', 'bg-yellow-50', 'bg-green-50');
                if (newStatus === 'pending') {
                    row.classList.add('bg-red-50');
                }
            }
        }

        // Atualizar estatísticas
        function updateStats() {
            fetch('/api/alerts')
                .then(response => response.json())
                .then(alerts => {
                    const pending = alerts.filter(a => a.status === 'pending').length;
                    const inProgress = alerts.filter(a => a.status === 'in_progress').length;
                    const resolved = alerts.filter(a => a.status === 'resolved').length;

                    document.getElementById('pending-count').textContent = pending;
                    document.getElementById('progress-count').textContent = inProgress;
                    document.getElementById('resolved-count').textContent = resolved;
                    document.getElementById('total-alerts').textContent = alerts.length;
                })
                .catch(error => console.error('Erro ao atualizar estatísticas:', error));
        }

        // Atualizar hora da última atualização
        function updateLastUpdateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('pt-BR');
            document.getElementById('last-update').textContent = timeString;
        }

        // Atualizar status do alerta
        function updateAlertStatus(alertId, newStatus) {
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
                    updateAlertRowStatus(alertId, newStatus);
                    updateStats();
                } else {
                    showNotification('Erro ao atualizar status', 'error');
                }
            })
            .catch(error => {
                console.error('Erro ao atualizar status:', error);
                showNotification('Erro ao atualizar status', 'error');
            });
        }

        // Deletar alerta
        function deleteAlert(alertId) {
            if (confirm('Tem certeza que deseja deletar este alerta?')) {
                fetch(`/alerts/${alertId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`tr[data-alert-id="${alertId}"]`);
                        if (row) {
                            row.remove();
                        }
                        showNotification('Alerta deletado com sucesso!', 'success');
                        updateStats();

                        // Verificar se não há mais alertas
                        const tableBody = document.getElementById('alerts-table-body');
                        if (tableBody.children.length === 0) {
                            showEmptyState();
                        }
                    } else {
                        showNotification('Erro ao deletar alerta', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro ao deletar alerta:', error);
                    showNotification('Erro ao deletar alerta', 'error');
                });
            }
        }

        // Mostrar estado vazio
        function showEmptyState() {
            const tableBody = document.getElementById('alerts-table-body');
            tableBody.innerHTML = `
                <tr id="empty-state">
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum alerta encontrado</h3>
                            <p class="text-gray-500">Quando houver alertas de emergência, eles aparecerão aqui.</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        // Mostrar notificação
        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const notificationText = document.getElementById('notification-text');

            // Definir cores baseadas no tipo
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500',
                warning: 'bg-yellow-500'
            };

            // Atualizar conteúdo
            notificationText.textContent = message;
            notification.querySelector('div').className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg flex items-center`;

            // Mostrar notificação
            notification.classList.remove('hidden');

            // Ocultar após 5 segundos
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 5000);
        }

        // Abrir modal de imagem
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        // Fechar modal de imagem
        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        // Ver no mapa
        function viewOnMap(lat, lng) {
            const url = `https://www.google.com/maps?q=${lat},${lng}`;
            window.open(url, '_blank');
        }

        // Parar polling quando a página não estiver visível
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopPolling();
            } else {
                initializePolling();
            }
        });

        // Parar polling quando a janela for fechada
        window.addEventListener('beforeunload', function() {
            stopPolling();
        });
    </script>
</x-app-layout>