<div x-data="{ processing: false, progress: 0 }"
     x-init="
        Livewire.on('start-processing', () => {
            processing = true;
            progress = 0;

            let interval = setInterval(() => {
                if (progress >= 95) return;
                progress += 5;
            }, 300);

            Livewire.on('stop-processing', () => {
                clearInterval(interval);
                progress = 100;
                setTimeout(() => processing = false, 1000); // attendre un peu avant de masquer
            });
        });
     ">
    {{-- ✅ Message de succès --}}
    @if ($successMessage)
        <div class="alert alert-success" id="success-alert">
            {{ $successMessage }}
        </div> 
    @endif

    {{-- ✅ Bouton Modal Volt --}}
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#importMvola">
        <i class="fas fa-upload me-1"></i> Importer un fichier MVola
    </button>

    {{-- ✅ Bouton Airtel au même style que MVola --}}
    <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#importAirtel">
        <i class="fas fa-upload me-1"></i> Importer un fichier Airtel
    </button>


    {{-- ✅ Tableau des données importées --}}
        <div class="card card-body shadow border-0 table-wrapper table-responsive">

            <div class="card-header mb-3">
                <h5 class="mb-0">Aperçu des données importées</h5>

                <!-- Barre de progression -->
                <div class="progress mt-3" x-show="processing">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                        role="progressbar"
                        :style="`width: ${progress}%`">
                    </div>
                </div>
                @if ($env_mode === 'production')
                    <div class="alert alert-danger">
                        ⚠️ <strong>Mode PRODUCTION :</strong> Les actions réalisées ici impactent directement les données réelles. Veuillez procéder avec prudence.
                    </div>
                @else
                    <div class="alert alert-success">
                        ⚠️ Vous êtes actuellement en <strong>mode DEMO</strong>. Les actions effectuées sont simulées et sans conséquence réelle.
                    </div>
                @endif


            </div>

            {{-- Filtre  --}}
           <div class="d-flex mb-3 gap-3">
                <!-- Filtre Type -->
                <select class="form-select fmxw-200" wire:model="filterStatus">
                    <option value="all">Tous les statuts</option>
                    <option value="modified">Compte Modifier</option>
                    <option value="processed">Succès</option>
                    <option value="failed">Échoué</option>
                    <!-- Ajoute d'autres statuts selon ta base -->
                </select>

                <!-- Filtre Provider -->
                <select class="form-select fmxw-200" wire:model="filterProvider">
                    <option value="all">Tous les fournisseurs</option>
                    <option value="mvola">MVola</option>
                    <option value="airtel">Airtel</option>
                    <!-- Tu peux ajouter d'autres valeurs si besoin -->
                </select>

                <div class="ms-auto">
                    <button wire:click="$set('showModal', true)" class="btn btn-primary">
                        Lancer les transactions MVola
                    </button>
                </div>
            </div>


            <div class="table-responsive mb-4">
                <table class="table user-table table-hover align-items-center">
                    <thead>
                        <tr>
                            <th class="border-bottom">#</th>
                            <th class="border-bottom">Transaction</th>
                            <th class="border-bottom">Montant</th>
                            <th class="border-bottom">Statut</th>
                            <th class="border-bottom">Compte</th>
                            <th class="border-bottom">Envoyer par</th>
                            <th class="border-bottom">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($importedData as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $row['Transaction_Id'] }}</strong><br>
                                    <small>{{ $row->Transaction_Date }}</small>
                                </td>
                                <td>
                                    @if($editingId === $row->id)
                                        <strong>{{ number_format($row->Montant, 2) }} MGA </strong> <br>
                                        <input type="text" wire:model.defer="editCompte" class="form-control form-control-sm mt-1" />
                                    @else
                                        <strong>{{ number_format($row->Montant, 2) }} MGA </strong> <br>
                                        <small>Compte : {{ $row->Account }}</small>
                                    @endif

                                </td>
                                <td style="width: 160px; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">

                                    <strong> 
                                        @if ($row['status'] === 'processed')
                                            <span class="badge bg-success">Succès</span>
                                        @elseif ($row['is_ready'] === 1)
                                            <span class="badge bg-warning text-dark">Prêt à importer</span>
                                        @elseif ($row['status'] === 'modified')
                                            <span class="badge bg-danger">Compte Modifier</span>
                                        @else
                                            <span class="badge bg-secondary ">Ignorée</span>
                                        @endif
                                    </strong> <br>
                                    <small>{{ $row['code_operation'] }} </small>

                                </td>
                                <td>
                                    <strong>{{ $row['Compte'] }} | {{ $row['Type'] }}</strong><br>
                                    <small>De : {{ $row['De'] }} | Vers : {{ $row['Vers'] }}</small>
                                </td>
                                <td>
                                    <strong>{{ $row['processed_by'] ?? 'N/A' }}</strong><br>
                                    <small>Le : {{ $row['payment_date'] ?? 'Date d\'envoi à Musoni' }}</small>
                                </td>
                                <td>
                                    @if($editingId === $row->id)
                                        <button wire:click="saveEdit" class="btn btn-success btn-sm" disabled>Sauvegarder</button>
                                        <button wire:click="$set('editingId', null)" class="btn btn-secondary btn-sm">Annuler</button>
                                    @else
                                        <button wire:click="startEdit({{ $row->id }})" class="btn btn-primary btn-sm"
                                            {{ $row->status !== 'failed' ? 'disabled' : '' }}>Modifier</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
               
            </div>
             <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <small>
                        Affichage de {{ $importedData->firstItem() }} à {{ $importedData->lastItem() }} sur {{ $importedData->total() }} résultats
                    </small>
                </div>

                <div>
                    {{ $importedData->links() }}
                </div>
            </div>
        </div>


    {{-- ✅ Transactions ignorées --}}
    {{-- @if (!empty($ignored))
        <div class="alert alert-warning mt-3">
            <strong>Transactions ignorées :</strong>
            <ul class="mb-0">
                @foreach ($ignored as $txn)
                    <li>{{ $txn }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

    {{-- ✅ Modal d'importation Mvola --}}
    <div wire:ignore.self class="modal fade" id="importMvola" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Importer un fichier MVola</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="import">
                        <div class="mb-3">
                            <label class="form-label">Fichier MVola</label>
                            <input type="file" wire:model="fileMvola" accept=".xlsx,.xls,.csv" class="form-control" />
                            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Modal d'importation Airtel --}}
    <div wire:ignore.self class="modal fade" id="importAirtel" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Importer un fichier Airtel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="importAirtel">
                        <div class="mb-3">
                            <label class="form-label">Fichier Airtel</label>
                            <input type="file" wire:model="fileAirtel" accept=".xlsx,.xls,.csv" class="form-control" />
                            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   

    <!-- ✅ Modal Validation-->
    <div class="modal fade @if($showModal) show d-block @endif" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" role="dialog">
        <div class="modal-dialog" role="document" wire:ignore.self>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Authentification CBS</h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Login CBS</label>
                        <input type="text" class="form-control" wire:model.defer="login">
                    </div>
                    <div class="mb-3">
                        <label>Mot de passe CBS</label>
                        <input type="password" class="form-control" wire:model.defer="password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="$set('showModal', false)">Annuler</button>
                    <button class="btn btn-primary" wire:click="processTransactions">Valider et Lancer</button>
                </div>
            </div>
        </div>
    </div>

    
    {{-- ✅ Script pour success alert --}}
    <script>
        window.addEventListener('message-clear', () => {
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) alert.remove();
            }, 5000);
        });

        window.addEventListener('hide-import-modal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('importModal'));
            if (modal) modal.hide();
        });
    </script>
   

</div>
