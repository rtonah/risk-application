<x-layouts.app>

    <title>Fahombiazana</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Conformité</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rôle et Permission</li>
                </ol>
            </nav>
            <h2 class="h4">Liste des rôles et permissions</h2>
            <p class="mb-0">Outil de gestion des rôles utilisateurs et de leurs niveaux d’accès aux fonctionnalités..</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="#" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal-form-roles">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Role
            </a>
        </div>
    </div>


   
    {{-- @include('admin.roles.partials.roles_table') --}}
    
    {{-- Start Role --}}
    
    <livewire:admin.role-manager />

    {{-- End Role --}}
     <div class="col-lg-4">
        <!-- Modal Content -->
        <div class="modal fade" id="modal-form-roles" tabindex="-1" role="dialog" aria-labelledby="modal-form-roles" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <div class="card p-3 p-lg-4">

                            <div class="container mt-4">
                                <h2>Créer un nouveau rôle</h2>

                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <form method="POST" action="{{ route('roles.store') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom du rôle</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>

                                   <div class="mb-3">
                                        <label class="form-label">Permissions</label>
                                        <div>
                                            @foreach($permissions as $permission)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>


                                    <button type="submit" class="btn btn-primary">Créer</button>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Content -->
    </div>

</x-layouts.app>

