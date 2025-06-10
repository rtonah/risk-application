{{-- --------------------------------------------- --}}
{{-- Tableau de stats des tickets (Cartes responsives) --}}
{{-- --------------------------------------------- --}}
<div class="row">
    @foreach ($ticketStats as $stat)
        <div class="col-12 col-sm-6 col-xl-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex align-items-center">
                        
                        {{-- Icône + affichage mobile --}}
                        <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-{{ $stat['color'] }} rounded me-4 me-sm-0 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('assets/img/' . $stat['icon']) }}" alt="{{ $stat['label'] }}" style="width: 40px; height: 40px;">
                            </div>

                            {{-- Affichage spécifique mobile --}}
                            <div class="d-sm-none">
                                <h2 class="h5">{{ $stat['label'] }}</h2>
                                <h3 class="fw-extrabold mb-1">{{ $stat['count'] }}</h3>
                            </div>
                        </div>

                        {{-- Texte et chiffres affichés uniquement sur desktop --}}
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0">{{ $stat['label'] }}</h2>
                                <h3 class="fw-extrabold mb-2">{{ $stat['count'] }}</h3>
                            </div>
                            <small class="d-flex align-items-center text-gray-500">
                                Total {{ strtolower($stat['label']) }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- --------------------------------------------- --}}
{{-- Dashboard IT : Graphiques d'évolution et répartition --}}
{{-- --------------------------------------------- --}}
<div class="row">
    {{-- Graphique Évolution des incidents (7 derniers jours) avec titre plus petit --}}
    <div class="col-12 col-md-8 mb-4">
        <div class="card border-0 shadow" style="background-color: #fdeeee;">
            <div class="card-header d-sm-flex flex-row align-items-center flex-0">
                <div class="d-block">
                    {{-- Titre réduit en taille avec fs-6 (Bootstrap) --}}
                    <div class="fs-6 fw-normal mb-2">Évolution des incidents (7 derniers jours)</div>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="position-relative" style="height: 300px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphique Répartition par catégorie (en %) avec titre plus petit --}}
    <div class="col-12 col-md-4 mb-4">
        <div class="card border-0 shadow" style="background-color: #fdeeee;">
            <div class="card-header d-sm-flex flex-row align-items-center flex-0">
                <div class="d-block">
                    {{-- Titre réduit en taille avec fs-6 --}}
                    <div class="fs-6 fw-normal mb-2">Répartition par catégorie</div>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="position-relative" style="height: 300px;">
                    <canvas id="donutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- --------------------------------------------- --}}
{{-- KPI par technicien : Performance --}}
{{-- --------------------------------------------- --}}

<div class="row">
    <div class="col-12 col-md-8 mb-4">
        <div class="card border-0 shadow" style="background-color: #fdeeee;">
            <div class="card-header d-sm-flex flex-row align-items-center flex-0">
                <div class="d-block">
                    <div class="fs-6 fw-normal mb-2">Performance des techniciens</div>
                </div>
            </div>
            <div class="card-body p-2 d-flex justify-content-center">
                <div class="position-relative" style="width: 100%; height: 300px;">
                    <canvas id="technicianChart" style="width: 100%; height: 100%; display: block;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4 mb-4">
        <div class="card border-0 shadow" style="background-color: #fdeeee;">
            <div class="card-header d-sm-flex flex-row align-items-center flex-0">
                <div class="d-block">
                    <div class="fs-6 fw-normal mb-2">Répartition par status du tickets</div>
                </div>
            </div>
            <div class="card-body p-2 d-flex justify-content-center">
                <div class="position-relative" style="max-width: 280px; height: 300px; margin: 0 auto;">
                    <canvas id="ticketStatusChart" style="width: 100%; height: 100%; display: block;"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<livewire:connected-users />


{{-- --------------------------------------------- --}}
{{-- Liste des tickets récents --}}
{{-- --------------------------------------------- --}}
<div class="container mt-2">
    <div class="table-responsive shadow rounded p-3 bg-white">
        <h2 class="h5 fw-bold mb-3">Tickets récents</h2>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col">Titre</th>
                    <th scope="col">Catégorie</th>
                    <th scope="col">Priorité</th>
                    <th scope="col">Statut</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentTickets as $ticket)
                    <tr>
                        <td><span class="fw-bold">{{ $ticket->title }}</span></td>
                        <td><div class="text-muted">{{ $ticket->category }}</div></td>
                        <td>
                            @php
                                // Définition des classes Bootstrap pour la priorité
                                $priorityBadges = [
                                    'urgent' => 'bg-danger',
                                    'très urgent' => 'bg-danger',
                                    'moyenne' => 'bg-warning text-dark',
                                    'basse' => 'bg-secondary',
                                    'normal' => 'bg-success',
                                ];
                                $priorityClass = $priorityBadges[strtolower($ticket->priority)] ?? 'bg-secondary';
                            @endphp
                            <span class="badge {{ $priorityClass }}">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </td>
                        <td>
                            @php
                                // Badge couleur selon statut
                                $statusClass = strtolower($ticket->status) === 'open' ? 'bg-success' : 'bg-secondary';
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($ticket->status ?? 'ouvert') }}
                            </span>
                        </td>
                        <td><span class="fw-bold">{{ $ticket->created_at->format('d/m/Y H:i') }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucun ticket trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- --------------------------------------------- --}}
{{-- Scripts Chart.js --}}
{{-- Utilisation de @once pour éviter les doublons --}}
{{-- --------------------------------------------- --}}
@once
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Graphique en barre : Évolution des tickets
            new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($ticketsByDate->keys()) !!},
                    datasets: [{
                        label: 'Tickets',
                        data: {!! json_encode($ticketsByDate->values()) !!},
                        backgroundColor: '#3B82F6',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Graphique donut : Répartition par catégorie
            new Chart(document.getElementById('donutChart'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($ticketsByCategory->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($ticketsByCategory->values()) !!},
                        backgroundColor: ['#60A5FA', '#FBBF24', '#F87171', '#34D399', '#A78BFA']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#374151' }
                        }
                    }
                }
            });

            // Graphique barres : Performance techniciens
            const ctxTech = document.getElementById('technicianChart').getContext('2d');
            new Chart(ctxTech, {
                type: 'bar',
                data: {
                    labels: @json($technicianKpiChart['labels']),
                    datasets: [
                        {
                            label: 'Assignés',
                            data: @json($technicianKpiChart['datasets'][0]['data']),
                            backgroundColor: 'rgba(255, 99, 132, 0.7)'
                        },
                        {
                            label: 'Clôturés',
                            data: @json($technicianKpiChart['datasets'][1]['data']),
                            backgroundColor: 'rgba(75, 192, 192, 0.7)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            title: { display: true, text: 'Techniciens' }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Tickets' }
                        }
                    }
                }
            });


            // Graphique pour les statuts ticket
            const ctx = document.getElementById('ticketStatusChart').getContext('2d');

            const ticketStatusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($ticketStatusChart['labels']),
                    datasets: [{
                        label: 'Tickets par statut',
                        data: @json($ticketStatusChart['data']),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',    // rouge - open
                            'rgba(255, 206, 86, 0.7)',    // jaune - in_progress
                            'rgba(75, 192, 192, 0.7)'     // vert - closed
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });

            // Optionnel : Recharger le graphique à chaque update Livewire si besoin
            Livewire.on('refreshTicketStatusChart', () => {
                ticketStatusChart.data.datasets[0].data = @json($ticketStatusChart['data']);
                ticketStatusChart.update();
            });
            });
    </script>
    @endpush
@endonce
