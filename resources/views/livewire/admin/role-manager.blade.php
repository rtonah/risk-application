<div>
    <div class="row">
        <div class="col-12 col-xl-3">
            <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                <div x-data="rolePermissionManager()" class="grid grid-cols-2 gap-4">
                    <!-- Colonne gauche : Rôles -->
                    <div>
                        <h2 class="h5 mb-4">Liste des rôles.</h2>

                        @foreach($roles as $role)
                            <li>
                                <button wire:click="selectRole({{ $role->id }})"
                                        class="btn w-full text-left {{ $selectedRoleId === $role->id ? 'bg-secondary text-white' : '' }}">
                                    {{ $role->name }}
                                </button>
                            </li>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Liste des permissions -->

        <div class="col-12 col-xl-9">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body border-0 shadow">
                        <h2 class="h5 mb-4">Liste des permissions associées au rôle sélectionné.</h2>
                        
                        @if($selectedRoleId)
                            @if(count($allPermissions) > 0)
                                <form wire:submit.prevent="updatePermissions">

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nom</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allPermissions as $permission)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}">
                                                    </td>
                                                    <td>{{ $permission->name }}</td>
                                                    <td>{{ $permission->description ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
                                </form>
                            @else
                                <p>Aucune permission trouvée pour ce rôle.</p>
                            @endif
                        @else
                            <p>Sélectionnez un rôle pour modifier ses permissions</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
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
