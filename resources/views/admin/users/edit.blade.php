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
                <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
            </ol>
        </nav>
        <h2 class="h4">Liste des utilisateurs</h2>
        <p class="mb-0">Affiche tous les utilisateurs ayant accès à l'application, avec leurs rôles et informations associées..</p>
    </div>
</div>


<div class="row">
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">General information</h2>
                <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="first_name" class="form-label">Prénom</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}">
                            </div>    
                        </div>
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="last_name" class="form-label">Nom</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}">
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                            </div>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender">Gender</label>
                            <select wire:model="user.gender" class="form-select mb-0" id="gender"
                                aria-label="Gender select example" name="gender">
                                <option selected>{{ $user->gender }}</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('user.gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h2 class="h5 my-4">Location</h2>
                    <div class="row">
                        <div class="col-sm-9 mb-3">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input wire:model="user.address" class="form-control" id="address" type="text"
                                    placeholder="Enter your home address" name="address" value="{{ $user->address }}">
                            </div>
                            @error('user.address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-3 mb-3">
                            <div class="form-group">
                                <label for="number">Phone</label>
                                <input wire:model="user.number" class="form-control" id="number" type="number"
                                    placeholder="No." name="number" value="{{ $user->number }}">
                            </div>
                            @error('user.number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 mb-3">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input wire:model="user.city" class="form-control" id="city" type="text"
                                    placeholder="City" name="city" value="{{ $user->city }}">
                            </div>
                            @error('user.city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="zip">ZIP</label>
                                <input wire:model="user.ZIP" class="form-control" id="zip" type="tel" placeholder="ZIP" name="ZIP" value="{{ $user->ZIP }}">
                            </div>
                        </div>
                        @error('user.ZIP') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-gray-800 mt-2 animate-up-2">Save All</button>
                    </div>
                    
                </form>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="row">
                <div class="col-12 mb-4">

                    <div class="card shadow border-0 text-center p-0 mb-4">
                        <div wire:ignore.self class="profile-cover rounded-top"
                            data-background="{{ asset('assets/img/background.png') }}">
                        </div>

                        <div class="card-body pb-5">
                            {{-- Affichage de la photo de profil si elle existe --}}
                            @if ($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                    class="avatar-xl rounded-circle mx-auto mt-n7 mb-2" alt="Portrait utilisateur">
                            @else
                                <img src="{{ asset('default-avatar.png') }}"
                                    class="avatar-xl rounded-circle mx-auto mt-n7 mb-2" alt="Avatar par défaut">
                            @endif

                            {{-- Message d'erreur pour le champ profile_photo --}}
                            @error('profile_photo')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror

                            {{-- Formulaire de mise à jour de la photo --}}
                            <form action="{{ route('admin.profil.update', $user) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="profile_photo" class="form-label">Modifier la photo de profil</label>
                                    <input type="file" name="profile_photo" id="profile_photo" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-sm btn-secondary">Mettre à jour</button>
                            </form>
                        </div>
                    </div>


                    <div class="card shadow border-0 text-center p-0">
                        <div class="card-body pb-5">
                            <form action="{{ route('admin.pass.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <h4>Changer le mot de passe</h4>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" name="password" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-sm btn-secondary">Mettre à jour le mot de passe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

</x-layouts.app>

