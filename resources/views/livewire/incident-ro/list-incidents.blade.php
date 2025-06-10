<div>
    <div class="card shadow border-0">
        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Suivi des Incidents Opérationnels</h5>
            <div>
                {{-- Bouton pour créer un nouvel incident (peut-être un modal ou une redirection) --}}
                <a href="{{ route('risque.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i> Nouvel Incident
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-4 row">
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Rechercher par titre, description, localisation, etc.">
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterStatus" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="ouvert">Ouvert</option>
                        <option value="en cours">En cours</option>
                        <option value="résolu">Résolu</option>
                        <option value="clôturé">Clôturé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterPriority" class="form-select">
                        <option value="">Toutes les priorités</option>
                        <option value="faible">Faible</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="élevée">Élevée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterOrigin" class="form-select">
                        <option value="">Toutes les origines</option>
                        <option value="interne">Interne</option>
                        <option value="externe">Externe</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select wire:model.live="filterBranch" class="form-select">
                        <option value="">Toutes les branches</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-4 row">
                <div class="col-md-3">
                    <input type="date" wire:model.live="filterStartDate" class="form-control" placeholder="Date de début">
                </div>
                <div class="col-md-3">
                    <input type="date" wire:model.live="filterEndDate" class="form-control" placeholder="Date de fin">
                </div>
                <div class="col-md-3">
                    <input type="text" wire:model.live="filterIncidentType" class="form-control" placeholder="Type d'incident">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('id')" style="cursor: pointer;">ID
                                @if ($sortField === 'id')
                                    <i class="fas fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('title')" style="cursor: pointer;">Titre
                                @if ($sortField === 'title')
                                    <i class="fas fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('status')" style="cursor: pointer;">Statut
                                @if ($sortField === 'status')
                                    <i class="fas fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('priority')" style="cursor: pointer;">Priorité
                                @if ($sortField === 'priority')
                                    <i class="fas fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('incident_type')" style="cursor: pointer;">Type
                                @if ($sortField === 'incident_type')
                                    <i class="fas fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('origin')" style="cursor: pointer;">Origine
                                @if ($sortField === 'origin')
                                    <i class="fas fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th wire:click="sortBy('reported_at')" style="cursor: pointer;">Date de Rapport
                                @if ($sortField === 'reported_at')
                                    <i class="fas fa-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>
                            <th>Rapporté par</th>
                            <th>Branche</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($incidents as $incident)
                            <tr>
                                <td>{{ $incident->id }}</td>
                                <td>{{ Str::limit($incident->title, 50) }}</td>
                                <td>
                                    <span class="badge {{
                                        $incident->status == 'ouvert' ? 'bg-danger' :
                                        ($incident->status == 'en cours' ? 'bg-warning text-dark' :
                                        ($incident->status == 'résolu' ? 'bg-info' : 'bg-success'))
                                    }}">{{ ucfirst($incident->status) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{
                                        $incident->priority == 'faible' ? 'bg-success' :
                                        ($incident->priority == 'moyenne' ? 'bg-warning text-dark' : 'bg-danger')
                                    }}">{{ ucfirst($incident->priority) }}</span>
                                </td>
                                <td>{{ $incident->incident_type ?? 'N/A' }}</td>
                                <td>{{ ucfirst($incident->origin) ?? 'N/A' }}</td>
                                <td>{{ $incident->reported_at ? $incident->reported_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                <td>{{ $incident->user->name ?? 'Utilisateur Inconnu' }}</td>
                                <td>{{ $incident->branch->name ?? 'N/A' }}</td>
                                <td>
                                     {{-- Le bouton "Voir les détails" --}}
                                    <button class="btn btn-sm btn-info me-2"
                                            title="Voir les détails"
                                            wire:click="openShowModalForIncident({{ $incident->id }})"> {{-- NOUVELLE MODIFICATION --}}
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- Bouton pour modifier (si vous avez un composant EditIncident) --}}
                                    <a href="#" class="btn btn-sm btn-warning me-2" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Bouton Résoudre/Clôturer --}}
                                    @if ($incident->status != 'résolu' && $incident->status != 'clôturé')
                                        <button class="btn btn-sm btn-success me-2"
                                                title="Résoudre/Clôturer l'incident"
                                                wire:click="openResolveModalForIncident({{ $incident->id }})"> {{-- NOUVELLE CORRECTION --}}
                                            <i class="fas fa-check-circle"></i> Résoudre
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-secondary me-2" disabled title="Déjà résolu/clôturé">
                                            <i class="fas fa-check-circle"></i> Résolu
                                        </button>
                                    @endif

                                    {{-- Bouton pour supprimer --}}
                                    <button class="btn btn-sm btn-danger" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">Aucun incident trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $incidents->links() }} {{-- Afficher les liens de pagination --}}
            </div>

            {{-- Inclure le composant de résolution ici --}}
            <livewire:incident-ro.resolve-incident />

            {{-- Inclure le nouveau composant d'affichage des détails --}}
            <livewire:incident-ro.show-incident />

        </div>
    </div>
</div>