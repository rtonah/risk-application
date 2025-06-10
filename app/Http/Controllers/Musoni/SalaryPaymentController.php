<?php

namespace App\Http\Controllers\Musoni;

use App\Models\SalaryPayment;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Imports\SalaryPaymentsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalaryPaymentTemplateExport;
use Illuminate\Support\Facades\Http;
use App\Models\CbsCredential;
use Carbon\Carbon;
use App\Exports\SalaryPaymentExport;
use App\Exports\SalaryPaymentsExport;

class SalaryPaymentController extends Controller
{
    #.. Variable public

    public $token;
    public $domaine;

    #.. Environnement de travail
    public $env_mode = 'demo';

    public function __construct()
    {
        // Code exécuté à chaque appel de méthode du contrôleur
        // 1. Charger tous les types de paiement
        $this->types_paiement = get_setting('types_paiement') ?? [];

        // 2. Extraire l'identifiant spécifique salaire
        // $this->payement_id = $this->types_paiement['compte_pivot'] ?? null;
        $this->payment_id = $this->types_paiement['compte_pivot'] ?? null;


        // 3. Récupérer les credentials CBS du mode actif (démo ou production)
        $this->env_mode = get_setting('env_mode') ?? 'demo';
        $creds = CbsCredential::where('name', $this->env_mode)->first();

        if ($creds) {
            $this->token = $creds->token;
            $this->domaine = $creds->domaine;
        } else {
            // Optionnel : valeurs par défaut si aucune config trouvée
            $this->token = null;
            $this->domaine = null;
        }
    }

    public function index(Request $request)
    {
        $query = SalaryPayment::with('paymentType')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = Carbon::parse($request->from_date)->startOfDay()->toDateString();
            $to = Carbon::parse($request->to_date)->endOfDay()->toDateString();

            $query->whereBetween('payment_date', [$from, $to]);
        }


        $salaryPayments = $query->paginate(15)->appends($request->all()); // Important pour garder les filtres en pagination

        return view('musoni.salary_payments.index', compact('salaryPayments'));
    }


    public function create()
    {
        // Récupération de tous les paiements déjà enregistrés (peut être retiré si non utilisé dans la vue)
        $salary_payments = SalaryPayment::all();

        // Types de paiement définis dans les paramètres d'application
        $types_paiement = get_setting('types_paiement') ?? [];

        // Inverser les paires clé-valeur pour un affichage plus propre
        $types_paiement_inverse = array_flip($types_paiement);

        // Nettoyage des noms (ex. "orange_paiement" => "Orange")
        $types_paiement_clean = [];
        foreach ($types_paiement_inverse as $id => $key) {
            $types_paiement_clean[$id] = ucfirst(str_replace('_paiement', '', $key));
        }

        return view('musoni.salary_payments.create', [
            'salary_payments' => $salary_payments,
            'types_paiement' => $types_paiement_clean,
        ]);
    }


    public function show(SalaryPayment $salaryPayment)
    {
        // return view('salary_payments.show', compact('salaryPayment'));
        return view('musoni.salary_payments.show', compact('salaryPayment'));
    }

    public function edit(SalaryPayment $salaryPayment)
    {
        $paymentTypes = PaymentType::where('active', true)->get();
        return view('musoni.salary_payments.edit', compact('salaryPayment', 'paymentTypes'));
    }

    public function update(Request $request, SalaryPayment $salaryPayment)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'label' => 'nullable|string|max:255',
            'payment_type_id' => 'required|exists:payment_types,id',
            'payment_date' => 'nullable|date',
        ]);

        $salaryPayment->update($validated);

        return redirect()->route('salary-payments.index')->with('success', 'Salaire mis à jour.');
    }

    public function destroy(SalaryPayment $salaryPayment)
    {
        $salaryPayment->delete();
        return redirect()->route('salary-payments.index')->with('success', 'Salaire supprimé.');
    }

    #.. Methode d'importaion de fichier
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new SalaryPaymentsImport, $request->file('file'));

        return back()->with('success', 'Salaires importés avec succès.');
    }

    #.. Template à utilisé
    public function downloadTemplate()
    {
        return Excel::download(new SalaryPaymentTemplateExport, 'template_salaire.xlsx');
    }

    #.. Tester la connexion CBS
    public function testConnexionCBS($login, $password)
    {
        try {
            $response = Http::withHeaders([
                'X-Fineract-Platform-TenantId' => 'acepmg',
                'x-api-key' => $this->token,
            ])
            ->timeout(20)
            ->post($this->domaine . 'authentication', [
                'username' => $login,
                'password' => $password
            ]);
            
            // dd($response->successful());

            if ($response->successful()) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
    #.. Dépot de salaire sur musoni
    
    // public function deposit(Request $request)
    // {
    //     $todayDate = Carbon::now()->format('d F Y');
    //     $today = Carbon::today();
    //     $creds = CbsCredential::where('name', 'demo')->first();

    //     $salaries = SalaryPayment::where('status', 'pending')
    //         ->whereNull('operation_code')
    //         ->whereDate('created_at', $today)
    //         ->whereNotIn('account_number', function ($query) use ($today) {
    //             $query->select('account_number')
    //                 ->from('salary_payments')
    //                 ->whereNotNull('operation_code')
    //                 ->whereDate('created_at', $today);
    //         })
    //         ->get();

    //     $alreadyProcessed = SalaryPayment::whereNotNull('operation_code')
    //         ->whereDate('created_at', $today)
    //         ->pluck('account_number');

    //     if ($alreadyProcessed->isNotEmpty()) {
    //         return redirect()->back()->with('warning', 'Attention : certains comptes ont déjà été traités aujourd\'hui.');
    //     }

    //     foreach ($salaries as $data) {
    //         $response = Http::withBasicAuth($creds->login, $creds->password)->withHeaders([
    //             "X-Fineract-Platform-TenantId" => "acepmg",
    //             "x-api-key" => $creds->token
    //         ])
    //         ->timeout(60)
    //         ->post($creds->domain . 'savingsaccounts/' . $data->account_number . '/transactions?command=deposit', [
    //             'locale' => 'en',
    //             'dateFormat' => 'dd MMMM yyyy',
    //             'transactionDate' => $todayDate,
    //             'transactionAmount' => $data->amount,
    //             'paymentTypeId' => $data->payment_type_id,
    //             'accountNumber' => $data->account_number,
    //             'receiptNumber' => $data->label,
    //         ]);

    //         $result = json_decode($response->body());

    //         if ($response->successful()) {
    //             $data->operation_code = $result->resourceId ?? 'no_resource_id';
    //             $data->status = 'processed';
    //         } else {
    //             $data->operation_code = $result->errors[0]->developerMessage ?? 'Erreur inconnue';
    //             $data->status = 'failed';
    //         }

    //         $data->save();
    //     }

    //     return redirect()->back()->with('success', 'Dépôts traités avec mise à jour des statuts.');
    // }

    public function deposit(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'payment_type_id' => 'required|integer',
        ]);


        // Tester la connexion CBS
        if (!$this->testConnexionCBS($request->login, $request->password)) {
            return redirect()->back()->with('error', 'Échec d’authentification : identifiants CBS invalides.');
        }

        try {
            $todayDate = Carbon::now()->format('d F Y');
            $today = Carbon::today();

            $salaries = SalaryPayment::where('status', 'pending')
                ->whereNull('operation_code')
                ->whereDate('created_at', $today)
                ->get();

            $duplicateCount = 0;
            $successCount = 0;
            $failCount = 0;

            foreach ($salaries as $data) {
                // Vérification de doublon
                $isDuplicate = SalaryPayment::where('account_number', $data->account_number)
                    ->where('label', $data->label)
                    ->whereDate('created_at', $today)
                    ->whereNotNull('operation_code')
                    ->exists();

                if ($isDuplicate) {
                    $data->operation_code = 'Doublon détecté (déjà traité)';
                    $data->status = 'failed';
                    $data->processed_by = $request->login;
                    $data->save();
                    $duplicateCount++;
                    continue;
                }

                // 📡 Appel API Musoni
                $response = Http::withBasicAuth($request->login, $request->password)
                    ->withHeaders([
                        "X-Fineract-Platform-TenantId" => "acepmg",
                        "x-api-key" => $this->token,
                    ])
                    ->timeout(60)
                    ->post($this->domaine . 'savingsaccounts/' . $data->account_number . '/transactions?command=deposit', [
                        'locale' => 'en',
                        'dateFormat' => 'dd MMMM yyyy',
                        'transactionDate' => $todayDate,
                        'transactionAmount' => $data->amount,
                        'paymentTypeId' => $request->payment_type_id,
                        'accountNumber' => $data->account_number,
                        'receiptNumber' => $data->label,
                    ]);

                $result = json_decode($response->body());
                // dd( $result);

                if ($response->successful()) {
                    $data->operation_code = $result->resourceId ?? 'no_resource_id';
                    $data->status = 'processed';
                    $data->payment_type_id = $request->payment_type_id;
                    $data->payment_date = now();
                    $successCount++;
                } else {
                    $data->operation_code = $result->errors[0]->developerMessage ?? 'Erreur inconnue';
                    $data->status = 'failed';
                    $data->payment_type_id = null;
                    $data->payment_date = null;
                    $failCount++;
                }

                $data->processed_by = $request->login;
                $data->save();
            }

            // Résumé
            $message = "Dépôt terminé : {$successCount} succès, {$failCount} échecs, {$duplicateCount} doublons.";

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }


    #.. Téléchargement résultat via excel 
    public function export()
    {
        return Excel::download(new SalaryPaymentExport, 'salary_payments_results.xlsx');
    }

}
