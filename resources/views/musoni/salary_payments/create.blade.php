<x-layouts.app>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Musoni</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Virement salarial</li>
                </ol>
            </nav>
            <h2 class="h4">Virement salarial MUSONI</h2>
            <p class="mb-0">Payement.</p>
        </div>
        <form action="{{ route('salary-payments.deposit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center""> 
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    Envoyer vers Musoni
                </button>
            </div>
        </form>
        
    </div>
    
    {{-- Call view grace  --}}

    <div class="row ">
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">     
                    <form action="{{ route('salary-payments.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">Fichier Excel</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Importer</button>
                        <a href="{{ route('salary-payments.template') }}" class="btn btn-secondary">
                            Télécharger le modèle Excel
                        </a>

                    </form>

                    <form action="{{ route('salary-payments.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
                            ✅ Saisie Manuel : vous avez la possibilité d’effectuer une saisie manuelle.
                        </div>
                        
                        <!-- Matricule employé -->
                        <div class="mb-3">
                            <label for="employee_id">Matricule employé</label>
                            <input type="text" class="form-control is-valid" id="employee_id" name="employee_id" required>
                            <div class="valid-feedback">
                                Saisissez le matricule de l'employé
                            </div>                
                        </div>

                        <!-- Compte courant -->
                        
                        <div class="mb-3">
                            <label for="account_number">Compte courant</label>
                            <input type="text" class="form-control is-valid" id="account_number" name="account_number" required>
                            <div class="valid-feedback">
                                Numéro du compte courant
                            </div>                
                        </div>

                        <!-- Montant -->
                        <div class="mb-3">
                            <label for="amount">Montant</label>
                            <input type="number" step="0.01" class="form-control is-valid" id="amount" name="amount" required>
                            <div class="valid-feedback">
                                Montant à verser
                            </div>                
                        </div>

                        <!-- Libellé -->
                        <div class="mb-3">
                            <label for="label">Libellé</label>
                            <input type="text" class="form-control" id="label" name="label">
                        </div>

                        <!-- Type de paiement -->
                        <div class="mb-3">
                            <label for="payment_type_id">Type de paiement</label>
                            <select name="payment_type_id" id="payment_type_id" class="form-select is-valid" required>
                                @foreach($paymentTypes as $typePayement)
                                    <option value="{{ $typePayement->id }}">{{  $typePayement->name }}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Sélectionnez le type de paiement
                            </div>
                        </div>

                        <!-- Date de paiement -->
                        <div class="mb-3">
                            <label for="payment_date">Date de paiement</label>
                            <input type="date" class="form-control" id="payment_date" name="payment_date">
                        </div>

                        <!-- Boutons -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-secondary">Enregistrer</button>
                            <a href="{{ route('salary-payments.index') }}" class="ml-4 text-gray-600"> 
                                <button class="btn btn-outline-gray-500" type="button">Annuler</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow components-section">   
                <div class=" card-body table-wrapper table-responsive">
                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Alerte :</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                            <a href="{{ route('salary-payments.export') }}" class="btn btn-sm btn-success ms-3">📥 Télécharger le fichier Excel</a>
                        </div>
                    @endif
                    
                     <table class="table user-table table-hover align-items-center">
                        <thead>
                            <tr>
                                <th class="border-bottom">#</th>
                                <th class="border-bottom">Matricule</th>
                                <th class="border-bottom">Compte</th>
                                <th class="border-bottom">Montant</th>
                                <th class="border-bottom">Type de paiement</th>
                                <th class="border-bottom">Code opération</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salaryPayments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>{{ $payment->employee_id }}</td>
                                    <td>{{ $payment->account_number }}</td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->paymentType->name ?? '-' }}</td>
                                    <td>{{ $payment->operation_code }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>






</x-layouts.app>
