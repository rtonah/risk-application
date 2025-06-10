<div class="col-12 mb-4">
    <div class="card border-0 shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-gray">Utilisateurs connectés ({{ $sessions->count() }})</h6>
            <span class="badge bg-success">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
        <div class="card-body px-3 py-2">
            @if($sessions->isEmpty())
                <div class="text-muted">Aucun utilisateur connecté actuellement.</div>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($sessions as $session)
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold text-dark">
                                    <i class="fas fa-user me-2 text-primary"></i> {{ $session->user->matricule ?? 'Inconnu' }} - {{ $session->user->last_name ?? 'Inconnu' }}
                                </div>
                                <div class="small text-muted">
                                    IP : {{ $session->ip_address }}<br>
                                    Navigateur : {{ $session->user_agent }}</span>
                                </div>
                            </div>
                            <span class="badge bg-light text-dark small">
                                Dernière activité : {{ $session->last_activity->diffForHumans() }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

