<div>
    <div class="row">
        <div class="col-12 col-xl-6">
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

        <div class="col-12 col-xl-6">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body border-0 shadow">
                        <h2 class="h5 mb-4">Liste des permissions associées au rôle sélectionné.</h2>
                        
                        @if($selectedRoleId)
                            @if(count($permissions) > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $permission)
                                            <tr>
                                                <td>{{ $permission->id }}</td>
                                                <td>{{ $permission->name }}</td>
                                                <td>{{ $permission->description ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>Aucune permission trouvée pour ce rôle.</p>
                            @endif
                        @else
                            <p>Sélectionnez un rôle pour voir ses permissions.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
