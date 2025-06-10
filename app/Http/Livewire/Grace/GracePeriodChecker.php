<?php

namespace App\Http\Livewire\Grace;

use App\Models\CbsCredential;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\GracePeriodValue;
use App\Models\FgmdRate;
use App\Models\VerificationHistory;
use Carbon\Carbon;

class GracePeriodChecker extends Component
{
    public $loanNumber;
    public $loanData;
    public $comparisonResult;

    public $fgmdResult;       // Résultat booléen
    public $fgmdExpectedRate; // Taux attendu
    public $fgmdActualRate;   // Taux réel récupéré de Musoni
    public $loanTermInDays;

    #.. Environnement de travail
    public $env_mode = 'demo';

    public function check()
    {
       // 3. Récupérer les credentials CBS du mode actif (démo ou production)
        $this->env_mode = get_setting('env_mode') ?? 'demo';
        $creds = CbsCredential::where('name', $this->env_mode)->first();
        if ($creds) {
            // dd($creds->login, $creds->password, $creds->domaine,  $creds->token);
            
            #.. Get information of the Loan
            $loanResponse = Http::withBasicAuth($creds->login, $creds->password)->withHeaders([
                "X-Fineract-Platform-TenantId" => "acepmg",
                "x-api-key" => $creds->token
            ])
            ->timeout(300)
            ->get($creds->domaine.'loans/'.$this->loanNumber);

            $chargeResponse = Http::withBasicAuth($creds->login, $creds->password)
            ->withHeaders([
                "X-Fineract-Platform-TenantId" => "acepmg",
                "x-api-key" => $creds->token
            ])
            ->timeout(300)
            ->get($creds->domaine.'loans/'.$this->loanNumber.'/charges');

            if ($loanResponse->ok() && $chargeResponse->ok()) {
                $this->loanData = $loanResponse->json();
                // $charges = $chargeResponse->json();   

                #.. Récupérer les valeurs fournies dans la requête
                $duration = $this->loanData['termFrequency'] ?? "Valeur non définie";
                $graceCapital = $this->loanData['graceOnPrincipalPayment'] ?? "Valeur non définie";
                $graceInterest = $this->loanData['graceOnInterestPayment'] ?? "Valeur non définie";
                $interestCharged = $this->loanData['graceOnInterestCharged'] ?? "Valeur non définie";

                // Vérification spéciale si la durée est > 12
                if ($duration > 12) {

                    if (
                        floatval($interestCharged) == 0 &&
                        intval($graceCapital) == intval($graceInterest)
                    ) {
                        $this->comparisonResult = [
                            'rule' => 'ok', // règle spéciale respectée
                            'capital' => true,
                            'interest' => true,
                            'charged' => true,
                        ];
                    } else {
                        $this->comparisonResult = [
                            'rule' => 'error',
                            'capital' => false,
                            'interest' => false,
                            'charged' => false,
                            'api_values' => [
                                'graceCapital' => $graceCapital,
                                'graceInterest' => $graceInterest,
                                'interestCharged' => $interestCharged
                            ]
                        ];
                        return; // stoppe ici, pas besoin de comparer à la base
                    }
                }

                // Comparaison normale (durée ≤ 12 ou si pas concerné par la règle spéciale)
                $this->expected = GracePeriodValue::where('loan_duration', $duration)->first();

                if ($this->expected) {
                    $this->comparisonResult = [
                        'rule' => 'normal',
                        'capital' => $graceCapital == $this->expected->grace_period_capital,
                        'interest' => $graceInterest == $this->expected->grace_period_interest_payment,
                        'charged' => floatval($interestCharged) == floatval($this->expected->grace_on_interest_charged),
                    ];
                } else {
                    $this->comparisonResult = null;
                }

                #.. FGMD 
                    // Decode JSON
                    $loans = json_decode($loanResponse->body(), true);
                    $charge = json_decode($chargeResponse->body(), true);

                    // Extract dates
                    $submittedOnDate = Carbon::create($loans['timeline']['submittedOnDate'][0], $loans['timeline']['submittedOnDate'][1], $loans['timeline']['submittedOnDate'][2]);
                    $expectedMaturityDate = Carbon::create($loans['timeline']['expectedMaturityDate'][0], $loans['timeline']['expectedMaturityDate'][1], $loans['timeline']['expectedMaturityDate'][2]);

                    // // 1. Obtenir la durée du prêt en jours - Calculate difference in days
                    $this->loanTermInDays = $submittedOnDate->diffInDays($expectedMaturityDate);

                    // 2. Chercher le taux FGMD attendu dans la table selon la durée
                    $rateRow = FgmdRate::where('min_days', '<=', $this->loanTermInDays)
                        ->where('max_days', '>=', $this->loanTermInDays)
                        ->first();
                        
                    $this->fgmdExpectedRate = $rateRow?->rate ?? null;

                    // 3. Extraire le taux réel (FGMD) depuis la réponse Musoni (charge ID 48)
                    $percentage = null;
                    foreach ($charge as $item) {
                        if ($item['chargeId'] == 48) {
                            $percentage = $item['percentage'];
                            break;
                        }
                    }
                    $this->fgmdActualRate =  $percentage ?? null;

                    // 4. Comparaison
                    if ($this->fgmdExpectedRate !== null && $this->fgmdActualRate !== null) {
                        $this->fgmdResult = round($this->fgmdExpectedRate, 2) == round($this->fgmdActualRate, 2);
                    } else {
                        $this->fgmdResult = null;
                    }

                #.. End FGMD
                
                VerificationHistory::create([
                    'user_id' => auth()->id(),
                    'loan_number' => $this->loanNumber,
                    'loan_duration_days' => $this->loanTermInDays,
                    'grace_capital_conform' => $this->comparisonResult['capital'] ?? false,
                    'grace_interest_conform' => $this->comparisonResult['interest'] ?? false,
                    'grace_interest_charged_conform' => $this->comparisonResult['charged'] ?? false,
                    'standing_instruction_activated' => $this->loanData['createStandingInstructionAtDisbursement'] ?? false,
                    'fgmd_conform' => $this->fgmdResult ?? false,
                    'fgmd_expected_rate' => $this->fgmdExpectedRate ?? null,
                    'fgmd_actual_rate' => $this->fgmdActualRate ?? null,
                ]);

            } else {
                dd("pas ok");
                $this->loanData = null;
                $this->comparisonResult = null;
            }


        } else {
            return;
        }

        
    }


    public function render()
    {
        return view('livewire.grace.grace-period-checker', [
            'expected' => $this->expected ?? null
        ]);
    }

}

