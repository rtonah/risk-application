 <div class="row justify-content-center">
    <div class="col-6 mb-4">
        <div class="card border-0 shadow components-section">
            <div class="card-body">     

                <div>
                    <div class="input-group me-2 me-lg-3 mb-4">
                        <span class="input-group-text">
                            <svg class="icon icon-xs" x-description="Heroicon name: solid/search"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        {{-- <input type="text" class="form-control" placeholder="Search users"> --}}
                        <input type="text" wire:model.debounce.300ms="query" placeholder="Rechercher un client, fournisseur ou prestataire..." class="form-control">
                        
                    </div>

                    <div class="space-y-4">
                        @if($results)
                            <ul class="border rounded divide-y shadow">
                                @forelse($results as $noire)
                                    <li class="p-3">
                                        <div class="font-bold">{{ $noire->full_name }}</div>
                                        <div class="text-sm text-gray-500">
                                            Type : {{ ucfirst($noire->blacklist_type) }} |
                                            Statut : 
                                                @php
                                                    $statusClass = match(strtolower($noire->status)) {
                                                        'unblocked' => 'bg-success',
                                                        'blacklisted' => 'bg-danger',
                                                        default => 'bg-light text-dark'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }}">
                                                    {{ ucfirst($noire->status) }}
                                                </span>
                                            |
                                            ID : {{ $noire->national_id }}
                                        </div>

                                        @if($noire->document_path)
                                            <div class="mt-2 d-flex align-items-center">
                                                <a href="{{ asset('storage/' . $noire->document_path) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center">
                                                    <svg class="icon icon-xs me-1" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 7H7v6h6V7z" />
                                                        <path fill-rule="evenodd"
                                                            d="M5 4a3 3 0 013-3h4a3 3 0 013 3v12a3 3 0 01-3 3H8a3 3 0 01-3-3V4zm3-1a1 1 0 00-1 1v12a1 1 0 001 1h4a1 1 0 001-1V4a1 1 0 00-1-1H8z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Voir document
                                                </a>
                                            </div>
                                        @endif
                                    </li>
                                @empty
                                    <li class="p-3 text-gray-500">Aucun résultat</li>
                                @endforelse
                            </ul>
                        @endif
                    </div>

                    
                </div>
                
            </div>
        </div>
    </div>
 </div>