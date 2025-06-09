<div class="container my-4">
    {{-- Message de succès --}}
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    {{-- Formulaire de création de ticket --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Créer un nouveau ticket</h5>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="submit" class="row g-3">
                {{-- Champ Titre --}}
                <div class="col-8">
                    <label for="title" class="form-label">Titre <span class="text-danger">*</span></label>
                    <input wire:model.defer="title" type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Saisir le titre du ticket">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Champ Fichier --}}
                <div class="col-4">
                    <label for="attachments" class="form-label">Pièces jointes (PDF ou images)</label>
                    <input wire:model="attachments" type="file" class="form-control @error('attachments.*') is-invalid @enderror" id="attachments" multiple>
                    @error('attachments.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Champ Description --}}
                <div class="col-12">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea wire:model.defer="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="4" placeholder="Décrivez le problème ou la demande"></textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                {{-- Champ Catégorie --}}
                <div class="col-md-6">
                    <label for="category" class="form-label">Catégorie <span class="text-danger">*</span></label>
                    <select wire:model="category" class="form-select @error('category') is-invalid @enderror" id="category">
                        <option value="">-- Choisir une catégorie --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Champ Priorité --}}
                <div class="col-md-6">
                    <label for="priority" class="form-label">Priorité <span class="text-danger">*</span></label>
                    <select wire:model="priority" class="form-select @error('priority') is-invalid @enderror" id="priority">
                        @foreach ($priorities as $prio)
                            <option value="{{ $prio }}">{{ ucfirst($prio) }}</option>
                        @endforeach
                    </select>
                    @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                {{-- Bouton de soumission --}}
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle me-1"></i> Créer le ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
