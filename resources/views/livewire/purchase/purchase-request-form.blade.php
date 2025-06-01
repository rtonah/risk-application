<div>
    <div class="row">
        <div class="col-12 col-xl-6">
            <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                <div x-data="rolePermissionManager()" class="grid grid-cols-2 gap-4">
                    <!-- Colonne gauche : Rôles -->
                    <div>
                        <h2 class="mb-4">Nouvelle demande d'achat</h2>

                        <!-- Titre -->
                       <div class="form-group mb-3">
                            <label for="title">Titre de la demande</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <!-- SVG card-text icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M14 4.5H2a.5.5 0 0 0 0 1h12a.5.5 0 0 0 0-1zM2 7h12a.5.5 0 0 1 0 1H2a.5.5 0 0 1 0-1zm0 3h8a.5.5 0 0 1 0 1H2a.5.5 0 0 1 0-1z"/>
                                    </svg>
                                </span>
                                <input type="text" wire:model="title" class="form-control" id="title" name="title" placeholder="Titre de la demande">
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="department">Département</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <!-- SVG building icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M6.5 15.5v-2h3v2h2v-3h-7v3h2z"/>
                                            <path d="M2 1v14h1v-1h10v1h1V1H2zm1 1h10v11H3V2z"/>
                                            <path d="M4 3h2v2H4V3zm0 3h2v2H4V6zm0 3h2v2H4V9zm3-6h2v2H7V3zm0 3h2v2H7V6zm0 3h2v2H7V9zm3-6h2v2h-2V3zm0 3h2v2h-2V6zm0 3h2v2h-2V9z"/>
                                        </svg>
                                    </span>
                                    <input type="text" wire:model="department" class="form-control" id="department" name="department" placeholder="Département">
                                </div>
                            </div>

                            <!-- Priorité -->
                            <div class="col-md-4 mb-3">
                                <label for="priority">Priorité</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <!-- SVG flag icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M14.778 3.21a.5.5 0 0 1 0 .58l-2.5 3.5a.5.5 0 0 1-.828 0L8.5 4.7l-2.95 4.13A.5.5 0 0 1 5 9H2.5a.5.5 0 0 1 0-1h2.21L8.5 3.87a.5.5 0 0 1 .828 0l2.95 4.13 2.5-3.5a.5.5 0 0 1 .707 0z"/>
                                            <path d="M1 2.5a.5.5 0 0 1 1 0v11a.5.5 0 0 1-1 0v-11z"/>
                                        </svg>

                                    </span>
                                    <select wire:model="priority" class="form-control" id="priority" name="priority">
                                        <option value="Basse">Basse</option>
                                        <option value="Normale">Normale</option>
                                        <option value="Haute">Haute</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Date de livraison -->
                            <div class="form-group mb-4">
                                <label for="expected_delivery_date">Date souhaitée</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <!-- SVG calendar icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M3.5 0a.5.5 0 0 0 0 1H4v1h8V1h.5a.5.5 0 0 0 0-1h-9zM4 2V1h8v1H4zm9 1H3a1 1 0 0 0-1 1v1h12V4a1 1 0 0 0-1-1zm1 2H2v8a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5z"/>
                                        </svg>
                                    </span>
                                    <input type="date" wire:model="expected_delivery_date" class="form-control" id="expected_delivery_date" name="expected_delivery_date">
                                </div>
                            </div>
                            <!-- Notes -->
                            <div class="form-group mb-4">
                                <label for="notes">Notes</label>
                                <textarea wire:model="notes" class="form-control" id="notes" name="notes" placeholder="Notes ou commentaires..."></textarea>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    <!-- End Info demande -->
    
    <div class="col-12 col-xl-6">
            <div class="row">
                <div class="col-12">
                    <div class="card card-body border-0 shadow">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                        @foreach ($items as $index => $item)
                            <div class="border p-3 rounded mb-3 bg-light">
                                <div class="form-group mb-2">
                                    <label>Nom du produit</label>
                                    <input type="text" wire:model="items.{{ $index }}.product_name" class="form-control" placeholder="Nom du produit">
                                </div>
                                <div class="row">
                                    <!-- Quantité -->
                                    <div class="col-md-2 mb-2">
                                        <label>Quantité</label>
                                        <input type="number" wire:model="items.{{ $index }}.quantity" min="1" class="form-control" placeholder="Quantité">
                                    </div>

                                    <!-- Prix unitaire -->
                                    <div class="col-md-3 mb-2">
                                        <label>Prix unitaire</label>
                                        <input type="number" wire:model="items.{{ $index }}.unit_price" step="0.01" class="form-control" placeholder="Prix unitaire">
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-7 mb-2">
                                        <label>Description</label>
                                        <input type="text" wire:model="items.{{ $index }}.description" class="form-control" placeholder="Description">
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button wire:click.prevent="removeItem({{ $index }})" class="btn btn-danger btn-sm">Supprimer</button>
                                </div>
                            </div>
                        @endforeach

                        <div class="row mb-4">
                            <!-- Bouton Ajouter un article -->
                            <div class="col-md-6 text-start">
                                <button wire:click.prevent="addItem" class="btn btn-secondary w-100">+ Ajouter un article</button>
                            </div>

                            <!-- Bouton Envoyer la demande -->
                            <div class="col-md-6 text-end">
                                @error('submit') <p class="text-danger">{{ $message }}</p> @enderror
                                <button wire:click.prevent="submit" class="btn btn-primary w-100">Envoyer la demande</button>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    <!-- Articles -->
    
</div>
