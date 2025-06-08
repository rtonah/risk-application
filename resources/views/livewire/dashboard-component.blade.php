<div>
    {{-- Style personnalisé pour la légende du graphique Chartist --}}
    <style>
        .ct-series-a .ct-bar { stroke: #FFDE21 !important; } /* Mvola */
        .ct-series-b .ct-bar { stroke: #e40000 !important; }  /* Airtel */
    </style>

    <div>
    {{-- Formulaire de filtre --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Rechercher un compte ou un tel"
                wire:model.debounce.500ms="search">
        </div>
    </div>

    {{-- Résumé des résultats uniquement si recherche ≥ 7 caractères --}}
    @if(strlen($search) >= 7 && $summary)
        <div class="alert alert-info">
            <strong>Résumé :</strong><br>
            <ul class="mb-0">
                <li><strong>Transaction aujourd'hui :</strong> {{ $summary['today'] }}</li>
                <li><strong>Total :</strong> {{ $summary['total'] }}</li>
                <li><strong>Nombre de transactions :</strong> {{ $summary['count'] }}</li>
            </ul>
        </div>
    @endif
</div>


    {{-- ====================== Cartes Opérateurs ====================== --}}
    <div class="row">
        @php
            // Configuration des opérateurs : nom, image, couleur, et clé dans le tableau
            $operators = [
                'mvola' => ['label' => 'Mvola', 'img' => 'mvola.png', 'color' => '#FFDE21'],
                'airtel' => ['label' => 'Airtel', 'img' => 'airtel.png', 'color' => '#e40000'],
                'orange' => ['label' => 'Orange', 'img' => 'orange.png', 'color' => '#f57c00']
            ];
        @endphp

        {{-- Boucle sur les opérateurs pour générer les cartes --}}
        @foreach ($operators as $key => $operator)
            <div class="col-12 col-sm-6 col-xl-4 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row d-block d-xl-flex align-items-center">
                            {{-- Icône opérateur --}}
                            <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                <div class="icon-shape icon-shape-warning rounded me-4 me-sm-0 d-flex align-items-center justify-content-center">
                                    <img src="{{ asset('assets/img/' . $operator['img']) }}" alt="{{ $operator['label'] }}" style="width: 40px; height: 40px;">
                                </div>
                                {{-- Affichage mobile --}}
                                <div class="d-sm-none">
                                    <h2 class="h5">{{ $operator['label'] }} (Aujourd'hui)</h2>
                                    <h3 class="fw-extrabold mb-1">{{ number_format($dailyStats[$key]['sum'], 0, ',', ' ') }} Ar</h3>
                                </div>
                            </div>

                            {{-- Affichage desktop --}}
                            <div class="col-12 col-xl-7 px-xl-0">
                                <div class="d-none d-sm-block">
                                    <h2 class="h6 text-gray-400 mb-0">{{ $operator['label'] }} (Aujourd'hui)</h2>
                                    <h3 class="fw-extrabold mb-2">{{ number_format($dailyStats[$key]['sum'], 0, ',', ' ') }} Ar</h3>
                                </div>
                                <small class="d-flex align-items-center text-gray-500">
                                    Transactions : {{ $dailyStats[$key]['count'] }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ====================== Évolution Hebdomadaire ====================== --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow" style="background-color: #fac0b9">
            <div class="card-header d-sm-flex flex-row align-items-center">
                <div>
                    <div class="fs-5 fw-normal mb-2">Évolution hebdomadaire</div>
                    <h2 class="fs-3 fw-extrabold">{{ $weeklyStats['total'] ?? '--' }} Ar</h2>
                    <div class="small mt-2">
                        <span class="fw-normal me-2">Semaine en cours</span>
                        <span class="fas fa-angle-up text-success"></span>
                        <span class="text-success fw-bold">{{ $weeklyStats['percent'] ?? '--' }}%</span>
                    </div>
                </div>
                <div class="d-flex ms-auto">
                    <a href="#" class="btn btn-secondary btn-sm me-2">Semaine</a>
                    <a href="#" class="btn btn-sm">Jour</a>
                </div>
            </div>
            <div class="card-body p-2">
                <canvas id="weeklyChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- ====================== Top 15 Clients ====================== --}}
    <div class="col-12 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-gray mb-1">Top 15 clients (par montant, sur 7 jours)</h6>
                </div>
                <div class="text-end d-flex align-items-center">
                    <span class="dot rounded-circle me-2" style="background-color: #FFDE21;"></span><small class="me-3">Mvola</small>
                    <span class="dot rounded-circle me-2" style="background-color: #e40000;"></span><small>Airtel</small>
                </div>
            </div>
            <div class="card-body py-2 px-3">
                <div id="topClientsChart" class="ct-chart" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    {{-- ====================== Scripts ====================== --}}
    @push('scripts')
        {{-- Chart.js pour le graphique hebdomadaire --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('livewire:load', () => {
                let weeklyChart = null;

                function renderChart(labels, dataMvola, dataAirtel) {
                    const ctx = document.getElementById('weeklyChart').getContext('2d');

                    // Si un chart existe déjà, le détruire
                    if (weeklyChart) {
                        weeklyChart.destroy();
                    }

                    weeklyChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Montants Mvola (Ar)',
                                    data: dataMvola,
                                    borderColor: '#FFDE21',
                                    backgroundColor: 'rgba(255, 222, 33, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                },
                                {
                                    label: 'Montants Airtel (Ar)',
                                    data: dataAirtel,
                                    borderColor: '#e40000',
                                    backgroundColor: 'rgba(228, 0, 0, 0.1)',
                                    fill: true,
                                    tension: 0.4,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#4B5563'
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    ticks: { color: '#6B7280' },
                                    grid: { display: false }
                                },
                                y: {
                                    ticks: { color: '#6B7280' },
                                    grid: { color: '#E5E7EB' }
                                }
                            }
                        }
                    });
                }

                // Premier rendu initial
                renderChart(@json($weeklyLabels), @json($weeklyTotalsMvola), @json($weeklyTotalsAirtel));

                // Mettre à jour à chaque événement Livewire qui met à jour les données (exemple)
                Livewire.hook('message.processed', (message, component) => {
                    // Récupérer les données mises à jour depuis le composant Livewire (si elles sont exposées en propriété publique)
                    const labels = @this.weeklyLabels;
                    const dataMvola = @this.weeklyTotalsMvola;
                    const dataAirtel = @this.weeklyTotalsAirtel;

                    renderChart(labels, dataMvola, dataAirtel);
                });
            });

        </script>

        {{-- Chartist pour le graphique Top Clients --}}
        <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend/chartist-plugin-legend.min.js"></script>
        <script>
            document.addEventListener('livewire:load', () => {
                let topClientsChartInstance = null;

                function renderTopClientsChart(labels, series) {
                    // Supprimer le contenu du conteneur pour éviter empilement
                    const chartContainer = document.getElementById('topClientsChart');
                    chartContainer.innerHTML = '';

                    // Créer une nouvelle instance Chartist
                    topClientsChartInstance = new Chartist.Bar(chartContainer, {
                        labels: labels,
                        series: series,
                    }, {
                        seriesBarDistance: 10,
                        axisY: {
                            onlyInteger: true,
                            offset: 70
                        },
                        height: '400px',
                    });
                }

                // Premier rendu initial
                renderTopClientsChart(@json($chartTopClients['labels']), @json($chartTopClients['series']));

                // À chaque update Livewire, récupérer les données mises à jour et rerender
                Livewire.hook('message.processed', (message, component) => {
                    // Récupérer les nouvelles données (à exposer en propriétés publiques dans ton composant Livewire)
                    const labels = @this.chartTopClients.labels;
                    const series = @this.chartTopClients.series;

                    renderTopClientsChart(labels, series);
                });
            });

        </script>
    @endpush
</div>
