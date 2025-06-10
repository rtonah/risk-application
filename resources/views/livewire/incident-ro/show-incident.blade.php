{{-- resources/views/livewire/incident-ro/show-incident.blade.php --}}

<div>
    @if ($showModal)
        <div class="modal d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de l'incident #{{ $incident->id ?? '' }}</h5>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        @if ($incident)
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Titre :</strong>
                                    <p>{{ $incident->title }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Statut :</strong>
                                    <span class="badge {{
                                        $incident->status == 'ouvert' ? 'bg-danger' :
                                        ($incident->status == 'en cours' ? 'bg-warning text-dark' :
                                        ($incident->status == 'résolu' ? 'bg-info' : 'bg-success'))
                                    }}">{{ ucfirst($incident->status) }}</span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Priorité :</strong>
                                    <span class="badge {{
                                        $incident->priority == 'faible' ? 'bg-success' :
                                        ($incident->priority == 'moyenne' ? 'bg-warning text-dark' : 'bg-danger')
                                    }}">{{ ucfirst($incident->priority) }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Type d'incident :</strong>
                                    <p>{{ $incident->incident_type ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Origine :</strong>
                                    <p>{{ ucfirst($incident->origin) ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Localisation :</strong>
                                    <p>{{ $incident->location ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Description :</strong>
                                <p>{{ $incident->description }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>Impact Commercial :</strong>
                                <p>{{ $incident->business_impact ?? 'Aucun' }}</p>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Rapporté par :</strong>
                                    <p>{{ $incident->user->name ?? 'Inconnu' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Branche :</strong>
                                    <p>{{ $incident->branch->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Date de Rapport :</strong>
                                    <p>{{ $incident->reported_at ? $incident->reported_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Date de Résolution :</strong>
                                    <p>{{ $incident->resolved_at ? $incident->resolved_at->format('d/m/Y H:i') : 'Non résolu' }}</p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong>Détails de la résolution :</strong>
                                <p>{{ $incident->resolution_details ?? 'Non renseigné' }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>Pièce jointe :</strong>
                                @if ($incident->attachment_path)
                                    <button class="btn btn-sm btn-outline-primary" wire:click="downloadAttachment">
                                        <i class="fas fa-download me-2"></i> Télécharger la pièce jointe
                                    </button>
                                @else
                                    <p>Aucune pièce jointe.</p>
                                @endif
                                @if (session()->has('error'))
                                    <div class="alert alert-danger mt-2">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>

                            <h6>Historique des Actions</h6>
                            @if ($activities->isEmpty())
                                <p class="text-muted">Aucune activité enregistrée pour cet incident.</p>
                            @else
                                <div class="timeline mt-3">
                                    @foreach ($activities as $activity)
                                        <div class="timeline-item d-flex align-items-start mb-3">
                                            <div class="timeline-icon me-3">
                                                {{-- Vous pouvez personnaliser les icônes ici selon le type d'activité --}}
                                                @if ($activity->type == 'created')
                                                    <i class="fas fa-plus-circle text-success"></i>
                                                @elseif ($activity->type == 'status_updated')
                                                    <i class="fas fa-redo text-warning"></i>
                                                @elseif ($activity->type == 'resolved')
                                                    <i class="fas fa-check-double text-info"></i>
                                                @else
                                                    <i class="fas fa-info-circle text-muted"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content flex-grow-1">
                                                <small class="text-muted">{{ $activity->created_at->format('d/m/Y H:i') }} par {{ $activity->user->name ?? 'Système' }}</small>
                                                <p class="mb-0">{{ $activity->description }}</p>
                                                @if ($activity->old_value || $activity->new_value)
                                                    <small class="text-secondary">
                                                        @if ($activity->old_value && isset($activity->old_value['status']) && isset($activity->new_value['status']))
                                                            Statut: {{ ucfirst($activity->old_value['status']) }} <i class="fas fa-arrow-right mx-1"></i> {{ ucfirst($activity->new_value['status']) }}
                                                        @endif
                                                        {{-- Ajoutez d'autres comparaisons si vous stockez plus de données dans old_value/new_value --}}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <p>Aucun incident sélectionné pour l'affichage.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>