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
                    <li class="breadcrumb-item active" aria-current="page">Table ronde virtuelle</li>
                </ol>
            </nav>
            <h2 class="h4">Espace de discussion</h2>
            <p class="mb-0">Fil de discussion (pour une discussion spécifique dans un forum)</p>
        </div>
    </div>
    
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        
        <div>
            <button class="btn btn-gray-800 d-inline-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                    <path fill-rule="evenodd"
                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                        clip-rule="evenodd"></path>
                </svg>
                Reports
                <svg class="icon icon-xs ms-1" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
            <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"></path>
                        <path fill-rule="evenodd"
                            d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Conformité
                </a>
               
                <div role="separator" class="dropdown-divider my-1"></div>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <svg class="dropdown-icon text-gray-800 me-2" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd"></path>
                    </svg>
                    All Reports
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                <h2 class="h5 mb-4">💬 Discussion</h2>
                <h2 class="h4">{{ $ticket->subject }}</h2>
                <div class="text-sm text-gray-600 mt-1">
                    Créé par :
                    <strong>{{ $ticket->user ? $ticket->user->first_name : 'Anonyme' }}</strong> 
                    •
                    {{ $ticket->created_at->diffForHumans() }}
                </div>
                <p class="mb-0">{{ $ticket->description }}</p>
                <br>
                <div class="card-body space-y-6">
                    {{-- Liste des messages --}}
                    @foreach ($ticket->messages as $msg)
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex align-items-center justify-content-between px-0 border-bottom">
                                <div>
                                    <h3 class="h6 mb-1">{{ $msg->user->first_name ?? 'Anonyme' }}</h3>
                                    <p class="small pe-4">{{ $msg->message }}</p>
                                </div>
                                <div>
                                    <div class="form-check form-switch">
                                        <label class="text-xs text-gray-500" for="user-notification-3">{{ $msg->created_at->diffForHumans() }}</label>
                                    </div>
                                </div>
                            </li>
                            
                        </ul>
                    @endforeach

                    {{-- Formulaire d’ajout de message --}}
                    <form action="{{ route('tickets.messages.store', $ticket) }}" method="POST">
                        @csrf
                        <textarea name="message" rows="3" class="form-control mb-2" placeholder="Votre message"></textarea>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" name="anonymous" id="anonymous">
                            <label for="anonymous" class="form-check-label">Soumettre anonymement</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body border-0 shadow">
                        <h2 class="h5 mb-4">Documents justificatifs</h2>
                        <div class="d-flex align-items-center">
                            @forelse ($ticket->attachments as $attachment)
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <img class="rounded avatar-xl" src="../assets/img/pdf.png" alt="Fichier">
                                    </div>
                                    <div>
                                        <div class="fw-normal text-dark mb-1">Uploaded by :{{ $attachment->uploader?->first_name ?? 'Anonyme' }} </div>
                                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                                            Télécharger
                                        </a>
                                        <p class="text-muted small mt-1">{{ basename($attachment->path) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">Aucun document attaché à ce ticket.</p>
                            @endforelse
                        </div>
                    </div>    

                           

                    </div>
                </div>
            </div>
        </div>

    </div>



</x-layouts.app>
