<div class="card border-0 shadow mb-4">
    <div class="card-header">
        <h2 class="h5 mb-0">📊 Dashboard des Vérifications</h2>
    </div>

    <div class="card-body">

        {{-- graphique de l'activité de recherche (par exemple, le nombre de vérifications faites chaque jour durant les 7 derniers jours). --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Activité de vérification cette semaine</h5>
            </div>
            <div class="card-body">
                <canvas id="weeklyChart" height="100"></canvas>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    class="form-control"
                    placeholder="🔍 Recherche numéro de prêt..."
                />
            </div>
            <div class="col-md-4">
                <select wire:model="statusFilter" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="1">✅ Conformes</option>
                    <option value="0">❌ Non conformes</option>
                </select>
            </div>
        </div>

        <!-- Tableau -->
       <div class="table-responsive shadow rounded p-3 bg-white mt-4">
            <table class="table table-hover align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th scope="col">📅 Date</th>
                        <th scope="col">📌 Numéro Prêt</th>
                        <th scope="col">👤 Utilisateur</th>
                        <th scope="col">✅ Grace</th>
                        <th scope="col">📈 FGMD</th>
                        <th scope="col">📤 Standing Instruction</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($verifications as $v)
                        <tr>
                            <td class="text-center">{{ $v->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $v->loan_number }}</td>
                            <td>
                                <img 
                                    src="{{ $v->user->profile_photo_path ? asset('storage/' . $v->user->profile_photo_path) : asset('assets/img/team/avatar.jpg') }}" 
                                    class="avatar rounded-circle me-3" 
                                    alt="Avatar">
                                {{ $v->user->matricule ?? '-' }}
                            </td>
                            <td class="text-center">
                                @if($v->grace_capital_conform && $v->grace_interest_conform && $v->grace_interest_charged_conform)
                                    <span >✅ Conforme</span>
                                @else
                                    <span >❌ Non conforme</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($v->fgmd_conform)
                                    <span class="text-success">✅</span>
                                @else
                                    <span class="text-danger">❌</span>
                                @endif
                                <br>
                                <small class="text-muted">{{ $v->fgmd_actual_rate }} vs {{ $v->fgmd_expected_rate }}</small>
                            </td>
                            <td class="text-center">
                                {{ $v->standing_instruction_activated ? '✅ Activée' : '❌ Non activée' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Aucune vérification trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
            {{-- Infos --}}
            <div class="fw-normal small mt-4 mt-lg-0">
                Affichage de {{ $verifications->firstItem() }} à {{ $verifications->lastItem() }} sur {{ $verifications->total() }} résultats
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $verifications->links('vendor.pagination.bootstrap-5-sm') }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:load', function () {
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($weeklyLabels),
                datasets: [{
                    label: 'Vérifications',
                    data: @json($weeklyCounts),
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

