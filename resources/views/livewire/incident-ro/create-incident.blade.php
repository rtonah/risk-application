<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-inner--icon"><i class="fas fa-check-circle"></i></span>
            <span class="alert-inner--text">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <h5 class="mb-4">Informations sur l'incident</h5>
                    <form wire:submit.prevent="submit" enctype="multipart/form-data">
                        <div class="row g-3">
                            {{-- Première colonne --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.debounce.500ms="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Ex: Panne réseau">
                                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="location" class="form-label">Lieu</label>
                                    <input type="text" wire:model.debounce.500ms="location" class="form-control" id="location" placeholder="Ex: Bureau 201">
                                </div>

                                <div class="mb-3">
                                    <label for="incident_type" class="form-label">Type d’incident</label>
                                    <input type="text" wire:model.debounce.500ms="incident_type" class="form-control" id="incident_type" placeholder="Ex: Logiciel, Matériel">
                                </div>
                            </div>

                            {{-- Deuxième colonne --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea wire:model.debounce.500ms="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="4" placeholder="Décrivez l'incident en détail..."></textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="business_impact" class="form-label">Impact métier</label>
                                    <textarea wire:model.debounce.500ms="business_impact" class="form-control" id="business_impact" rows="4" placeholder="Quel est l'impact sur les opérations ?"></textarea>
                                </div>
                            </div>

                            {{-- Troisième colonne --}}
                            <div class="col-lg-4 col-md-6">
                                <div class="mb-3">
                                    <label for="origin" class="form-label">Origine</label>
                                    <select wire:model="origin" class="form-select" id="origin">
                                        <option value="">-- Choisir --</option>
                                        <option value="interne">Interne</option>
                                        <option value="externe">Externe</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priorité</label>
                                    <select wire:model="priority" class="form-select" id="priority">
                                        <option value="faible">Faible</option>
                                        <option value="moyenne">Moyenne</option>
                                        <option value="élevée">Élevée</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="attachment" class="form-label">Pièce jointe</label>
                                    <input type="file" wire:model="attachment" class="form-control @error('attachment') is-invalid @enderror" id="attachment">
                                    @error('attachment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    @if ($attachment)
                                        <small class="form-text text-muted mt-2">Fichier sélectionné : {{ $attachment->getClientOriginalName() }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary animate-hover"><i class="fas fa-paper-plane me-2"></i>Soumettre l'incident</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>