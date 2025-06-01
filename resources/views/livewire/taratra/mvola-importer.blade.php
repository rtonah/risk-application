<div>
    {{-- ✅ Message de succès --}}
    @if ($successMessage)
        <div class="alert alert-success" id="success-alert">
            {{ $successMessage }}
        </div>
    @endif

    {{-- ✅ Bouton Modal Volt --}}
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#importModal">
        <i class="fas fa-upload me-1"></i> Importer un fichier MVola
    </button>

    {{-- ✅ Tableau des données importées --}}
        <div class="card card-body shadow border-0 table-wrapper table-responsive">

            <div class="card-header mb-3">
                <h5 class="mb-0">Aperçu des données importées</h5>
            </div>

            {{-- Filtre  --}}
            <div class="d-flex mb-3">

                <select id="typeFilter" class="form-select fmxw-200" wire:model="filterType" wire:change="loadImportedData">
                    <option value="all">Tous</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                    <option value="report">Rapport</option>
                    <option value="transfer">Transfert</option>
                    <!-- ajoute d'autres types selon tes données -->
                </select>
            </div>

            <div class="table-responsive mb-4">
                <table class="table user-table table-hover align-items-center">
                    <thead>
                        <tr>
                            <th class="border-bottom">#</th>
                            <th class="border-bottom">Transaction</th>
                            <th class="border-bottom">Montant</th>
                            <th class="border-bottom">Type</th>
                            <th class="border-bottom">Statut</th>
                            <th class="border-bottom">Compte</th>
                            <th class="border-bottom">Validateur</th>
                            <th class="border-bottom">Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($importedData as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $row['Transaction_Id'] }}</strong><br>
                                    <small>{{ $row['Transaction_Date'] }}</small>
                                </td>
                                <td>{{ number_format($row['Montant'], 2) }} MGA</td>
                                <td>{{ $row['Type'] }}</td>
                                <td>
                                    @if ($row['Status'] === 'SUCCESS')
                                        <span class="badge bg-success">Succès</span>
                                    @elseif ($row['Status'] === 'PENDING')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($row['Status']) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $row['Compte'] }}</strong><br>
                                    <small>De : {{ $row['De'] }} | Vers : {{ $row['Vers'] }}</small>
                                </td>
                                <td>{{ $row['Validateur'] ?? 'N/A' }}</td>
                                <td><small>{{ $row['Details_1'] }}<br>{{ $row['Account'] }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
               
            </div>
             <!-- Pagination -->
            <div class="d-flex justify-content-center">
{{ $importedData->links('pagination::bootstrap-5') }}

            </div>
        </div>


    {{-- ✅ Transactions ignorées --}}
    @if (!empty($ignored))
        <div class="alert alert-warning mt-3">
            <strong>Transactions ignorées :</strong>
            <ul class="mb-0">
                @foreach ($ignored as $txn)
                    <li>{{ $txn }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Modal d'importation Volt --}}
    <div wire:ignore.self class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
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
                            <input type="file" wire:model="file" accept=".xlsx,.xls,.csv" class="form-control" />
                            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Importer</button>
                    </form>
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
