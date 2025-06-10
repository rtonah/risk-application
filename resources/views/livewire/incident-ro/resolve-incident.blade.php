{{-- resources/views/livewire/incident-ro/resolve-incident.blade.php --}}

<div>
    @if ($showModal)
        <div class="modal d-block" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Résoudre/Clôturer l'incident #{{ $incident->id ?? '' }}</h5>
                        <button type="button" class="btn-close" aria-label="Close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        @if ($incident)
                            <p><strong>Titre :</strong> {{ $incident->title }}</p>
                            <p><strong>Statut actuel :</strong>
                                <span class="badge {{
                                    $incident->status == 'ouvert' ? 'bg-danger' :
                                    ($incident->status == 'en cours' ? 'bg-warning text-dark' :
                                    ($incident->status == 'résolu' ? 'bg-info' : 'bg-success'))
                                }}">{{ ucfirst($incident->status) }}</span>
                            </p>

                            <form wire:submit.prevent="resolveIncident">
                                <div class="mb-3">
                                    <label for="resolutionDetails" class="form-label">Détails de la résolution / Actions correctives</label>
                                    <textarea wire:model.defer="resolutionDetails" class="form-control @error('resolutionDetails') is-invalid @enderror" id="resolutionDetails" rows="6" placeholder="Décrivez les actions prises pour résoudre l'incident..."></textarea>
                                    @error('resolutionDetails') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Changer le statut en :</label>
                                    <select wire:model.defer="status" class="form-select @error('status') is-invalid @enderror" id="status">
                                        <option value="">Sélectionnez un statut</option>
                                        <option value="résolu">Résolu</option>
                                        <option value="clôturé">Clôturé</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                @if (session()->has('message'))
                                    <div class="alert alert-success mt-3">
                                        {{ session('message') }}
                                    </div>
                                @endif
                            </form>
                        @else
                            <p>Aucun incident sélectionné.</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Annuler</button>
                        <button type="submit" class="btn btn-primary" wire:click="resolveIncident">
                            <span wire:loading.remove wire:target="resolveIncident">Enregistrer la résolution</span>
                            <span wire:loading wire:target="resolveIncident">Enregistrement...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>