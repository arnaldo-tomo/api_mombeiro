<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex items-center justify-center w-8 h-8 bg-red-500 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Painel de Alertas de Incêndio</h2>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                <div class="w-8 h-8 bg-gray-300 rounded-full"></div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                <!-- Alertas Pendentes -->
                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center mb-2 space-x-2">
                                <div class="flex items-center justify-center w-10 h-10 bg-red-50 rounded-xl">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium tracking-wide text-gray-600 uppercase">ALERTAS PENDENTES</span>
                            </div>
                            <div class="text-3xl font-bold text-gray-900" id="pending-count">{{ $alerts->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="w-1 h-16 bg-red-200 rounded-full"></div>
                    </div>
                </div>

                <!-- Em Progresso -->
                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center mb-2 space-x-2">
                                <div class="flex items-center justify-center w-10 h-10 bg-yellow-50 rounded-xl">
                                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium tracking-wide text-gray-600 uppercase">EM PROGRESSO</span>
                            </div>
                            <div class="text-3xl font-bold text-gray-900" id="progress-count">{{ $alerts->where('status', 'in_progress')->count() }}</div>
                        </div>
                        <div class="w-1 h-16 bg-yellow-200 rounded-full"></div>
                    </div>
                </div>

                <!-- Resolvidos -->
                <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center mb-2 space-x-2">
                                <div class="flex items-center justify-center w-10 h-10 bg-green-50 rounded-xl">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium tracking-wide text-gray-600 uppercase">RESOLVIDOS</span>
                            </div>
                            <div class="text-3xl font-bold text-gray-900" id="resolved-count">{{ $alerts->where('status', 'resolved')->count() }}</div>
                        </div>
                        <div class="w-1 h-16 bg-green-200 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Control Panel -->
            <div class="p-6 mb-8 bg-white border border-gray-100 shadow-sm rounded-2xl">
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
                        <button id="sound-toggle" class="flex items-center px-4 py-2 space-x-2 text-blue-700 transition-colors bg-blue-50 rounded-xl hover:bg-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            </svg>
                            <span id="sound-status">Som ON</span>
                        </button>

                        <!-- Refresh -->
                        <button id="refresh-btn" class="flex items-center px-4 py-2 space-x-2 text-gray-700 transition-colors bg-gray-50 rounded-xl hover:bg-gray-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span>Atualizar</span>
                        </button>

                        <!-- Teste Manual -->
                        <button onclick="testarTudo()" class="flex items-center px-4 py-2 space-x-2 text-purple-700 transition-colors bg-purple-50 rounded-xl hover:bg-purple-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <span>Testar</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Alerts Table -->
            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
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
                                    <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">USUÁRIO</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">LOCALIZAÇÃO</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">DESCRIÇÃO</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">MÍDIA</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">STATUS</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">DATA/HORA</span>
                                </th>
                                <th class="px-6 py-3 text-left">
                                    <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">AÇÕES</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="alerts-table-body" class="divide-y divide-gray-100">
                            @forelse($alerts as $alert)
                            <tr class="transition-colors hover:bg-gray-50" data-alert-id="{{ $alert->id }}">
                                <!-- User Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                                            <span class="text-sm font-medium text-red-600">{{ substr($alert->user_name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $alert->user_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $alert->user_phone }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Location Column -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $alert->location }}</div>
                                    <div class="text-xs text-gray-500">{{ $alert->latitude }}, {{ $alert->longitude }}</div>
                                </td>

                                <!-- Description Column -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $alert->message ?? 'Sem descrição' }}</div>
                                </td>

                                <!-- Media Column -->
                                <td class="px-6 py-4">
                                    <div class="flex flex-col space-y-1">
                                        @if($alert->photo)
                                        <button type="button" onclick="abrirImagem('{{ asset('storage/' . $alert->photo) }}')" class="flex items-center px-2 py-1 space-x-2 text-xs text-green-700 rounded bg-green-50 hover:bg-green-100">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Foto</span>
                                        </button>
                                        @endif

                                        @if($alert->video)
                                        <button type="button" onclick="abrirVideo('{{ asset('storage/' . $alert->video) }}')" class="flex items-center px-2 py-1 space-x-2 text-xs text-blue-700 rounded bg-blue-50 hover:bg-blue-100">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                            </svg>
                                            <span>Vídeo</span>
                                        </button>
                                        @endif

                                        @if($alert->audio)
                                        <button type="button" onclick="tocarAudio('{{ asset('storage/' . $alert->audio) }}')" class="flex items-center px-2 py-1 space-x-2 text-xs text-purple-700 rounded bg-purple-50 hover:bg-purple-100">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.796L4.828 14H2a1 1 0 01-1-1V7a1 1 0 011-1h2.828l3.555-2.796A1 1 0 019.383 3.076zM12 6a1 1 0 011 1v6a1 1 0 11-2 0V7a1 1 0 011-1zm3-1a1 1 0 000 2 3 3 0 010 6 1 1 0 100 2 5 5 0 000-10z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Áudio</span>
                                        </button>
                                        @endif

                                        @if(!$alert->photo && !$alert->video && !$alert->audio)
                                        <span class="text-xs text-gray-400">Sem mídia</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status Column -->
                                <td class="px-6 py-4">
                                    <select class="px-2 py-1 text-xs border border-gray-200 rounded status-select" data-alert-id="{{ $alert->id }}">
                                        <option value="pending" {{ $alert->status == 'pending' ? 'selected' : '' }}>🔴 Pendente</option>
                                        <option value="in_progress" {{ $alert->status == 'in_progress' ? 'selected' : '' }}>🟡 Em Progresso</option>
                                        <option value="resolved" {{ $alert->status == 'resolved' ? 'selected' : '' }}>🟢 Resolvido</option>
                                    </select>
                                </td>

                                <!-- Date Column -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $alert->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $alert->created_at->format('H:i:s') }}</div>
                                </td>

                                <!-- Actions Column -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button type="button" onclick="verNoMapa({{ $alert->latitude }}, {{ $alert->longitude }})" class="p-1 text-blue-600 rounded hover:bg-blue-50">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="excluirAlerta({{ $alert->id }})" class="p-1 text-red-600 rounded hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="empty-state">
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="flex items-center justify-center w-12 h-12 mb-4 bg-gray-100 rounded-xl">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="mb-2 text-lg font-medium text-gray-900">Nenhum alerta encontrado</h3>
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

    <!-- Modal de Imagem -->
    <div id="modalImagem" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000;">
        <div style="display: flex; align-items: center; justify-content: center; height: 100%; padding: 20px;">
            <div style="background: white; border-radius: 12px; max-width: 90%; max-height: 90%; overflow: hidden;">
                <div style="padding: 15px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Foto do Alerta</h3>
                    <button onclick="fecharModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">×</button>
                </div>
                <div style="padding: 15px; text-align: center;">
                    <img id="imagemModal" src="" style="max-width: 100%; max-height: 70vh; border-radius: 8px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Vídeo -->
    <div id="modalVideo" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000;">
        <div style="display: flex; align-items: center; justify-content: center; height: 100%; padding: 20px;">
            <div style="background: white; border-radius: 12px; max-width: 90%; max-height: 90%; overflow: hidden;">
                <div style="padding: 15px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Vídeo do Alerta</h3>
                    <button onclick="fecharModal()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280;">×</button>
                </div>
                <div style="padding: 15px; text-align: center;">
                    <video id="videoModal" controls style="max-width: 100%; max-height: 70vh; border-radius: 8px;">
                        Seu navegador não suporta vídeo.
                    </video>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast de Notificação -->
    <div id="toast" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 10001;">
        <div id="toastContent" style="background: white; border: 1px solid #d1d5db; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 16px 20px; display: flex; align-items: center; gap: 12px; max-width: 400px;">
            <div id="toastIcon"></div>
            <span id="toastText" style="font-size: 14px; font-weight: 500;"></span>
        </div>
    </div>

    <script>
        // ================================
        // VARIÁVEIS GLOBAIS
        // ================================
        window.soundEnabled = true;
        window.currentAlerts = [];
        window.pollingInterval = null;
        window.notificationAudio = null;

        // ================================
        // FUNÇÕES DE MODAL - GLOBAIS
        // ================================
        function abrirImagem(src) {
            console.log('📸 Abrindo imagem:', src);
            document.getElementById('imagemModal').src = src;
            document.getElementById('modalImagem').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function abrirVideo(src) {
            console.log('🎥 Abrindo vídeo:', src);
            document.getElementById('videoModal').src = src;
            document.getElementById('modalVideo').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function fecharModal() {
            console.log('❌ Fechando modais');
            // Fechar modal de imagem
            document.getElementById('modalImagem').style.display = 'none';
            document.getElementById('imagemModal').src = '';

            // Fechar modal de vídeo
            document.getElementById('modalVideo').style.display = 'none';
            const video = document.getElementById('videoModal');
            video.pause();
            video.src = '';

            document.body.style.overflow = '';
        }

        function tocarAudio(src) {
            console.log('🔊 Tocando áudio:', src);
            const audio = new Audio(src);
            audio.play().then(() => {
                mostrarNotificacao('Reproduzindo áudio...', 'info');
            }).catch(error => {
                console.error('Erro ao tocar áudio:', error);
                mostrarNotificacao('Erro ao reproduzir áudio', 'error');
            });
        }

        function verNoMapa(lat, lng) {
            console.log('🗺️ Abrindo mapa:', lat, lng);
            window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
        }

        function excluirAlerta(id) {
            if (!confirm('Tem certeza que deseja excluir este alerta?')) return;

            console.log('🗑️ Excluindo alerta:', id);

            fetch(`/alerts/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const row = document.querySelector(`tr[data-alert-id="${id}"]`);
                    if (row) row.remove();
                    mostrarNotificacao('Alerta excluído com sucesso!', 'success');
                    atualizarEstatisticas();
                } else {
                    mostrarNotificacao('Erro ao excluir alerta', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                mostrarNotificacao('Erro de conexão', 'error');
            });
        }

        // ================================
        // SISTEMA DE NOTIFICAÇÕES
        // ================================
        function mostrarNotificacao(message, type = 'info') {
            console.log('📢 Notificação:', message, type);

            const toast = document.getElementById('toast');
            const content = document.getElementById('toastContent');
            const icon = document.getElementById('toastIcon');
            const text = document.getElementById('toastText');

            // Definir cores e ícones
            let borderColor = '#3b82f6';
            let iconSvg = '';

            if (type === 'success') {
                borderColor = '#10b981';
                iconSvg = '<svg style="width: 20px; height: 20px; color: #10b981;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
            } else if (type === 'error') {
                borderColor = '#ef4444';
                iconSvg = '<svg style="width: 20px; height: 20px; color: #ef4444;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
            } else {
                borderColor = '#3b82f6';
                iconSvg = '<svg style="width: 20px; height: 20px; color: #3b82f6;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>';
            }

            // Aplicar estilos
            content.style.borderColor = borderColor;
            icon.innerHTML = iconSvg;
            text.textContent = message;

            // Mostrar toast
            toast.style.display = 'block';

            // Esconder após 3 segundos
            setTimeout(() => {
                toast.style.display = 'none';
            }, 3000);
        }

        // ================================
        // SISTEMA DE SOM
        // ================================
        function inicializarSom() {
            // Tentar carregar som personalizado
            window.notificationAudio = new Audio('/som.mp3');
            window.notificationAudio.volume = 0.7;
            window.notificationAudio.onerror = () => {
                console.warn('Som personalizado não encontrado, usando sintético');
                window.notificationAudio = null;
            };
        }

        function tocarSomNotificacao() {
            if (!window.soundEnabled) return;

            if (window.notificationAudio) {
                window.notificationAudio.currentTime = 0;
                window.notificationAudio.play().catch(() => tocarSomSintetico());
            } else {
                tocarSomSintetico();
            }
        }

        function tocarSomSintetico() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();

                osc.connect(gain);
                gain.connect(ctx.destination);

                osc.frequency.value = 800;
                osc.type = 'sine';

                gain.gain.setValueAtTime(0, ctx.currentTime);
                gain.gain.linearRampToValueAtTime(0.3, ctx.currentTime + 0.01);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);

                osc.start();
                osc.stop(ctx.currentTime + 0.5);
            } catch (error) {
                console.error('Erro ao tocar som sintético:', error);
            }
        }

        function alternarSom() {
            window.soundEnabled = !window.soundEnabled;
            const button = document.getElementById('sound-toggle');
            const status = document.getElementById('sound-status');

            if (window.soundEnabled) {
                button.className = 'flex items-center space-x-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition-colors';
                status.textContent = 'Som ON';
                tocarSomNotificacao(); // Testar som
            } else {
                button.className = 'flex items-center space-x-2 px-4 py-2 bg-gray-50 text-gray-700 rounded-xl hover:bg-gray-100 transition-colors';
                status.textContent = 'Som OFF';
            }
        }

        // ================================
        // SISTEMA DE POLLING
        // ================================
        function iniciarPolling() {
            if (window.pollingInterval) return;

            window.pollingInterval = setInterval(() => {
                fetch('/api/alerts')
                    .then(response => response.json())
                    .then(data => {
                        const novosAlertas = data.filter(alert =>
                            !window.currentAlerts.some(existing => existing.id === alert.id)
                        );

                        if (novosAlertas.length > 0) {
                            console.log('🚨 Novos alertas encontrados:', novosAlertas.length);
                            tocarSomNotificacao();
                            mostrarNotificacao(`${novosAlertas.length} novo(s) alerta(s) recebido(s)!`, 'success');

                            // Adicionar novos alertas à tabela
                            novosAlertas.forEach(alert => adicionarAlertaTabela(alert));
                            window.currentAlerts = data;
                        }

                        atualizarEstatisticas();
                        atualizarHorario();
                    })
                    .catch(error => {
                        console.error('Erro no polling:', error);
                        mostrarNotificacao('Erro ao verificar novos alertas', 'error');
                    });
            }, 5000); // Verificar a cada 5 segundos
        }

        function adicionarAlertaTabela(alert) {
            const tbody = document.getElementById('alerts-table-body');
            const emptyState = document.getElementById('empty-state');

            if (emptyState) emptyState.remove();

            const row = criarLinhaAlerta(alert);
            tbody.insertAdjacentHTML('afterbegin', row);
        }

        function criarLinhaAlerta(alert) {
            const data = new Date(alert.created_at);

            let botoesMedia = '';
            if (alert.photo) {
                botoesMedia += `<button type="button" onclick="abrirImagem('/storage/${alert.photo}')" class="flex items-center px-2 py-1 mb-1 space-x-2 text-xs text-green-700 rounded bg-green-50 hover:bg-green-100"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg><span>Foto</span></button>`;
            }
            if (alert.video) {
                botoesMedia += `<button type="button" onclick="abrirVideo('/storage/${alert.video}')" class="flex items-center px-2 py-1 mb-1 space-x-2 text-xs text-blue-700 rounded bg-blue-50 hover:bg-blue-100"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/></svg><span>Vídeo</span></button>`;
            }
            if (alert.audio) {
                botoesMedia += `<button type="button" onclick="tocarAudio('/storage/${alert.audio}')" class="flex items-center px-2 py-1 mb-1 space-x-2 text-xs text-purple-700 rounded bg-purple-50 hover:bg-purple-100"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.617.796L4.828 14H2a1 1 0 01-1-1V7a1 1 0 011-1h2.828l3.555-2.796A1 1 0 019.383 3.076zM12 6a1 1 0 011 1v6a1 1 0 11-2 0V7a1 1 0 011-1zm3-1a1 1 0 000 2 3 3 0 010 6 1 1 0 100 2 5 5 0 000-10z" clip-rule="evenodd"/></svg><span>Áudio</span></button>`;
            }
            if (!botoesMedia) {
                botoesMedia = '<span class="text-xs text-gray-400">Sem mídia</span>';
            }

            return `
                <tr class="transition-colors hover:bg-gray-50 animate-pulse" data-alert-id="${alert.id}" style="animation: pulse 2s infinite;">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 rounded-full">
                                <span class="text-sm font-medium text-red-600">${alert.user_name.charAt(0)}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${alert.user_name}</div>
                                <div class="text-sm text-gray-500">${alert.user_phone}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">${alert.location}</div>
                        <div class="text-xs text-gray-500">${alert.latitude}, ${alert.longitude}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">${alert.message || 'Sem descrição'}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col space-y-1">${botoesMedia}</div>
                    </td>
                    <td class="px-6 py-4">
                        <select class="px-2 py-1 text-xs border border-gray-200 rounded status-select" data-alert-id="${alert.id}">
                            <option value="pending" ${alert.status === 'pending' ? 'selected' : ''}>🔴 Pendente</option>
                            <option value="in_progress" ${alert.status === 'in_progress' ? 'selected' : ''}>🟡 Em Progresso</option>
                            <option value="resolved" ${alert.status === 'resolved' ? 'selected' : ''}>🟢 Resolvido</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">${data.toLocaleDateString('pt-BR')}</div>
                        <div class="text-xs text-gray-500">${data.toLocaleTimeString('pt-BR')}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="verNoMapa(${alert.latitude}, ${alert.longitude})" class="p-1 text-blue-600 rounded hover:bg-blue-50">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                            </button>
                            <button type="button" onclick="excluirAlerta(${alert.id})" class="p-1 text-red-600 rounded hover:bg-red-50">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }

        function atualizarEstatisticas() {
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

        function atualizarHorario() {
            const now = new Date();
            document.getElementById('last-update').textContent = now.toLocaleTimeString('pt-BR');
        }

        function atualizarStatus(alertId, novoStatus) {
            fetch(`/alerts/${alertId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: novoStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacao('Status atualizado!', 'success');
                    atualizarEstatisticas();
                } else {
                    mostrarNotificacao('Erro ao atualizar status', 'error');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                mostrarNotificacao('Erro de conexão', 'error');
            });
        }

        function forcarAtualizacao() {
            const btn = document.getElementById('refresh-btn');
            const originalHTML = btn.innerHTML;

            btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg><span>Atualizando...</span>';

            atualizarEstatisticas();

            setTimeout(() => {
                btn.innerHTML = originalHTML;
                mostrarNotificacao('Dados atualizados!', 'success');
            }, 1000);
        }

        // ================================
        // FUNÇÃO DE TESTE GLOBAL
        // ================================
        function testarTudo() {
            console.log('🧪 Iniciando teste completo do sistema...');

            // Teste 1: Notificação
            mostrarNotificacao('Sistema funcionando perfeitamente!', 'success');

            // Teste 2: Som
            setTimeout(() => {
                tocarSomNotificacao();
            }, 500);

            // Teste 3: Modal de imagem (apenas abrir e fechar)
            setTimeout(() => {
                console.log('📸 Testando modal de imagem...');
                document.getElementById('imagemModal').src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5URVNURSBJTUFHRU08L3RleHQ+PC9zdmc+';
                document.getElementById('modalImagem').style.display = 'block';

                setTimeout(() => {
                    fecharModal();
                    mostrarNotificacao('Modal testado com sucesso!', 'info');
                }, 2000);
            }, 1000);

            console.log('✅ Teste concluído!');
        }

        // ================================
        // INICIALIZAÇÃO
        // ================================
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Inicializando sistema de alertas...');

            // Inicializar componentes
            inicializarSom();

            // Carregar alertas iniciais
            fetch('/api/alerts')
                .then(response => response.json())
                .then(data => {
                    window.currentAlerts = data;
                    atualizarEstatisticas();
                })
                .catch(error => console.error('Erro ao carregar alertas iniciais:', error));

            // Iniciar polling
            iniciarPolling();

            // Event listeners
            document.getElementById('sound-toggle').addEventListener('click', alternarSom);
            document.getElementById('refresh-btn').addEventListener('click', forcarAtualizacao);

            // Event listener para mudança de status
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('status-select')) {
                    const alertId = e.target.dataset.alertId;
                    const novoStatus = e.target.value;
                    atualizarStatus(alertId, novoStatus);
                }
            });

            // Fechar modais com ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') fecharModal();
            });

            // Fechar modais clicando fora
            document.getElementById('modalImagem').addEventListener('click', function(e) {
                if (e.target === this) fecharModal();
            });

            document.getElementById('modalVideo').addEventListener('click', function(e) {
                if (e.target === this) fecharModal();
            });

            // Teste automático após 2 segundos
            setTimeout(() => {
                mostrarNotificacao('Sistema carregado e funcionando!', 'success');
                tocarSomNotificacao();
            }, 2000);

            console.log('✅ Sistema inicializado com sucesso!');
        });

        // Limpeza ao sair da página
        window.addEventListener('beforeunload', function() {
            if (window.pollingInterval) {
                clearInterval(window.pollingInterval);
            }
        });

        // Pausar/retomar polling baseado na visibilidade da página
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (window.pollingInterval) {
                    clearInterval(window.pollingInterval);
                    window.pollingInterval = null;
                }
            } else {
                iniciarPolling();
            }
        });
    </script>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }
    </style>
</x-app-layout>