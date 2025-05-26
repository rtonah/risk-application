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
                    <li class="breadcrumb-item active" aria-current="page">Création ticket</li>
                </ol>
            </nav>
            <h2 class="h4">Formulaire de ticket : anonyme ou non</h2>
            <p class="mb-0">Page de soumission de ticket avec choix entre anonymat et visibilité.</p>
        </div>
    </div>
    

    <div class="table-settings mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="row justify-content-center form-bg-image"
                data-background-lg="../../assets/img/illustrations/signin.svg">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                        <div class="text-center text-md-center mb-4 mt-md-0">
                            <h1 class="mb-0 h3">Créer un ticket</h1>
                        </div>
                        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Titre -->
                            <div class="form-group mb-4">
                                <label for="title">Titre du ticket</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v0.5L10 10 2 5.5V5z"></path>
                                            <path d="M18 8.5l-8 5-8-5V15a2 2 0 002 2h12a2 2 0 002-2V8.5z"></path>
                                        </svg>
                                    </span>
                                    <input type="text" name="title" id="title" class="form-control" placeholder="Sujet du ticket"
                                        required>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-4">
                                <label for="description">Description</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zm0 4h12a1 1 0 100-2H4a1 1 0 000 2zm0 4h12a1 1 0 100-2H4a1 1 0 000 2zm0 4h8a1 1 0 100-2H4a1 1 0 000 2z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                    <textarea name="description" id="description" rows="4" class="form-control"
                                        placeholder="Décris ton problème..." required></textarea>
                                </div>
                            </div>

                            <!-- Anonymat -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" value="1" name="anonymous" id="anonymous">
                                <label class="form-check-label" for="anonymous">
                                    Soumettre anonymement
                                </label>
                            </div>

                            <!-- Pièces jointes -->
                            <div class="form-group mb-4">
                                <label for="attachments">Pièces jointes</label>
                                <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                            </div>

                            <!-- Soumettre -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Soumettre le ticket
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
