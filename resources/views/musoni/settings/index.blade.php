<x-layouts.app>

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
                    <li class="breadcrumb-item"><a href="#">Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Musoni</li>
                </ol>
            </nav>
            <h2 class="h4">Musoni Settings</h2>
            <p class="mb-0">La configuration permettant d’accéder aux paramètres se trouve ici.</p>
        </div>
        
    </div>
    
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        
        <div id="blacklist-table">

            <div class="container mx-auto p-4">

                @if(session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white p-4 rounded shadow mb-6">
                    <h2 class="text-xl font-semibold mb-4">Ajouter un identifiant CBS</h2>
                    <form method="POST" action="{{ route('setting.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="block font-medium">Nom de l’environnement</label>
                            <input type="text" name="name" id="name" class="form-input w-full" required>
                        </div>
                        <div class="mb-3">
                            <label for="login" class="block font-medium">Login CBS</label>
                            <input type="text" name="login" id="login" class="form-input w-full" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="block font-medium">Mot de passe CBS</label>
                            <input type="password" name="password" id="password" class="form-input w-full" required>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Enregistrer</button>
                    </form>
                </div>

                <div class="bg-white p-4 rounded shadow">
                    <h2 class="text-xl font-semibold mb-4">Identifiants enregistrés</h2>
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="text-left p-2">Nom</th>
                                <th class="text-left p-2">Login</th>
                                <th class="text-left p-2">Date d’ajout</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($credentials as $cred)
                                <tr class="border-t">
                                    <td class="p-2">{{ $cred->name }}</td>
                                    <td class="p-2">{{ $cred->login }}</td> <!-- Déchiffré automatiquement -->
                                    <td class="p-2">{{ $cred->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-2 text-center text-gray-500">Aucun identifiant enregistré</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>

</x-layouts.app>
