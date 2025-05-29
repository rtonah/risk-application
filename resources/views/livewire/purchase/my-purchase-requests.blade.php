<div class="card card-body shadow border-0 table-wrapper table-responsive">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Mes demandes d’achat</h4>

        <div>
            <label for="status" class="form-label me-2">Filtrer par statut</label>
            <select wire:model="status" id="status" class="form-select d-inline-block fmxw-200">
                <option value="">-- Tous --</option>
                <option value="draft">Brouillon</option>
                <option value="approved">Approuvé</option>
                <option value="pending">En attente</option>
                <option value="rejected">Rejeté</option>
            </select>
        </div>
    </div>

    <table class="table user-table table-hover align-items-center mb-0">
        <thead>
            <tr>
                <th class="border-bottom">ID</th>
                <th class="border-bottom">Titre</th>
                <th class="border-bottom">Statut</th>
                <th class="border-bottom">Créé le</th>
                <th class="border-bottom">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td class="fw-bold">{{ $request->title }}</td>
                    <td>
                        @php
                            $badgeColor = match($request->status) {
                                'approved' => 'bg-success',
                                'rejected' => 'bg-danger',
                                'pending' => 'bg-warning',
                                'draft' => 'bg-secondary',
                                default => 'bg-tertiary',
                            };
                        @endphp
                        <span class="badge {{ $badgeColor }}">{{ ucfirst($request->status) }}</span>
                    </td>
                    <td><span class="fw-normal">{{ $request->created_at->translatedFormat('d F Y H:i') }}</span></td>
                    <td>
                       <a href="#" class="btn btn-sm btn-primary" wire:click.prevent="showDetails({{ $request->id }})">
                            Voir
                        </a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Aucune demande trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($showModal && $selectedRequest)
        <div class="modal fade" id="modalDetails" tabindex="-1" role="dialog" aria-labelledby="modalDetailsLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de la demande #{{ $selectedRequest->id }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="card p-3 p-lg-4">
                            <p><strong>Titre :</strong> {{ $selectedRequest->title }}</p>
                            <p><strong>Statut :</strong> {{ ucfirst($selectedRequest->status) }}</p>

                            <hr>
                            <h6>Articles</h6>
                            <ul class="list-group">
                                @foreach ($selectedRequest->items as $item)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>{{ $item->product_name }} (x{{ $item->quantity }})</span>
                                        <span>{{ number_format($item->quantity * $item->unit_price, 2) }} Ariary</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            window.addEventListener('openModal', () => {
                const myModalEl = document.getElementById('modalDetails');
                const modal = new bootstrap.Modal(myModalEl);
                modal.show();

                // Optionnel : pour fermer la modal quand Livewire déclenche closeModal
                window.addEventListener('closeModal', () => {
                    modal.hide();
                });
            });
        </script>

    @endif


    


</div>
