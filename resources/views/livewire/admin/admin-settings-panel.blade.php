<div class="col-lg-8 col-sm-12 mx-auto">
    <div class="card border-0 shadow p-4">
        <h2 class="h5 mb-4">Paramètres Généraux</h2>
        
       <div class="row">
            <!-- Mode Environnement -->
            <div class="col-md-4 mb-3">
                <label for="env_mode">Mode Environnement</label>
                <select wire:model="env_mode" id="env_mode" class="form-select">
                    <option value="demo">Démo</option>
                    <option value="production">Production</option>
                </select>
            </div>

            <!-- Domaine CBS associé au mode -->
            <div class="col-md-8 mb-4">
                <label for="domain_cbs">Nom de Domaine CBS ({{ ucfirst($env_mode) }})</label>
                <input type="text" wire:model="domain_cbs" id="domain_cbs" class="form-control" placeholder="https://..." disabled />
                <small class="text-muted">Ce domaine sera utilisé pour le mode <strong>{{ $env_mode }}</strong>.</small>
            </div>
        </div>



        <div class="row">
            <!-- Email général -->
            <div class="col-md-4 mb-3">
                <label for="email_recepteur">Email notifications admin</label>
                <input type="email" wire:model="email_recepteur" id="email_recepteur" class="form-control" />
            </div>

            <!-- Email Achat -->
            <div class="col-md-4 mb-3">
                <label for="email_achat">Email notifications Achat</label>
                <input type="email" wire:model="email_achat" id="email_achat" class="form-control" />
            </div>

            <!-- Email Audit -->
            <div class="col-md-4 mb-4">
                <label for="email_edit">Email notifications Audit</label>
                <input type="email" wire:model="email_edit" id="email_edit" class="form-control" />
            </div>
        </div>


        <!-- Types de paiement -->
        <div class="mb-3">
            <label>Types de Paiement (clé => identifiant)</label>
            @foreach ($types_paiement as $index => $item)
                <div class="input-group mb-2">
                    <input type="text" wire:model="types_paiement.{{ $index }}.key" class="form-control w-50" placeholder="Clé (ex: orange_paiement)" />
                    <input type="text" wire:model="types_paiement.{{ $index }}.id" class="form-control w-25" placeholder="ID Type de payement" />
                    <button wire:click.prevent="removeType({{ $index }})" class="btn btn-outline-danger w-25">Supprimer</button>
                </div>
            @endforeach
            <button wire:click.prevent="addType" class="btn btn-link p-0">+ Ajouter un type</button>
        </div>


        <!-- Bouton Enregistrer -->
        <div class="mt-4">
            <button wire:click="save" class="btn btn-primary">Enregistrer</button>
        </div>
    </div>

</div>
<script>
    window.addEventListener('notify', event => {
        const notyf = new Notyf({
            duration: 3000,  // Durée de la notification (ms)
            position: {
                x: 'right',
                y: 'top',
            }
        });

        if (event.detail.type === 'success') {
            notyf.success(event.detail.message);
        } else if (event.detail.type === 'error') {
            notyf.error(event.detail.message);
        }
    });
</script>