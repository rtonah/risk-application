<x-layouts.app>
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4 animate__animated animate__fadeIn">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb bg-transparent px-0 py-1 text-primary fw-semibold">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#" class="text-dark">Musoni</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Virement salarial</li>
                </ol>
            </nav>
            <h2 class="h4 fw-bold text-dark mb-1">💸 Virement salarial vers MUSONI</h2>
            <p class="text-muted">Traitement des paiements salariaux vers Musoni</p>
        </div>
    </div>

    <!-- Upload Excel -->
    <div class="row animate__animated animate__fadeInUp justify-content-center">
        <div class="col-12 col-xl-4 mb-4 ">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('salary-payments.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold">📂 Fichier Excel</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload me-1"></i> Importer
                            </button>
                            <a href="{{ route('salary-payments.template') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-download me-1"></i> Télécharger modèle
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table + Mode Info -->
        <div class="col-12 col-xl-12">
            <div class="card border-0 shadow components-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @if ($env_mode === 'production')
                        <div class="alert alert-danger mb-0 me-3">
                            ⚠️ <strong>Mode LIVE :</strong> Les actions réalisées ici impactent directement les données réelles.
                        </div>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modal-form-signup">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M22 2L11 13M22 2L15 22L11 13L2 9l20-7z"/>
                            </svg>
                            Send to Live Musoni
                        </button>
                    @else
                        <div class="alert alert-success mb-0 me-3">
                            ⚠️ <strong>Mode DEMO :</strong> Les actions sont simulées.
                        </div>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-form-signup">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M22 2L11 13M22 2L15 22L11 13L2 9l20-7z"/>
                            </svg>
                            Send to Demo Musoni
                        </button>
                    @endif

                </div>

                <div class="card-body table-wrapper table-responsive">
                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Alerte :</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success d-flex justify-content-between align-items-center">
                            <span>{{ session('success') }}</span>
                            <a href="{{ route('salary-payments.export') }}" class="btn btn-sm btn-success">
                                📥 Télécharger le fichier Excel
                            </a>
                        </div>
                    @endif

                    <table class="table user-table table-hover align-items-center">
                        <thead>
                        <tr>
                            <th>Compte</th>
                            <th>Montant</th>
                            <th>Code opération</th>
                            <th>Responsable</th>
                            <th>Statut</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($salary_payments as $payment)
                            <tr>
                                <td>
                                    <strong>{{ $payment->account_number }}</strong><br>
                                    <small>Matricule : {{ $payment->employee_id }}</small>
                                </td>
                                <td>
                                    <strong>{{ number_format($payment->amount, 2) }} MGA</strong><br>
                                    <small>{{ $payment->label }}</small>
                                </td>
                                <td>
                                    <strong>{{ $payment->operation_code }}</strong><br>
                                    <small>Type : {{ $types_paiement[$payment->payment_type_id] ?? 'Inconnu' }}</small>
                                </td>
                                <td>
                                    <strong>User : {{ $payment->processed_by }}</strong><br>
                                    <small>{{ $payment->updated_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    @php
                                        $status = $payment->status;
                                        $badgeClass = match($status) {
                                            'processed' => 'badge rounded-pill bg-success bg-gradient shadow-sm',
                                            'failed' => 'badge rounded-pill bg-danger bg-gradient shadow-sm',
                                            'pending' => 'badge rounded-pill bg-warning text-dark shadow-sm',
                                            default => 'badge rounded-pill bg-secondary',
                                        };
                                        $translatedStatus = [
                                            'processed' => 'Traité',
                                            'failed' => 'Échoué',
                                            'pending' => 'En attente',
                                        ][$status] ?? ucfirst($status);
                                    @endphp
                                    <span class="{{ $badgeClass }} animate__animated animate__fadeIn">
                                        {{ $translatedStatus }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-form-signup" tabindex="-1" aria-labelledby="modal-form-signup" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-body p-0">
                    <div class="card border-0 p-4">
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Fermer"></button>

                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Connexion à Musoni</h4>
                            <p class="text-muted mb-0">Veuillez entrer vos identifiants</p>
                        </div>

                        <form action="{{ route('salary-payments.deposit') }}" method="POST">
                            @csrf

                            {{-- Login --}}
                            <div class="mb-3">
                                <label for="login" class="form-label">Login</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 2a5 5 0 100 10 5 5 0 000-10zM2 18a8 8 0 1116 0H2z"
                                                clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <input type="text" class="form-control" name="login" id="login" required placeholder="Nom d'utilisateur">
                                </div>
                            </div>

                            {{-- Mot de passe --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd"/>
                                        </svg>
                                    </span>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Minimum 6 caractères" minlength="6" required>
                                </div>
                            </div>

                            {{-- Type de paiement --}}
                            @php
                                $defaultType = old('payment_type_id', '237');
                            @endphp
                            <div class="mb-4">
                                <label for="payment_type_id" class="form-label">Type de paiement</label>
                                <select name="payment_type_id" id="payment_type_id" class="form-select" required>
                                    <option value="">-- Sélectionnez un type --</option>
                                    @foreach ($types_paiement as $id => $name)
                                        <option value="{{ $id }}" {{ $id == $defaultType ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- Conditions générales --}}
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="remember" required>
                                <label class="form-check-label" for="remember">
                                    J’accepte les <a href="#" class="fw-bold text-decoration-underline">conditions générales</a>
                                </label>
                            </div>

                            {{-- Bouton Submit --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-2"></i>Envoyer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
