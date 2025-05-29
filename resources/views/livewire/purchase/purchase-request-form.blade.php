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
                                    <i class="bi bi-card-text"></i>
                                </span>
                                <input type="text" wire:model="title" class="form-control" id="title" name="title" placeholder="Titre de la demande">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Date de livraison -->
                            <div class="col-md-4 mb-3">
                                <label for="expected_delivery_date">Date de livraison souhaitée</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-calendar-event"></i>
                                    </span>
                                    <input type="date" wire:model="expected_delivery_date" class="form-control" id="expected_delivery_date" name="expected_delivery_date">
                                </div>
                            </div>

                            <!-- Département -->
                            <div class="col-md-4 mb-3">
                                <label for="department">Département</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <input type="text" wire:model="department" class="form-control" id="department" name="department" placeholder="Département">
                                </div>
                            </div>

                            <!-- Priorité -->
                            <div class="col-md-4 mb-3">
                                <label for="priority">Priorité</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-flag"></i>
                                    </span>
                                    <select wire:model="priority" class="form-control" id="priority" name="priority">
                                        <option value="Basse">Basse</option>
                                        <option value="Normale">Normale</option>
                                        <option value="Haute">Haute</option>
                                    </select>
                                </div>
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
