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
    </div>
    
    {{-- Call view grace  --}}

    <div class="mb-4">
        <a href="{{ route('salary-payments.create') }}" class="btn btn-primary">+ Nouveau virement</a>
        <a href="{{ route('salary-payments.download', request()->all()) }}" class="btn btn-success" style="display: inline-flex; align-items: center;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px; height:16px; margin-right:6px;" fill="#217346" viewBox="0 0 24 24">
                <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zM14 3.5V9h4.5L14 3.5zM8.5 15.5l1.5-3 1.5 3h1l-2-4 2-4h-1l-1.5 3-1.5-3h-1l2 4-2 4h1z"/>
            </svg>
            Télécharger (Excel)
        </a>



    </div>
    <div class="col-12 col-xl-12">
        <div class="card border-0 shadow components-section">   
            <div class=" card-body table-wrapper table-responsive">
                
                {{-- #.. Formulaire de filtre  --}}
                <form method="GET" action="{{ route('salary-payments.index') }}" class="mb-3 d-flex align-items-end" id="filter-form">
                    {{-- Statut à gauche --}}
                    <div class="me-auto" style="min-width: 200px;">
                        <label for="status" class="form-label">Statut</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">-- Tous --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Traitée</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échouée</option>
                        </select>
                    </div>

                    {{-- Dates + bouton à droite --}}
                    <div class="d-flex gap-2" style="min-width: 300px;">
                        <div>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div>
                            <input type="date" name="to_date" id="to_date" class="form-control fmxw-600" value="{{ request('to_date') }}">
                        </div>
                        <div class="d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </div>
                    </div>
                </form>


               



                <table class="table user-table table-hover align-items-center">
                    <thead>
                        <tr>
                            <th class="border-bottom">#</th>
                            <th class="border-bottom">Utilisateur</th>

                            <th class="border-bottom">Montant</th>
                            <th class="border-bottom">Type de paiement</th>
                            <th class="border-bottom">Statut</th>
                            <th class="border-bottom">Code opération</th>
                            <th class="border-bottom">Traité par</th>
                            <th class="border-bottom">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaryPayments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td>
                                    <div class="d-block">
                                        <span class="fw-bold">Compte : {{ $payment->account_number }} </span> 
                                        <div class="small text-gray">Matricule : {{ $payment->employee_id }}</div>
                                    </div>
                                </td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->paymentType->name ?? '-' }}</td>
                                <td>
                                    @if($payment->status === 'processed')
                                        <span class="badge bg-success">Validé</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-block">
                                        <span class="fw-bold">{{ $payment->operation_code }} </span> 
                                        <div class="small text-gray">Date d'execution : 
                                            {{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d/m/Y') : 'N/A' }}
                                        </div>
                                    </div>
                                   
                                </td>
                                {{-- <td>{{ $payment->processed_by->user->first_name }}</td> --}}
                                <td>{{ $payment->processedBy->first_name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('salary-payments.destroy', $payment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit" 
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Confirmer la suppression ?')"
                                                {{ $payment->status !== 'pending' ? 'disabled' : '' }}
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>


    {{ $salaryPayments->links() }}

<script>
    document.getElementById('status').addEventListener('change', function () {
        document.getElementById('filter-form').submit();
    });
</script>




</x-layouts.app>
