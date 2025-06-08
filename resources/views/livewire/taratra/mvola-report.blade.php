<div>
    {{-- ✅ Filtres de recherche --}}
    <div class="card card-body shadow border-0 mb-4">
        <h5 class="mb-3">Filtrer les transactions</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Date de début</label>
                <input type="date" wire:model="fromDate" class="form-control" />
            </div>
            <div class="col-md-3">
                <label class="form-label">Date de fin</label>
                <input type="date" wire:model="toDate" class="form-control" />
            </div>
            <div class="col-md-3">
                <label class="form-label">Compte (Account)</label>
                <input type="text" wire:model.debounce.500ms="accountSearch" class="form-control" placeholder="Numéro de compte" />
            </div>
            <div class="col-md-3">
                <label class="form-label">Expéditeur (De)</label>
                <input type="text" wire:model.debounce.500ms="senderSearch" class="form-control" placeholder="Numéro mobile money" />
            </div>
        </div>
    </div>

    {{-- ✅ Tableau des transactions --}}
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <h5 class="mb-3">Liste des transactions</h5>
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="border-bottom">#</th>
                    <th class="border-bottom">Transaction</th>
                    <th class="border-bottom">Montant</th>
                    <th class="border-bottom">Statut</th>
                    <th class="border-bottom">Compte</th>
                    <th class="border-bottom">Envoyer par</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $row['Transaction_Id'] }}</strong><br>
                            <small>{{ $row->Transaction_Date }}</small>
                        </td>
                        <td>
                            <strong>{{ number_format($row->Montant, 2) }} MGA</strong><br>
                            <small>Compte : {{ $row->Account }}</small>
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
                                    <span class="badge bg-secondary ">Champ à ignorée</span>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Aucune transaction trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

       {{-- ✅ Pagination propre --}}
        <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
            {{-- Infos --}}
            <div class="fw-normal small mt-4 mt-lg-0">
                Affichage de {{ $transactions->firstItem() }} à {{ $transactions->lastItem() }} sur {{ $transactions->total() }} résultats
            </div>
            {{-- Pagination --}}
            <div>
                {{ $transactions->links() }}
            </div>

            
        </div>

    </div>
</div>
