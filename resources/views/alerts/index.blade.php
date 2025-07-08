<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-xl text-gray-900">Painel de Alertas de Incêndio</h2>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stats Cards - Clean Design -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Alertas Pendentes -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 uppercase tracking-wide">ALERTAS PENDENTES</span>
                            </div>
                            <div class="text-3xl font-bold text-gray-900" id="pending-count">{{ $alerts->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="w-1 h-16 bg-red-200 rounded-full"></div>
                    </div>
                </div>

                <!-- Em Progresso -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-10 h-10 bg-yellow-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 uppercase tracking-wide">EM PROGRESSO</span>
                            </div>
                            <div class="text-3xl font-bold text-gray-900" id="progress-count">{{ $alerts->where('status', 'in_progress')->count() }}</div>
                        </div>
                        <div class="w-1 h-16 bg-yellow-200 rounded-full"></div>
                    </div>
                </div>

                <!-- Resolvidos -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-600 uppercase tracking-wide">RESOLVIDOS</span>
                            </div>
                            <div class="text-3xl font-bold text-gray-900" id="resolved-count">{{ $alerts->where('status', 'resolved')->count() }}</div>
                        </div>
                        <div class="w-1 h-16 bg-green-200 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Control Panel - Clean -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Controle do Sistema</h3>

                    <div class="flex items-center space-x-4">
                        <!-- Status -->
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-600">Sistema Online</span>
                        </div>

                        <!-- Time -->
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm text-gray-600" id="last-update">{{ now()->format('H:i:s') }}</span>
                        </div>

                        <!-- Sound Control -->
                        <button id="sound-toggle" class="flex items-center space-x-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            </svg>
                            <span id="sound-status">Som ON</span>
                        </button>

                        <!-- Refresh -->
                        <button id="refresh-btn" class="flex items-center space-x-2 px-4 py-2 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Atualizar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Alerts Table - Clean Design -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Alertas de Emergência</h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">Total:</span>
                            <span class="text-sm font-semibold text-gray-900" id="total-alerts">{{ $alerts->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">USUÁRIO</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">LOCALIZAÇÃO</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">DESCRIÇÃO</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">STATUS</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">DATA/HORA</span>
                                    </div>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">AÇÕES</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="alerts-table-body" class="divide-y divide-gray-100">
                            @forelse($alerts as $alert)
                            <tr class="hover:bg-gray-50 transition-colors" data-alert-id="{{ $alert->id }}">
                                <!-- User Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-red-600">{{ substr($alert->user_name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $alert->user_name }}</div>
                                            <div class="text-sm text-gray-500 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $alert->user_phone }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Location Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-start space-x-2">
                                        <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        <div>
                                            <div class="text-sm text-gray-900">{{ $alert->location }}</div>
                                            <div class="text-xs text-gray-500">{{ $alert->latitude }}, {{ $alert->longitude }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Description Column -->
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <div class="text-sm text-gray-900 mb-2">{{ $alert->message ?? 'Sem descrição' }}</div>

                                        <!-- Media Files -->
                                        <div class="flex items-center space-x-2">
                                            @if($alert->photo)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $alert->photo) }}" alt="Foto" class="w-12 h-12 rounded-lg object-cover cursor-pointer image-preview" data-src="{{ asset('storage/' . $alert->photo) }}">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all"></div>
                                            </div>
                                            @endif

                                            @if($alert->video)
                                            <div class="relative group">
                                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center cursor-pointer video-preview" data-src="{{ asset('storage/' . $alert->video) }}">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10v4a6 6 0 006 6v-4"/>
                                                    </svg>
                                                </div>
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all"></div>
                                            </div>
                                            @endif

                                            @if($alert->audio)
                                            <div class="relative group">
                                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center cursor-pointer audio-preview" data-src="{{ asset('storage/' . $alert->audio) }}">
                                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                                    </svg>
                                                </div>
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all"></div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4">
                                    <select class="status-select border-0 bg-transparent text-sm font-medium focus:ring-0 focus:outline-none" data-alert-id="{{ $alert->id }}">
                                        <option value="pending" {{ $alert->status == 'pending' ? 'selected' : '' }} class="text-red-600">🔴 Pendente</option>
                                        <option value="in_progress" {{ $alert->status == 'in_progress' ? 'selected' : '' }} class="text-yellow-600">🟡 Em Progresso</option>
                                        <option value="resolved" {{ $alert->status == 'resolved' ? 'selected' : '' }} class="text-green-600">🟢 Resolvido</option>
                                    </select>
                                </td>

                                <!-- Date Column -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $alert->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $alert->created_at->format('H:i:s') }}</div>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button onclick="viewOnMap({{ $alert->latitude }}, {{ $alert->longitude }})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                        </button>
                                        <button onclick="deleteAlert({{ $alert->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mb-4">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum alerta encontrado</h3>
                                        <p class="text-gray-500">Os alertas de emergência aparecerão aqui quando forem recebidos.</p>
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
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl max-h-full overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Foto do Alerta</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <img id="modalImage" src="" alt="Foto do alerta" class="max-w-full max-h-96 mx-auto rounded-xl shadow-lg">
            </div>
        </div>
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl max-h-full overflow-hidden">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Vídeo do Alerta</h3>
                <button onclick="closeVideoModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <video id="modalVideo" controls class="max-w-full max-h-96 mx-auto rounded-xl shadow-lg">
                    Seu navegador não suporta o elemento de vídeo.
                </video>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="notification" class="fixed top-4 right-4 z-50 hidden transform transition-all duration-300">
        <div id="notification-content" class="bg-white border border-gray-200 rounded-xl shadow-lg px-6 py-4 flex items-center space-x-3">
            <div id="notification-icon" class="flex-shrink-0">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span id="notification-text" class="text-sm font-medium text-gray-900">Operação realizada com sucesso!</span>
        </div>
    </div>

    <script>
        // Global variables
        let soundEnabled = true;
        let currentAlerts = [];
        let isPolling = false;
        let pollingInterval = null;

        // Initialize everything
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
            initializePolling();
            loadInitialAlerts();
        });

        function setupEventListeners() {
            // Sound toggle
            document.getElementById('sound-toggle').addEventListener('click', toggleSound);

            // Refresh button
            document.getElementById('refresh-btn').addEventListener('click', forceRefresh);

            // Status change
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('status-select')) {
                    const alertId = e.target.dataset.alertId;
                    const newStatus = e.target.value;
                    updateAlertStatus(alertId, newStatus);
                }
            });

            // Delegação de eventos para cliques em mídia
            document.getElementById('alerts-table-body').addEventListener('click', function(e) {
                const target = e.target.closest('.cursor-pointer');
                if (!target) return;

                const src = target.dataset.src;
                if (!src) {
                    console.error('Nenhum src encontrado para o elemento:', target);
                    return;
                }

                if (target.classList.contains('image-preview')) {
                    console.log('Abrindo modal de imagem:', src);
                    openImageModal(src);
                } else if (target.classList.contains('video-preview')) {
                    console.log('Abrindo modal de vídeo:', src);
                    openVideoModal(src);
                } else if (target.classList.contains('audio-preview')) {
                    console.log('Reproduzindo áudio:', src);
                    playAudio(src);
                }
            });
        }

        function initializePolling() {
            if (isPolling) return;
            isPolling = true;
            pollingInterval = setInterval(checkForNewAlerts, 5000);
        }

        function loadInitialAlerts() {
            fetch('/api/alerts')
                .then(response => response.json())
                .then(data => {
                    currentAlerts = data;
                    updateStats();
                })
                .catch(error => console.error('Erro ao carregar alertas:', error));
        }

        function checkForNewAlerts() {
            fetch('/api/alerts')
                .then(response => response.json())
                .then(data => {
                    const newAlerts = data.filter(alert =>
                        !currentAlerts.some(existing => existing.id === alert.id)
                    );

                    if (newAlerts.length > 0) {
                        if (soundEnabled) {
                            playNotificationSound();
                        }

                        showNotification(`${newAlerts.length} novo(s) alerta(s) recebido(s)!`, 'success');

                        newAlerts.forEach(alert => {
                            addNewAlertToTable(alert);
                        });

                        currentAlerts = data;
                    }

                    updateStats();
                    updateLastUpdateTime();
                })
                .catch(error => {
                    console.error('Erro ao verificar alertas:', error);
                    showNotification('Erro ao verificar alertas', 'error');
                });
        }

        function playNotificationSound() {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0, audioContext.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.3, audioContext.currentTime + 0.01);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.5);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.5);
        }

        function addNewAlertToTable(alert) {
            const tableBody = document.getElementById('alerts-table-body');
            const emptyState = document.getElementById('empty-state');

            if (emptyState) {
                emptyState.remove();
            }

            const newRow = createAlertRow(alert);
            tableBody.insertAdjacentHTML('afterbegin', newRow);
        }

        function createAlertRow(alert) {
            const mediaHtml = createMediaHtml(alert);
            const createdAt = new Date(alert.created_at);

            return `
                <tr class="hover:bg-gray-50 transition-colors animate-pulse" data-alert-id="${alert.id}">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-red-600">${alert.user_name.charAt(0)}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${alert.user_name}</div>
                                <div class="text-sm text-gray-500 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    ${alert.user_phone}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <div>
                                <div class="text-sm text-gray-900">${alert.location}</div>
                                <div class="text-xs text-gray-500">${alert.latitude}, ${alert.longitude}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-xs">
                            <div class="text-sm text-gray-900 mb-2">${alert.message || 'Sem descrição'}</div>
                            ${mediaHtml}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <select class="status-select border-0 bg-transparent text-sm font-medium focus:ring-0 focus:outline-none" data-alert-id="${alert.id}">
                            <option value="pending" ${alert.status === 'pending' ? 'selected' : ''} class="text-red-600">🔴 Pendente</option>
                            <option value="in_progress" ${alert.status === 'in_progress' ? 'selected' : ''} class="text-yellow-600">🟡 Em Progresso</option>
                            <option value="resolved" ${alert.status === 'resolved' ? 'selected' : ''} class="text-green-600">🟢 Resolvido</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">${createdAt.toLocaleDateString('pt-BR')}</div>
                        <div class="text-sm text-gray-500">${createdAt.toLocaleTimeString('pt-BR')}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <button onclick="viewOnMap(${alert.latitude}, ${alert.longitude})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                            </button>
                            <button onclick="deleteAlert(${alert.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        function createMediaHtml(alert) {
            let mediaHtml = '<div class="flex items-center space-x-2">';

            if (alert.photo) {
                mediaHtml += `
                    <div class="relative group">
                        <img src="/storage/${alert.photo}" alt="Foto" class="w-12 h-12 rounded-lg object-cover cursor-pointer image-preview" data-src="/storage/${alert.photo}">
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all"></div>
                    </div>
                `;
            }

            if (alert.video) {
                mediaHtml += `
                    <div class="relative group">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center cursor-pointer video-preview" data-src="/storage/${alert.video}">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293H15M9 10v4a6 6 0 006 6v-4"/>
                            </svg>
                        </div>
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all"></div>
                    </div>
                `;
            }

            if (alert.audio) {
                mediaHtml += `
                    <div class="relative group">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center cursor-pointer audio-preview" data-src="/storage/${alert.audio}">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                            </svg>
                        </div>
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all"></div>
                    </div>
                `;
            }

            mediaHtml += '</div>';
            return mediaHtml;
        }

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

        function updateLastUpdateTime() {
            const now = new Date();
            document.getElementById('last-update').textContent = now.toLocaleTimeString('pt-BR');
        }

        function toggleSound() {
            soundEnabled = !soundEnabled;
            const button = document.getElementById('sound-toggle');
            const status = document.getElementById('sound-status');

            if (soundEnabled) {
                button.classList.remove('bg-gray-50', 'text-gray-700');
                button.classList.add('bg-blue-50', 'text-blue-700');
                status.textContent = 'Som ON';
            } else {
                button.classList.remove('bg-blue-50', 'text-blue-700');
                button.classList.add('bg-gray-50', 'text-gray-700');
                status.textContent = 'Som OFF';
            }
        }

        function forceRefresh() {
            const button = document.getElementById('refresh-btn');
            const originalHTML = button.innerHTML;

            button.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Atualizando...</span>
            `;

            checkForNewAlerts();

            setTimeout(() => {
                button.innerHTML = originalHTML;
            }, 1000);
        }

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
                    updateStats();
                } else {
                    showNotification('Erro ao atualizar status', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                showNotification('Erro de conexão', 'error');
            });
        }

        function deleteAlert(alertId) {
            if (confirm('Tem certeza que deseja excluir este alerta?')) {
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
                        showNotification('Alerta excluído com sucesso!', 'success');
                        updateStats();
                    } else {
                        showNotification('Erro ao excluir alerta', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification('Erro de conexão', 'error');
                });
            }
        }

        function viewOnMap(lat, lng) {
            window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
        }

        function openImageModal(src) {
            console.log('Tentando abrir modal de imagem:', src);
            const modal = document.getElementById('imageModal');
            const image = document.getElementById('modalImage');
            if (!modal || !image) {
                console.error('Elementos do modal de imagem não encontrados');
                showNotification('Erro ao abrir imagem', 'error');
                return;
            }
            image.src = src;
            modal.classList.remove('hidden');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const image = document.getElementById('modalImage');
            if (image) image.src = '';
            if (modal) modal.classList.add('hidden');
        }

        function openVideoModal(src) {
            console.log('Tentando abrir modal de vídeo:', src);
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('modalVideo');
            if (!modal || !video) {
                console.error('Elementos do modal de vídeo não encontrados');
                showNotification('Erro ao abrir vídeo', 'error');
                return;
            }
            video.src = src;
            modal.classList.remove('hidden');
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoModal');
            const video = document.getElementById('modalVideo');
            if (video) {
                video.pause();
                video.src = '';
            }
            if (modal) modal.classList.add('hidden');
        }

        function playAudio(src) {
            console.log('Tentando reproduzir áudio:', src);
            const audio = new Audio(src);
            audio.play().then(() => {
                console.log('Áudio reproduzido com sucesso');
            }).catch(error => {
                console.error('Erro ao reproduzir áudio:', error);
                showNotification('Erro ao reproduzir áudio', 'error');
            });
        }

        function showNotification(message, type = 'info') {
            const notification = document.getElementById('notification');
            const content = document.getElementById('notification-content');
            const text = document.getElementById('notification-text');
            const icon = document.getElementById('notification-icon');

            if (type === 'success') {
                content.className = 'bg-white border border-green-200 rounded-xl shadow-lg px-6 py-4 flex items-center space-x-3';
                icon.innerHTML = `
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                `;
            } else if (type === 'error') {
                content.className = 'bg-white border border-red-200 rounded-xl shadow-lg px-6 py-4 flex items-center space-x-3';
                icon.innerHTML = `
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                `;
            }

            text.textContent = message;

            notification.classList.remove('hidden');
            notification.style.transform = 'translateX(0)';

            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.classList.add('hidden');
                }, 300);
            }, 3000);
        }

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                clearInterval(pollingInterval);
                isPolling = false;
            } else {
                initializePolling();
            }
        });

        window.addEventListener('beforeunload', function() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        });
    </script>

<style>
    #imageModal:not(.hidden), #videoModal:not(.hidden) {
        display: flex;
        z-index: 50;
    }
</style>
</x-app-layout>