<form wire:submit.prevent="handleAction">
    <div class="row">
        <!-- Colonne gauche : Infos générales -->
        <div class="col-12 col-xl-6">
            <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                <h5 class="mb-4">Revue de la demande d'achat</h5>

                <div class="form-group mb-3">
                    <label class="form-label">Titre</label>
                    <input type="text" class="form-control" value="{{ $purchaseRequest->title }}" disabled>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Notes du demandeur</label>
                    <textarea class="form-control" wire:model.defer="notes" rows="3" placeholder="Notes ou commentaires..." @disabled(!$isEditable)></textarea>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Priorité</label>
                    <select class="form-select" wire:model="priority" @disabled(!$isEditable)>
                        <option value="Basse">Basse</option>
                        <option value="Normale">Normale</option>
                        <option value="Haute">Haute</option>
                        <option value="Critique">Critique</option>
                    </select>
                </div>
            </div>
            <!-- Boutons d’action -->
            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="submit" wire:click="$set('action', 'save')" class="btn btn-primary" {{ $isEditable ? '' : 'disabled' }}>Enregistrer</button>
                <button type="submit" wire:click="$set('action', 'approve')" class="btn btn-success" @disabled(!$isActionable)>Approuver</button>
                <button type="submit" wire:click="$set('action', 'pending')" class="btn btn-warning" @disabled(!$isActionable)>Mettre en attente</button>
                <button type="submit" wire:click="$set('action', 'reject')" class="btn btn-danger" @disabled(!$isActionable)>Refuser</button>
                <!-- Nouveau bouton Envoyer à Odoo -->
                <button type="button" wire:click="sendToOdoo" class="btn btn-info" 
                    @disabled($purchaseRequest->status !== 'approved')>
                    Envoyer à Odoo
                </button>

            </div>
        </div>

        <!-- Colonne droite : Articles -->
        <div class="col-12 col-xl-6">
            <div class="card card-body border-0 shadow mb-4">
                <h5 class="mb-4">Articles de la demande</h5>
                @foreach($items as $index => $item)
                    <div class="border p-3 rounded mb-3 bg-light" wire:key="item-{{ $index }}">
                        <!-- Nom du produit -->
                        <div class="col-md-12 mb-2">
                            <label>Nom du produit</label>
                            <input type="text" wire:model.defer="items.{{ $index }}.product_name" class="form-control" @disabled(!$isEditable)>
                        </div>
                        <div class="row">
                            <!-- Quantité -->
                            <div class="col-md-2 mb-2">
                                <label>Quantité</label>
                                <input type="number" wire:model.defer="items.{{ $index }}.quantity" class="form-control" min="1" @disabled(!$isEditable)>
                            </div>

                            <!-- Prix unitaire -->
                            <div class="col-md-4 mb-2">
                                <label>Prix unitaire</label>
                                <input type="number" wire:model.defer="items.{{ $index }}.unit_price" class="form-control" step="0.01" @disabled(!$isEditable)>
                            </div>

                            <!-- Total -->
                            <div class="col-md-6 mb-2">
                                <label>Total</label>
                                <input type="text" class="form-control" value="{{ number_format($item['quantity'] * $item['unit_price'], 2) }} Ariary" disabled>
                            </div>
                        </div>

                        <!-- Bouton Supprimer -->
                        <div class="text-end">
                            <button wire:click.prevent="removeItem({{ $index }})" type="button" class="btn btn-danger btn-sm" @disabled(!$isActionable)>
                                Supprimer
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    

</form>
