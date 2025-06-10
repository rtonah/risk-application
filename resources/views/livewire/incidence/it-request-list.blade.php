<div>
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
                    <div class="card custom-bg shadow-sm h-100">
                        <div class="card-body">
                            <h4 class="text-center mb-4">{{ $selectedTicket->title }}</h4>

                            <div class="mb-3">
                                <strong>Description :</strong>
                                <p class="mb-1">{{ $selectedTicket->description }}</p>
                            </div>
                            <div class="mb-2">
                                <strong>Catégorie :</strong> {{ ucfirst($selectedTicket->category) }}
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
                                    {{ $selectedTicket->status === 'open' ? 'bg-success' : 'bg-dark' }}">
                                    {{ ucfirst($selectedTicket->status) }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong>Assigné à :</strong> {{ $selectedTicket->assignedTo->first_name ?? '-' }}
                            </div>
                            <div class="mb-2">
                                <strong>Créé par :</strong> {{ $selectedTicket->user->first_name ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte de droite : Pièces jointes -->
                <div class="col-md-4">
                    <div class="card custom-bg shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="text-center mb-4">📎 Pièces jointes</h5>

                            <div class="row justify-content-center">
                                @forelse ($selectedTicket->files as $file)
                                    <div class="col-md-6 mb-3 text-center">
                                        @if (Str::endsWith($file->filename, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <a href="{{ $file->url }}" target="_blank">
                                                <img src="{{ $file->url }}" alt="Aperçu" class="img-fluid rounded shadow" style="max-height: 200px;">
                                            </a>
                                        @else
                                            <a href="{{ $file->url }}" target="_blank" class="d-block p-3 border rounded text-decoration-none bg-light">
                                                📄 Voir le fichier
                                            </a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="col-12 text-muted text-center">Aucun fichier attaché.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Formulaire de mise à jour assignation + statut -->
            <div class="card-footer bg-white">
                <form wire:submit.prevent="updateTicket" class="row g-3 justify-content-center text-start">
                    <!-- Champ sélection utilisateur -->
                    <div class="col-md-4">
                        <label for="assignedUserId" class="form-label">Assigner à</label>
                        <select wire:model="assignedUserId" class="form-select">
                            <option value="">-- Choisir un utilisateur --</option>
                            @foreach($allUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->matricule }} : {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Champ sélection statut -->
                    <div class="col-md-4">
                        <label for="newStatus" class="form-label">Statut</label>
                        <select wire:model="newStatus" class="form-select">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
                    </div>
                </form>
            </div>

            <!-- Section des commentaires -->
            <div class="card-footer bg-light">
                <h5 class="text-center mb-3">Commentaires</h5>

                <!-- Liste des commentaires -->
                @forelse($selectedTicket->comments as $comment)
                    <div class="mb-2 p-3 border rounded bg-white">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $comment->user->first_name ?? 'Utilisateur inconnu' }}</strong>
                            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mb-0 mt-2">{{ $comment->message }}</p>
                    </div>
                @empty
                    <p class="text-muted text-center">Aucun commentaire pour ce ticket.</p>
                @endforelse

                <!-- Formulaire ajout commentaire -->
                <form wire:submit.prevent="addComment" class="mt-4">
                    <div class="mb-3">
                        <textarea wire:model.defer="commentContent" class="form-control" rows="3" placeholder="Ajouter un commentaire..."></textarea>
                        @error('commentContent') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-sm btn-success">Ajouter le commentaire</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- END  -->

    @else
        <!-- Liste des tickets -->
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="Recherche...">
            </div>
            @php
                $statusLabels = [
                    'open' => 'Ticket Ouvert',
                    'closed' => 'Ticket Fermé',
                    'in_progress' => 'Ticket En cours',
                    // ajoute d’autres statuts si besoin
                ];
            @endphp

            <div class="col-md-3">
                <select wire:model="filterStatus" class="form-control">
                    <option value="">Tous les statuts</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">
                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select wire:model="filterCategory" class="form-control">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select wire:model="filterPriority" class="form-control">
                    <option value="">Toutes les priorités</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <!-- Conteneur de la table, rendue responsive pour un bon affichage sur mobile -->
        <div>
            <div class="table-responsive shadow rounded p-3 bg-white">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Créé par</th>
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
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img 
                                            src="{{ $ticket->user->profile_photo_path 
                                                ? asset('storage/' . $ticket->user->profile_photo_path)
                                                : asset('assets/img/team/avatar.jpg') }}" 
                                            class="avatar rounded-circle me-2" width="40" height="40">
                                        <div>
                                            <div>{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</div>
                                            <small class="text-muted">
                                                <span class="me-1">
                                                    <i class="fas fa-university me-1"></i> {{ $ticket->user->branch->name }}
                                                </span>
                                                <span class="mx-2 text-muted">|</span>
                                                <span>
                                                    <i class="fas fa-phone me-1"></i> {{ $ticket->user->phone }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>
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
                                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                    </span><br>
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i> {{ $ticket->assignedTo->matricule ?? '-' }}
                                    </small>
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
                    <div>
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>

            


        </div>

    @endif
</div>



