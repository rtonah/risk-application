<div class="col-lg-10 col-sm-12 mx-auto">
    <div class="card card-body shadow-sm">
        <h4 class="mb-4">Gestion des Identifiants CBS</h4>
        <form wire:submit.prevent="save">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nom (demo ou production)</label>
                    <input type="text" class="form-control" wire:model.defer="form.name" disabled>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Domaine</label>
                    <input type="text" class="form-control" wire:model.defer="form.domaine">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Token</label>
                    <input type="text" class="form-control" wire:model.defer="form.token">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">Login</label>
                    <input type="text" class="form-control" wire:model.defer="form.login">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" wire:model.defer="form.password">
                </div>

                <div class="col-12">
                    <button class="btn btn-primary">Enregistrer</button>
                </div>
            </div>
        </form>
        
        <hr class="my-4" />

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Login</th>
                    <th>Token</th>
                    <th>Actions</th>
                </tr>
            </thead>
        <tbody>
                @foreach($credentials as $credential)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $credential->name }}</span>
                            <div class="small text-muted">{{ $credential->domaine }}</div>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $credential->login }}</span>
                            <div class="small text-muted">••••</div>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $credential->token }}</span>
                            <div class="small text-muted">
                            Dernière modification effectuée le : {{ $credential->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" wire:click="edit({{ $credential->id }})" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button class="btn btn-sm btn-danger" wire:click="$emit('triggerDelete', {{ $credential->id }})" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
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

<script>
    window.addEventListener('DOMContentLoaded', () => {
        window.livewire.on('triggerDelete', id => {
            if (confirm('Es-tu sûr de vouloir supprimer cet enregistrement ?')) {
                Livewire.emit('delete', id);
            }
        });
    });
</script>


