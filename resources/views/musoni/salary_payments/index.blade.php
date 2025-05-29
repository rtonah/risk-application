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
    </div>
    <div class="col-12 col-xl-12">
        <div class="card border-0 shadow components-section">   
            <div class=" card-body table-wrapper table-responsive">
                
                <table class="table user-table table-hover align-items-center">
                    <thead>
                        <tr>
                            <th class="border-bottom">#</th>
                            <th class="border-bottom">Matricule</th>
                            <th class="border-bottom">Compte</th>
                            <th class="border-bottom">Montant</th>
                            <th class="border-bottom">Type de paiement</th>
                            <th class="border-bottom">Date</th>
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
                                <td>{{ $payment->employee_id }}</td>
                                <td>{{ $payment->account_number }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->paymentType->name ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d/m/Y') }}</td>
                                <td>
                                    @if($payment->status === 'processed')
                                        <span class="badge bg-success">Validé</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $payment->operation_code }}</td>
                                {{-- <td>{{ $payment->processed_by->user->first_name }}</td> --}}
                                <td>{{ $payment->processedBy->first_name ?? 'N/A' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('salary-payments.destroy', $payment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">
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




</x-layouts.app>
