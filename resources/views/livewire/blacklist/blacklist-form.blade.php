<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 mb-4">
        <div class="card border-0 shadow components-section">
            <div class="card-body">
                

                {{-- Formulaire de création --}}
                <form wire:submit.prevent="submit" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <input type="text" class="form-control" wire:model="full_name" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" wire:model="blacklist_type" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="client">Client</option>
                                <option value="fournisseur">Fournisseur</option>
                                <option value="prestataire">Prestataire</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">CIN</label>
                            <input
                                type="text"
                                wire:model="national_id"
                                id="national_id"
                                maxlength="15"
                                class="form-control"
                                placeholder="123 456 789 012"
                                oninput="formatNationalID(this)"
                                required
                            >
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Document justificatif (PDF)</label>
                            <input class="form-control" type="file" wire:model="document" accept="application/pdf">
                        </div>
                    </div>

                   

                    <div class="mb-3">
                        <label class="form-label">Nom de société</label>
                        <input type="text" class="form-control" wire:model="company_name">
                        <div class="form-text">Remplir si applicable</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Raison</label>
                        <textarea class="form-control" wire:model="reason" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes internes</label>
                        <textarea class="form-control" wire:model="notes" rows="2"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-secondary">Envoyer</button>
                    </div>
                    @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                </form>

                <hr class="my-4">

                {{-- Import Excel --}}
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                        <br>
                        <a href="{{ route('blacklist.template') }}" class="btn btn-sm btn-secondary mt-2">
                            📥 Télécharger le modèle Excel
                        </a>
                    </div>
                @endif

                <form wire:submit.prevent="importExcel">
                    <div class="mb-3">
                        <label class="form-label">Importer via Excel</label>
                        <input type="file" wire:model="excel_file" class="form-control" accept=".xls,.xlsx">
                        <div wire:loading wire:target="excel_file" class="form-text text-info">Chargement du fichier...</div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Importer</button>
                </form>
            </div>
        </div>
    </div>
</div>
