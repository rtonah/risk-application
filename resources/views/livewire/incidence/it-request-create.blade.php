<div class="container my-4">
    @if($showTicket && $selectedTicket)
        <!-- Détail du ticket centré sur la page -->
        <div class="card mb-4 mx-auto col-12 col-lg-10 shadow rounded">
            
            <!-- En-tête du ticket avec bouton de retour -->
            <div class="card-header d-flex justify-content-between align-items-center bg-light mb-3">
                <h5 class="mb-0">Détails du ticket #{{ $selectedTicket->id }}</h5>
                <button class="btn btn-outline-secondary btn-sm" wire:click="backToList">Retour à la liste</button>
            </div>

            <style>
                .card.custom-bg {
                    background-color: #f8f9fa; /* Gris très clair */
                    border-radius: 0.5rem;
                    border: 1px solid #dee2e6;
                    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
                }
            </style>

            <!-- Corps principal contenant les infos du ticket -->
            <div class="card-body row justify-content-center mb-3">

                <!-- Carte de gauche : Détails du ticket -->
                <div class="col-md-8">
                    <div class="card custom-bg shadow-sm h-100 border-primary">
                        <div class="card-body">
                            <h4 class="text-center mb-4 text-primary fw-bold">{{ $selectedTicket->title }}</h4>

                            <div class="mb-3">
                                <strong>Description :</strong>
                                <p class="mb-1 text-secondary">{{ $selectedTicket->description }}</p>
                            </div>
                            <div class="mb-2">
                                <strong>Catégorie :</strong> <span class="text-capitalize">{{ $selectedTicket->category }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Priorité :</strong> 
                                <span class="badge 
                                    {{ $selectedTicket->priority === 'très urgent' ? 'bg-danger' : ($selectedTicket->priority === 'normal' ? 'bg-success' : 'bg-warning text-dark') }}">
                                    {{ ucfirst($selectedTicket->priority) }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong>Status actuel :</strong> 
                                <span class="badge 
                                    {{ $selectedTicket->status === 'open' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($selectedTicket->status) }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong>Assigné à :</strong> <span class="text-info">{{ $selectedTicket->assignedTo->first_name ?? '-' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Créé par :</strong> <span class="text-info">{{ $selectedTicket->user->first_name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte de droite : Pièces jointes -->
                <div class="col-md-4">
                    <div class="card custom-bg shadow-sm h-100 border-primary">
                        <div class="card-body d-flex flex-column align-items-center">
                            <h5 class="text-center mb-4 text-primary fw-semibold">📎 Pièces jointes</h5>

                            <div class="row justify-content-center w-100">
                                @forelse ($selectedTicket->files as $file)
                                    <div class="col-6 mb-3 text-center">
                                        @if (Str::endsWith(strtolower($file->filename), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <a href="{{ $file->url }}" target="_blank" class="d-block overflow-hidden rounded shadow-sm" style="max-height: 180px;">
                                                <img src="{{ $file->url }}" alt="Aperçu" class="img-fluid" style="object-fit: cover; max-height: 180px; width: 100%;">
                                            </a>
                                        @else
                                            <a href="{{ $file->url }}" target="_blank" class="d-block p-3 border rounded text-decoration-none bg-light text-truncate" title="{{ $file->filename }}">
                                                <!-- Icône fichier PDF/Document -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#d6336c" class="me-2" viewBox="0 0 16 16">
                                                    <path d="M5.5 0a.5.5 0 0 0-.5.5V1H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-3V.5a.5.5 0 0 0-.5-.5h-5zM6 1v1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-4V1H6z"/>
                                                    <path d="M4.5 7.5A.5.5 0 0 1 5 7h1a.5.5 0 0 1 0 1H5v1h1a.5.5 0 0 1 0 1H4.5a.5.5 0 0 1-.5-.5v-2z"/>
                                                </svg>
                                                Voir le fichier
                                            </a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="col-12 text-center text-muted py-4">
                                        <!-- Icône PDF seule avec texte -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#6c757d" class="mb-2" viewBox="0 0 16 16">
                                            <path d="M5.5 0a.5.5 0 0 0-.5.5V1H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-3V.5a.5.5 0 0 0-.5-.5h-5zM6 1v1H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1h-4V1H6z"/>
                                            <path d="M4.5 7.5A.5.5 0 0 1 5 7h1a.5.5 0 0 1 0 1H5v1h1a.5.5 0 0 1 0 1H4.5a.5.5 0 0 1-.5-.5v-2z"/>
                                        </svg>
                                        <div>Aucun fichier attaché</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Section forum de discussion -->
<div class="card-footer bg-white">
    <h5 class="text-center my-4">🗣️ Discussion autour du ticket</h5>

    <!-- Liste des commentaires -->
    <div class="forum-comments" style="max-height: 600px; overflow-y: auto;">
        @forelse($selectedTicket->comments as $comment)
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <img 
                                src="{{ $comment->user->profile_photo_path 
                                    ? asset('storage/' . $comment->user->profile_photo_path)
                                    : asset('assets/img/team/avatar.jpg') }}" 
                                class="rounded-circle me-3" width="45" height="45" alt="Avatar">
                            <div>
                                <strong class="d-block">{{ $comment->user->last_name ?? 'Utilisateur inconnu' }}</strong>
                                <span class="text-muted small">{{ $comment->user->matricule ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }} ({{ $comment->created_at->format('d/m/Y H:i') }})</small>
                    </div>

                    <p class="mb-2">{{ $comment->message }}</p>

                    {{-- Optionnel : Boutons d'action --}}
                    <div class="text-end">
                        <button class="btn btn-sm btn-outline-secondary me-2" disabled>Répondre</button>
                        @if(auth()->id() === $comment->user_id)
                            <button wire:click="confirmDeleteComment({{ $comment->id }})" class="btn btn-danger btn-sm">
                                Supprimer
                            </button>

                            {{-- <button class="btn btn-sm btn-outline-danger" wire:click="deleteComment({{ $comment->id }})">Supprimer</button> --}}
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted text-center">Aucun commentaire pour ce ticket pour l’instant.</p>
        @endforelse
    </div>

    <!-- Formulaire de publication -->
    <form wire:submit.prevent="addComment" class="mt-4">
        <div class="mb-3">
            <label for="commentContent" class="form-label">Ajouter un nouveau commentaire :</label>
            <textarea wire:model.defer="commentContent" class="form-control" id="commentContent" rows="4" placeholder="Votre message..."></textarea>
            @error('commentContent') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Publier le commentaire</button>
        </div>
    </form>
</div>


        <!-- END  -->

    @else
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

    {{-- Table des tickets créer par l'utilisateur seuelment :  --}}
    <!-- Conteneur de la table, rendue responsive pour un bon affichage sur mobile -->
    <div>
        <div class="table-responsive shadow rounded p-3 bg-white mt-4">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Avancement</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>
                                <span class="fw-bold">{{ $ticket->title }}</span><br>
                                <small class="text-muted">
                                    <span class="badge bg-light text-muted border">
                                        <i class="fas fa-tag me-1"></i> {{ $ticket->category }}
                                    </span>
                                    <span class="badge bg-light text-muted border">
                                        <i class="fas fa-exclamation me-1"></i> {{ $ticket->priority }}
                                    </span>
                                </small>
                            </td>
                            @php
                                \Carbon\Carbon::setLocale('fr');
                                $created = $ticket->created_at;
                                $daysDiff = $created->diffInDays(now());
                            @endphp

                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div>
                                            <strong>Créé le :</strong> {{ $created->format('d/m/Y H:i') }}
                                        </div>
                                        <small class="d-block mt-1">
                                            <i class="fas fa-clock me-1 text-muted"></i>
                                            @if ($daysDiff > 7)
                                                <span class="badge bg-danger">Ancien : {{ $daysDiff }} jours</span>
                                            @else
                                                <span>{{ $created->diffForHumans() }}</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </td>


                            <td>
                                <span class="badge 
                                    {{ $ticket->status === 'open' ? 'bg-success' : 
                                    ($ticket->status === 'in_progress' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td>
                                <button wire:click="show({{ $ticket->id }})" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button onclick="Livewire.emit('confirmDelete', {{ $ticket->id }})" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun ticket trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                {{-- Infos --}}
                <div class="fw-normal small mt-4 mt-lg-0">
                    Affichage de {{ $tickets->firstItem() }} à {{ $tickets->lastItem() }} sur {{ $tickets->total() }} résultats
                </div>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
                    {{ $tickets->links('vendor.pagination.bootstrap-5-sm') }}
                </div>
            </div>
        </div>
    </div>

@endif
</div>

{{-- Message de succès --}}
<script>
    window.addEventListener('notify', event => {
        const notyf = new Notyf({
            duration: 3000,  // Durée de la notification (ms)
            position: {
                x: 'right',
                y: 'top',
            }
        });

        if (event.detail.type === 'success') {
            notyf.success(event.detail.message);
        } else if (event.detail.type === 'error') {
            notyf.error(event.detail.message);
        }
    });

    window.addEventListener('show-delete-confirmation-comment', event => {
        Swal.fire({
            title: 'Confirmer la suppression',
            text: "Êtes-vous sûr de vouloir supprimer ce commentaire ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then(result => {
            if (result.isConfirmed) {
                Livewire.emit('deleteComment', event.detail.id);
            }
        });
    });
</script>

