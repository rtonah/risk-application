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
        $paymentTypes = PaymentType::where('active', true)->get();
        $salaryPayments = SalaryPayment::with('paymentType')->latest()->paginate(15);
        return view('musoni.salary_payments.create', compact('paymentTypes', 'salaryPayments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'label' => 'nullable|string|max:255',
            'payment_type_id' => 'required|exists:payment_types,id',
            'payment_date' => 'nullable|date',
        ]);

        SalaryPayment::create($validated);

        return redirect()->route('salary-payments.index')->with('success', 'Salaire enregistré avec succès.');
    }

    public function show(SalaryPayment $salaryPayment)
    {
        return view('salary_payments.show', compact('salaryPayment'));
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
        $todayDate = Carbon::now()->format('d F Y');
        $today = Carbon::today();
        $creds = CbsCredential::where('name', 'demo')->first();

        $salaries = SalaryPayment::where('status', 'pending')
            ->whereNull('operation_code')
            ->whereDate('created_at', $today)
            ->get();

            // dd($salaries);

        $duplicateCount = 0;

        foreach ($salaries as $data) {
            
            // Vérification doublon : même compte, même libellé, même jour
            $isDuplicate = SalaryPayment::where('account_number', $data->account_number)
                ->where('label', $data->label)
                ->whereDate('created_at', $today)
                ->whereNotNull('operation_code')
                ->exists();

            if ($isDuplicate) {
                $data->operation_code = 'Doublon détecté (déjà traité)';
                $data->status = 'failed';
                $data->processed_by = auth()->id(); // 👈 Aussi ici
                $data->save();
                $duplicateCount++;
                continue;
            }

            // Appel API Musoni
            $response = Http::withBasicAuth($creds->login, $creds->password)->withHeaders([
                "X-Fineract-Platform-TenantId" => "acepmg",
                "x-api-key" => $creds->token
            ])
            ->timeout(60)
            ->post($creds->domaine . 'savingsaccounts/' . $data->account_number . '/transactions?command=deposit', [
                'locale' => 'en',
                'dateFormat' => 'dd MMMM yyyy',
                'transactionDate' => $todayDate,
                'transactionAmount' => $data->amount,
                'paymentTypeId' => $data->payment_type_id,
                'accountNumber' => $data->account_number,
                'receiptNumber' => $data->label,
            ]);

            $result = json_decode($response->body());

           if ($response->successful()) {
                $data->operation_code = $result->resourceId ?? 'no_resource_id';
                $data->status = 'processed';
                $data->payment_date = now(); // 👈 On enregistre la date actuelle
            } else {
                $data->operation_code = $result->errors[0]->developerMessage ?? 'Erreur inconnue';
                $data->status = 'failed';
                $data->payment_date = null; // 👈 On peut aussi forcer à null si échec
            }

            $data->processed_by = auth()->id(); // 👈 Ajout de l'utilisateur
            $data->save();

        }

        // Message final
        if ($duplicateCount > 0) {
            return redirect()->back()->with('warning', "{$duplicateCount} doublon(s) détecté(s) et ignoré(s). Le reste a été traité.");
        }

        return redirect()->back()->with('success', 'Tous les dépôts ont été traités avec succès.');
    }

    #.. Téléchargement résultat via excel 
    public function export()
    {
        return Excel::download(new SalaryPaymentExport, 'salary_payments_results.xlsx');
    }

}
