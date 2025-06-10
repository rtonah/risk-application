<div>
    <div class="table-settings mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-9 col-lg-10 d-md-flex">
                <div class="input-group me-2 me-lg-3 fmxw-400">
                    <span class="input-group-text">
                        <svg class="icon icon-xs" x-description="Heroicon name: solid/search"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input  wire:model.debounce.500ms="search" type="text" class="form-control" placeholder="  Nom ou Email">
                </div>
                {{-- <input wire:model.debounce.500ms="search" type="text" class="form-control w-auto me-2" placeholder="🔍 Nom ou Email"> --}}

                <select wire:model="selectedStatus" class="form-select fmxw-200 d-none d-md-inline me-2" aria-label="Message select example 2">
                    <option value="">-- Tous les statuts --</option>
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>

                
                <select wire:model="selectedRole" class="form-select fmxw-200 d-none d-md-inline me-2" aria-label="Message select example 2">
                    <option value="">-- Tous les rôles --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>

                <select wire:model="selectedBranch" class="form-select fmxw-200 d-none d-md-inline" aria-label="Message select example 2">
                    <option value="">Toutes les agences</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                
            </div>
            <div class="col-3 col-lg-2 d-flex justify-content-end">
                <div class="btn-group">
                    {{-- <button wire:click="$set('showCreateModal', true)" class="btn btn-primary mb-3">Créer un utilisateur</button> --}}
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        Créer un utilisateur
                    </button>

                </div>
            </div>
        </div>
    </div>

    {{-- Table utilisateur --}}
    <div class="card card-body shadow border-0 table-wrapper table-responsive">

        <table class="table user-table table-hover align-items-center">
            <thead>
                <tr>

                    <th>Name</th>
                    <th>Role</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Change Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td>
                            <a href="#" class="d-flex align-items-center">
                                <img 
                                    src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/team/avatar.jpg') }}" 
                                    class="avatar rounded-circle me-3" 
                                    alt="Avatar">
                                <div class="d-block">
                                    <span class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</span>
                                    <div class="small text-gray">{{ $user->email }}</div>
                                </div>
                            </a>
                        </td>

                        <td>
                            <span class="fw-bold">{{ $user->roles->pluck('name')->first() ?? 'None' }}</span>
                            <div class="small text-gray">Agence : {{ $user->branch->name ?? 'Non affecté' }}</div>
                        </td>
                        <td>
                             <span class="fw-bold"> {{ $user->phone }} </span>
                            <div class="small text-gray">Matricule : {{ $user->matricule ?? 'Non affecté' }}</div>
                        </td>
                       <td class="text-center">
                            @if ($user->status === 1)
                                <span class="d-inline-block rounded-circle shadow" 
                                    style="width: 17px; height: 17px; background-color: #28a745;" 
                                    title="Actif">
                                </span>
                            @else
                                <span class="d-inline-block rounded-circle shadow" 
                                    style="width: 17px; height: 17px; background-color: #dc3545;" 
                                    title="Inactif">
                                </span>
                            @endif
                        </td>


                        <td>
                        <select wire:change="changeUserRole({{ $user->id }}, $event.target.value)" class="form-select form-select-sm" style="max-width: 200px;">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>

                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <!-- Bouton Modifier (icône uniquement) -->
                                <button wire:click="openPasswordResetModal({{ $user->id }})" class="btn btn-sm btn-warning" title="Réinitialiser le mot de passe">
                                    <i class="fas fa-key"></i>
                                </button>


                                <!-- Bouton Activer/Désactiver (icône uniquement) -->
                                <button wire:click="toggleStatus({{ $user->id }})"
                                        class="btn btn-sm btn-primary"
                                        onclick="return confirm('Êtes-vous sûr ?')"
                                        title="{{ $user->status ? 'Désactiver' : 'Activer' }}">
                                    <i class="fas {{ $user->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
            {{-- Infos --}}
            <div class="fw-normal small mt-4 mt-lg-0">
                Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} résultats
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $users->links('vendor.pagination.bootstrap-5-sm') }}
            </div>
        </div>
        {{-- ✅ Script pour success alert --}}

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
        </script>
    
    </div>

    <!-- Modal nouveau utilisateur -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Créer un utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body">
                    <input type="text" wire:model.defer="newUser.first_name" class="form-control mb-2" placeholder="Prénom">
                    <input type="text" wire:model.defer="newUser.last_name" class="form-control mb-2" placeholder="Nom">
                    <input type="email" wire:model.defer="newUser.email" class="form-control mb-2" placeholder="Email">

                    <select wire:model.defer="newUser.branch_id" class="form-control mb-2">
                        <option value="">Sélectionner une agence</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.defer="newUser.role" class="form-control mb-2">
                        <option value="">Sélectionner un rôle</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>

                    <label for="photo" class="form-label">Photo de profil</label>
                    <input type="file" wire:model="photo" class="form-control mb-2">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" width="100" class="mt-2">
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" wire:click="createUser" class="btn btn-success">Créer</button>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Modal de réinitialisation de mot de passe -->
    <div wire:ignore.self class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Réinitialiser le mot de passe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                    <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                    <input type="password" id="newPassword" class="form-control" wire:model.defer="newPassword">
                    @error('newPassword') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" wire:click="resetPassword">Réinitialiser</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.addEventListener('showPasswordModal', () => {
            let modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            modal.show();
        });

        window.addEventListener('hidePasswordModal', () => {
            let modalEl = document.getElementById('changePasswordModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        });
    </script>
    @endpush


    <!-- Modal -->

</div>
 