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
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
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
        <div class="table-responsive shadow rounded p-3 bg-white">
            <!-- Tableau stylisé avec Bootstrap -->
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Créé le</th>
                        <th scope="col">Priorité</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Boucle sur chaque ticket -->
                    @forelse($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>
                                <span class="fw-bold">{{  $ticket->title }}</span>
                                <div class="small text-gray">Catégorie : {{  $ticket->category }}</div>
                            </td>
                            <td>
                                <span class="fw-bold">{{ $ticket->created_at->format('d M Y H:i:s') }}</span>
                                <div class="small text-gray">Par : {{ $ticket->user->first_name }} / {{ $ticket->user->branch->name ?? 'Non affecté' }}</div>
                            </td>
                            <td>
                                <!-- Badge de couleur selon la priorité -->
                                <span class="badge 
                                    {{ $ticket->priority === 'très urgent' ? 'bg-danger' : ($ticket->priority === 'normal' ? 'bg-success' : 'bg-warning text-dark') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td>
                                <!-- Badge de couleur selon le statut -->
                                <span class="badge 
                                    {{ $ticket->status === 'open' ? 'bg-success' : 'bg-tertiary' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td>
    <!-- Bouton pour voir le ticket -->
    <button class="btn btn-sm btn-outline-info me-1" wire:click="show({{ $ticket->id }})" title="Voir le ticket">
        <i class="bi bi-eye"></i> {{-- Icône œil Bootstrap Icons --}}
    </button>

    <!-- Bouton pour supprimer le ticket avec confirmation -->
    <button class="btn btn-sm btn-outline-danger" 
            wire:click="delete({{ $ticket->id }})" 
            onclick="return confirm('Confirmer la suppression ?')" 
            title="Supprimer le ticket">
        <i class="bi bi-trash"></i> {{-- Icône poubelle Bootstrap Icons --}}
    </button>
</td>

                        </tr>
                    @empty
                        <!-- Message si aucun ticket -->
                        <tr>
                            <td colspan="7" class="text-center text-muted">Aucun ticket trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div>
            {{ $tickets->links() }}
        </div>
    @endif
</div>
