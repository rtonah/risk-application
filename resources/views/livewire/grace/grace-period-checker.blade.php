<div>
    <div class="row">
        <div class="col-12 col-xl-6">
            <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                {{-- Knowing others is intelligence; knowing yourself is true wisdom. --}}

                <div class="mb-4">
                    <label class="form-label">Numéro de prêt</label>
                    <input type="text" class="form-control" wire:model.defer="loanNumber" placeholder="Entrez le numéro de prêt">
                    <button class="btn btn-primary mt-2" wire:click="check">Vérifier</button>
                </div>

                @if ($comparisonResult)
                    @if ($comparisonResult['rule'] === 'ok')
                        <p class="text-success font-semibold">✅ Prêt > 12 mois : règles spéciales respectées.</p>
                    @elseif ($comparisonResult['rule'] === 'error')
                        <p class="text-danger font-semibold">❌ Prêt > 12 mois : règles spéciales non respectées.</p>
                        <ul class="list-disc pl-5 text-sm text-gray-700">
                            <li><strong>Durée :</strong> {{ $loanData['termFrequency'] ?? "Valeur non définie"}} mois</li>
                            <li>Délai grâce capital : {{ $comparisonResult['api_values']['graceCapital'] }}</li>
                            <li>Délai grâce intérêt : {{ $comparisonResult['api_values']['graceInterest'] }}</li>
                            <li>Interest Charged : {{ $comparisonResult['api_values']['interestCharged'] }}</li>
                        </ul>
                    @elseif ($comparisonResult['rule'] === 'normal')
                        <h6 class="font-bold mt-4">Comparaison avec la base :</h6>
                        <ul class="list-disc pl-5 mt-2">
                            <li><strong>Durée :</strong> {{ $loanData['termFrequency'] ?? "Valeur non définie"}} mois</li>

                            <li>
                                Capital : 
                                <span class="{{ $comparisonResult['capital'] ? 'text-success' : 'text-danger' }}">
                                    {{ $comparisonResult['capital'] ? 'OK' : 'Non conforme' }}
                                </span>
                                @unless($comparisonResult['capital'])
                                    <span class="text-sm text-gray-600"> (Attendu : {{ $expected->grace_period_capital ?? "Valeur non définie" }})</span>
                                @endunless
                            </li>
                            <li>
                                Intérêt : 
                                <span class="{{ $comparisonResult['interest'] ? 'text-success' : 'text-danger' }}">
                                    {{ $comparisonResult['interest'] ? 'OK' : 'Non conforme' }}
                                </span>
                                @unless($comparisonResult['interest'])
                                    <span class="text-sm text-gray-600"> (Attendu : {{ $expected->grace_period_interest_payment ?? "Valeur non définie"}})</span>
                                @endunless
                            </li>
                            <li>
                                Interest Charged : 
                                <span class="{{ $comparisonResult['charged'] ? 'text-success' : 'text-danger' }}">
                                    {{ $comparisonResult['charged'] ? 'OK' : 'Non conforme' }}
                                </span>
                                @unless($comparisonResult['charged'])
                                    <span class="text-sm text-gray-600"> (Attendu : {{ $expected->grace_on_interest_charged ?? "Valeur non définie" }})</span>
                                @endunless
                            </li>
                        </ul>
                    @endif
                @else
                    <p class="text-warning mt-3">Aucun résultat disponible.</p>
                @endif

            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card card-body border-0 shadow mb-4 mb-xl-0">
                
                @if ($loanData && array_key_exists('createStandingInstructionAtDisbursement', $loanData))
                    <h6 class="font-bold">Standing Instruction :</h6>
                    <div class="mt-2">
                        @if ($loanData['createStandingInstructionAtDisbursement'])
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                ✅ <strong>Standing Instruction activée :</strong> L’option est bien configurée pour ce prêt.
                            </div>
                        @else
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                ⚠️ <strong>Standing Instruction non activée :</strong> Cette fonctionnalité n’est pas activée à la mise en place du prêt.
                            </div>
                        @endif
                    </div>
                @endif
                
                @if (!is_null($fgmdResult))
                    <h6 class="font-bold mt-5">Vérification FGMD :</h6>
                    <div class="mt-2">
                        @if ($fgmdResult)
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                ✅ FGMD conforme : La valeur réelle correspond au barème attendu.
                            </div>
                        @else
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                ⚠️ FGMD non conforme :
                                <ul class="mt-2 list-disc ml-5">
                                    <li>Durée du prêt : {{ $loanData['termFrequency'] }} jours</li>
                                    <li>Taux attendu selon barème : <strong>{{ $fgmdExpectedRate }}%</strong></li>
                                    <li>Taux réellement appliqué : <strong>{{ $fgmdActualRate }}%</strong></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mt-2">

                    @if (!is_null($fgmdResult))
                        <ul>
                            <li>Durée du prêt : {{ $loanTermInDays }} jours</li>
                            <li>FGMD attendu : {{ $fgmdExpectedRate }}%</li>
                            <li>FGMD dans Musoni : {{ $fgmdActualRate }}%</li>
                            <li>Résultat : 
                                <span class="{{ $fgmdResult ? 'text-success' : 'text-danger' }}">
                                    {{ $fgmdResult ? 'OK' : 'Non conforme' }}
                                </span>
                            </li>
                        </ul>
                    @endif

                </div>




            </div>
        </div>
    </div>
</div>
