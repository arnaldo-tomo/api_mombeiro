<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 9m0 8V9m0 0V7"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-xl text-gray-900">Mapa de Alertas</h2>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('alerts.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m5-6h6m-5-4v4m-4 4H7a2 2 0 01-2-2V9a2 2 0 012-2h2m5 6h6"/>
                    </svg>
                </a>
                <button onclick="exportData()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Exportar
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Map Controls -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-lg font-semibold text-gray-900">Controles do Mapa</h3>

                        <!-- Legend -->
                        <div class="flex items-center space-x-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                <span>Pendente</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                <span>Em Progresso</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <span>Resolvido</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-600 rounded-full mr-2 animate-pulse"></div>
                                <span>Emergência</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <!-- Filters -->
                        <select id="status-filter" class="border-gray-300 rounded-lg text-sm">
                            <option value="all">Todos os Status</option>
                            <option value="pending">Pendentes</option>
                            <option value="in_progress">Em Progresso</option>
                            <option value="resolved">Resolvidos</option>
                        </select>

                        <select id="time-filter" class="border-gray-300 rounded-lg text-sm">
                            <option value="all">Todo o Período</option>
                            <option value="today">Hoje</option>
                            <option value="week">Esta Semana</option>
                            <option value="month">Este Mês</option>
                        </select>

                        <select id="emergency-filter" class="border-gray-300 rounded-lg text-sm">
                            <option value="all">Todos</option>
                            <option value="emergency">Apenas Emergências</option>
                        </select>

                        <button id="refresh-map" class="px-3 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>

                        <button id="fullscreen-map" class="px-3 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div id="map" style="height: 600px; width: 100%;" class="relative">
                    <!-- Loading overlay -->
                    <div id="map-loading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-600">Carregando mapa...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-6">
                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900" id="map-total">{{ $alerts->count() }}</div>
                        <div class="text-sm text-gray-600">Total</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600" id="map-pending">{{ $alerts->where('status', 'pending')->count() }}</div>
                        <div class="text-sm text-gray-600">Pendentes</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600" id="map-progress">{{ $alerts->where('status', 'in_progress')->count() }}</div>
                        <div class="text-sm text-gray-600">Em Progresso</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" id="map-resolved">{{ $alerts->where('status', 'resolved')->count() }}</div>
                        <div class="text-sm text-gray-600">Resolvidos</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-700" id="map-emergency">{{ $alerts->where('is_emergency', true)->count() }}</div>
                        <div class="text-sm text-gray-600">Emergências</div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600" id="map-today">{{ $alerts->whereDate('created_at', today())->count() }}</div>
                        <div class="text-sm text-gray-600">Hoje</div>
                    </div>
                </div>
            </div>

            <!-- Alert Details Modal -->
            <div id="alert-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                        <div id="alert-modal-content">
                            <!-- Modal content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Google Maps -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>

    <script>
        let map;
        let markers = [];
        let alertsData = @json($alerts);
        let currentInfoWindow = null;
        let markerCluster = null;

        // Initialize the map
        function initMap() {
            // Default to Beira, Mozambique
            const defaultCenter = { lat: -19.8157, lng: 34.8515 };

            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: defaultCenter,
                mapTypeControl: true,
                fullscreenControl: false,
                streetViewControl: true,
                styles: [
                    {
                        featureType: 'poi',
                        elementType: 'labels',
                        stylers: [{ visibility: 'off' }]
                    },
                    {
                        featureType: 'transit',
                        elementType: 'labels',
                        stylers: [{ visibility: 'off' }]
                    }
                ]
            });

            // Hide loading overlay
            document.getElementById('map-loading').style.display = 'none';

            loadMarkers();
            setupEventListeners();
        }

        function loadMarkers() {
            // Clear existing markers
            markers.forEach(marker => marker.setMap(null));
            markers = [];

            // Get filter values
            const statusFilter = document.getElementById('status-filter').value;
            const timeFilter = document.getElementById('time-filter').value;
            const emergencyFilter = document.getElementById('emergency-filter').value;

            // Filter alerts
            let filteredAlerts = alertsData;

            if (statusFilter !== 'all') {
                filteredAlerts = filteredAlerts.filter(alert => alert.status === statusFilter);
            }

            if (emergencyFilter === 'emergency') {
                filteredAlerts = filteredAlerts.filter(alert => alert.is_emergency);
            }

            if (timeFilter !== 'all') {
                const now = new Date();
                const filterDate = new Date();

                switch (timeFilter) {
                    case 'today':
                        filterDate.setHours(0, 0, 0, 0);
                        break;
                    case 'week':
                        filterDate.setDate(now.getDate() - 7);
                        break;
                    case 'month':
                        filterDate.setMonth(now.getMonth() - 1);
                        break;
                }

                if (timeFilter !== 'all') {
                    filteredAlerts = filteredAlerts.filter(alert =>
                        new Date(alert.created_at) >= filterDate
                    );
                }
            }

            // Create markers
            filteredAlerts.forEach(alert => {
                const marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(alert.latitude),
                        lng: parseFloat(alert.longitude)
                    },
                    map: map,
                    title: alert.user_name,
                    icon: getMarkerIcon(alert.status, alert.is_emergency),
                    animation: (alert.status === 'pending' && alert.is_emergency) ? google.maps.Animation.BOUNCE : null
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: createInfoWindowContent(alert)
                });

                marker.addListener('click', () => {
                    // Close current info window
                    if (currentInfoWindow) {
                        currentInfoWindow.close();
                    }

                    infoWindow.open(map, marker);
                    currentInfoWindow = infoWindow;
                });

                // Store alert data with marker for easy access
                marker.alertData = alert;
                markers.push(marker);
            });

            // Update stats
            updateMapStats(filteredAlerts);

            // Fit map to markers if any exist
            if (markers.length > 0) {
                const bounds = new google.maps.LatLngBounds();
                markers.forEach(marker => bounds.extend(marker.getPosition()));
                map.fitBounds(bounds);

                // Don't zoom too much for single marker
                if (markers.length === 1) {
                    map.setZoom(15);
                }
            }
        }

        function getMarkerIcon(status, isEmergency) {
            const colors = {
                pending: isEmergency ? '#DC2626' : '#EF4444',
                in_progress: isEmergency ? '#D97706' : '#F59E0B',
                resolved: isEmergency ? '#059669' : '#10B981'
            };

            const color = colors[status] || colors.pending;
            const size = isEmergency ? 36 : 32;

            return {
                url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(`
                    <svg width="${size}" height="${size}" viewBox="0 0 ${size} ${size}" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="${size/2}" cy="${size/2}" r="${size/2 - 3}" fill="${color}" stroke="#fff" stroke-width="3"/>
                        <circle cx="${size/2}" cy="${size/2}" r="${size/4}" fill="#fff"/>
                        ${isEmergency ? `<text x="${size/2}" y="${size/2 + 3}" text-anchor="middle" fill="${color}" font-size="12" font-weight="bold">!</text>` : ''}
                    </svg>
                `)}`,
                scaledSize: new google.maps.Size(size, size),
                anchor: new google.maps.Point(size/2, size/2)
            };
        }

        function createInfoWindowContent(alert) {
            const mediaHtml = createMediaPreview(alert);
            const statusEmoji = {
                pending: '🔴',
                in_progress: '🟡',
                resolved: '🟢'
            };

            const statusText = {
                pending: 'Pendente',
                in_progress: 'Em Progresso',
                resolved: 'Resolvido'
            };

            return `
                <div class="p-4 max-w-sm">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-blue-600">${alert.user_name.charAt(0)}</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">${alert.user_name}</h3>
                            <p class="text-sm text-gray-600">${alert.user_phone}</p>
                        </div>
                        ${alert.is_emergency ? '<div class="ml-auto"><span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">EMERGÊNCIA</span></div>' : ''}
                    </div>

                    <div class="space-y-2 mb-3">
                        <div class="flex items-center">
                            <span class="text-lg mr-2">${statusEmoji[alert.status]}</span>
                            <span class="font-semibold text-gray-700">${statusText[alert.status]}</span>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-4 h-4 mt-1 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            <span class="text-sm text-gray-700">${alert.location}</span>
                        </div>

                        ${alert.message ? `
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mt-1 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span class="text-sm text-gray-700">${alert.message}</span>
                        </div>
                        ` : ''}

                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm text-gray-600">${new Date(alert.created_at).toLocaleString('pt-BR')}</span>
                        </div>
                    </div>

                    ${mediaHtml}

                    <div class="flex space-x-2 mt-3">
                        <button onclick="showAlertDetails(${alert.id})" class="flex-1 bg-blue-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-600 transition-colors">
                            Ver Detalhes
                        </button>
                        ${alert.status !== 'resolved' ? `
                        <button onclick="updateAlertStatus(${alert.id})" class="flex-1 bg-green-500 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-600 transition-colors">
                            Atualizar Status
                        </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        function createMediaPreview(alert) {
            let mediaHtml = '';

            if (alert.photo || alert.video || alert.audio) {
                mediaHtml += '<div class="mb-3"><div class="text-sm text-gray-600 mb-2">Mídia anexada:</div><div class="flex space-x-2">';

                if (alert.photo) {
                    mediaHtml += `<div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center cursor-pointer" onclick="showMedia('${alert.id}', 'photo')">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>`;
                }

                if (alert.video) {
                    mediaHtml += `<div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center cursor-pointer" onclick="showMedia('${alert.id}', 'video')">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>`;
                }

                if (alert.audio) {
                    mediaHtml += `<div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center cursor-pointer" onclick="showMedia('${alert.id}', 'audio')">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                        </svg>
                    </div>`;
                }

                mediaHtml += '</div></div>';
            }

            return mediaHtml;
        }

        function updateMapStats(filteredAlerts) {
            const stats = {
                total: filteredAlerts.length,
                pending: filteredAlerts.filter(a => a.status === 'pending').length,
                in_progress: filteredAlerts.filter(a => a.status === 'in_progress').length,
                resolved: filteredAlerts.filter(a => a.status === 'resolved').length,
                emergency: filteredAlerts.filter(a => a.is_emergency).length,
                today: filteredAlerts.filter(a => {
                    const alertDate = new Date(a.created_at);
                    const today = new Date();
                    return alertDate.toDateString() === today.toDateString();
                }).length
            };

            document.getElementById('map-total').textContent = stats.total;
            document.getElementById('map-pending').textContent = stats.pending;
            document.getElementById('map-progress').textContent = stats.in_progress;
            document.getElementById('map-resolved').textContent = stats.resolved;
            document.getElementById('map-emergency').textContent = stats.emergency;
            document.getElementById('map-today').textContent = stats.today;
        }

        function setupEventListeners() {
            // Filter change events
            document.getElementById('status-filter').addEventListener('change', loadMarkers);
            document.getElementById('time-filter').addEventListener('change', loadMarkers);
            document.getElementById('emergency-filter').addEventListener('change', loadMarkers);

            // Refresh button
            document.getElementById('refresh-map').addEventListener('click', () => {
                location.reload();
            });

            // Fullscreen toggle
            document.getElementById('fullscreen-map').addEventListener('click', toggleFullscreen);

            // Modal close
            document.getElementById('alert-modal').addEventListener('click', (e) => {
                if (e.target.id === 'alert-modal') {
                    closeModal();
                }
            });

            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        }

        function toggleFullscreen() {
            const mapContainer = document.getElementById('map').parentElement;
            const button = document.getElementById('fullscreen-map');

            if (!document.fullscreenElement) {
                mapContainer.requestFullscreen().then(() => {
                    document.getElementById('map').style.height = '100vh';
                    button.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    `;
                    google.maps.event.trigger(map, 'resize');
                });
            } else {
                document.exitFullscreen().then(() => {
                    document.getElementById('map').style.height = '600px';
                    button.innerHTML = `
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                    `;
                    google.maps.event.trigger(map, 'resize');
                });
            }
        }

        function showAlertDetails(alertId) {
            const alert = alertsData.find(a => a.id === alertId);
            if (!alert) return;

            const modalContent = `
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">Detalhes do Alerta</h2>
                        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome</label>
                                <p class="mt-1 text-sm text-gray-900">${alert.user_name}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telefone</label>
                                <p class="mt-1 text-sm text-gray-900">${alert.user_phone}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Localização</label>
                            <p class="mt-1 text-sm text-gray-900">${alert.location}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <p class="mt-1 text-sm text-gray-900">${getStatusText(alert.status)}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Prioridade</label>
                                <p class="mt-1 text-sm text-gray-900">${alert.is_emergency ? 'Emergência' : 'Normal'}</p>
                            </div>
                        </div>

                        ${alert.message ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mensagem</label>
                            <p class="mt-1 text-sm text-gray-900">${alert.message}</p>
                        </div>
                        ` : ''}

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Criação</label>
                            <p class="mt-1 text-sm text-gray-900">${new Date(alert.created_at).toLocaleString('pt-BR')}</p>
                        </div>

                        ${createDetailedMediaPreview(alert)}
                    </div>

                    <div class="flex space-x-3 mt-6">
                        ${alert.status !== 'resolved' ? `
                        <button onclick="updateAlertStatus(${alert.id})" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            Atualizar Status
                        </button>
                        ` : ''}
                        <button onclick="deleteAlert(${alert.id})" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                            Excluir
                        </button>
                        <button onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                            Fechar
                        </button>
                    </div>
                </div>
            `;

            document.getElementById('alert-modal-content').innerHTML = modalContent;
            document.getElementById('alert-modal').classList.remove('hidden');
        }

        function createDetailedMediaPreview(alert) {
            let mediaHtml = '';

            if (alert.photo || alert.video || alert.audio) {
                mediaHtml += '<div><label class="block text-sm font-medium text-gray-700 mb-2">Mídia Anexada</label><div class="grid grid-cols-3 gap-2">';

                if (alert.photo) {
                    mediaHtml += `
                        <div class="relative">
                            <img src="/alerts/${alert.id}/media/photo" alt="Foto" class="w-full h-24 object-cover rounded-lg cursor-pointer" onclick="showMediaFullscreen('${alert.id}', 'photo')">
                            <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">Foto</div>
                        </div>
                    `;
                }

                if (alert.video) {
                    mediaHtml += `
                        <div class="relative">
                            <video class="w-full h-24 object-cover rounded-lg cursor-pointer" onclick="showMediaFullscreen('${alert.id}', 'video')">
                                <source src="/alerts/${alert.id}/media/video" type="video/mp4">
                            </video>
                            <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">Vídeo</div>
                        </div>
                    `;
                }

                if (alert.audio) {
                    mediaHtml += `
                        <div class="relative bg-gray-100 rounded-lg p-4 flex items-center justify-center cursor-pointer" onclick="showMediaFullscreen('${alert.id}', 'audio')">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                            </svg>
                            <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">Áudio</div>
                        </div>
                    `;
                }

                mediaHtml += '</div></div>';
            }

            return mediaHtml;
        }

        function getStatusText(status) {
            const statusMap = {
                pending: 'Pendente',
                in_progress: 'Em Progresso',
                resolved: 'Resolvido'
            };
            return statusMap[status] || status;
        }

        function updateAlertStatus(alertId) {
            const alert = alertsData.find(a => a.id === alertId);
            if (!alert) return;

            const newStatus = prompt('Novo status (pending, in_progress, resolved):', alert.status);
            if (!newStatus || !['pending', 'in_progress', 'resolved'].includes(newStatus)) return;

            const notes = prompt('Observações (opcional):');

            fetch(`/alerts/${alertId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus,
                    notes: notes
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.status = newStatus;
                    loadMarkers();
                    closeModal();
                    alert('Status atualizado com sucesso!');
                } else {
                    alert('Erro ao atualizar status: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao atualizar status');
            });
        }

        function deleteAlert(alertId) {
            if (!confirm('Tem certeza que deseja excluir este alerta?')) return;

            fetch(`/alerts/${alertId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alertsData = alertsData.filter(a => a.id !== alertId);
                    loadMarkers();
                    closeModal();
                    alert('Alerta excluído com sucesso!');
                } else {
                    alert('Erro ao excluir alerta: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro ao excluir alerta');
            });
        }

        function showMedia(alertId, type) {
            window.open(`/alerts/${alertId}/media/${type}`, '_blank');
        }

        function showMediaFullscreen(alertId, type) {
            window.open(`/alerts/${alertId}/media/${type}`, '_blank');
        }

        function closeModal() {
            document.getElementById('alert-modal').classList.add('hidden');
        }

        function exportData() {
            const statusFilter = document.getElementById('status-filter').value;
            const timeFilter = document.getElementById('time-filter').value;
            const emergencyFilter = document.getElementById('emergency-filter').value;

            let params = new URLSearchParams();
            if (statusFilter !== 'all') params.append('status', statusFilter);
            if (timeFilter !== 'all') params.append('time_filter', timeFilter);
            if (emergencyFilter === 'emergency') params.append('emergency', '1');

            window.open(`/alerts/export?${params.toString()}`, '_blank');
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Add CSRF token to meta if not present
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }
        });
    </script>
</x-app-layout>