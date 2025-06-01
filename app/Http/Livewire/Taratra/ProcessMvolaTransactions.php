<?php

namespace App\Http\Livewire\Taratra;

// app/Http/Livewire/ProcessMvolaTransactions.php

use Livewire\Component;
use App\Models\Mvola;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ProcessMvolaTransactions extends Component
{
    public $login, $password, $token, $domaine;
    public $message;

    public function process()
    {
        $today = now()->toDateString();
        $todayDate = now()->format('d F Y');

        $salaries = Mvola::whereDate('updated_at', now())
            ->where(function($q) {
                $q->whereNull('code_operation')
                  ->orWhere('status', 'Finalisée')
                  ->orWhere('status', 'completed');
            })
            ->where('type', 'Transfert')
            ->get();

        $duplicateCount = 0;
        $successCount = 0;
        $failCount = 0;

        foreach ($salaries as $data) {
            $isDuplicate = Mvola::where('account_number', $data->account_number)
                ->where('label', $data->label)
                ->whereDate('created_at', $today)
                ->whereNotNull('code_operation')
                ->exists();

            if ($isDuplicate) {
                $data->code_operation = 'Doublon détecté';
                $data->status = 'failed';
                $data->processed_by = Auth::id();
                $data->save();
                $duplicateCount++;
                continue;
            }

            $response = Http::withBasicAuth($this->login, $this->password)
                ->withHeaders([
                    'X-Fineract-Platform-TenantId' => 'acepmg',
                    'x-api-key' => $this->token,
                ])
                ->timeout(60)
                ->post("{$this->domaine}savingsaccounts/{$data->account_number}/transactions?command=deposit", [
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
                $data->code_operation = $result->resourceId ?? 'no_resource_id';
                $data->status = 'processed';
                $data->payment_date = now();
                $successCount++;
            } else {
                $data->code_operation = $result->errors[0]->developerMessage ?? 'Erreur inconnue';
                $data->status = 'failed';
                $data->payment_date = null;
                $failCount++;
            }

            $data->processed_by = Auth::id();
            $data->save();
        }

        $this->message = "Transactions : $successCount réussies, $failCount échouées, $duplicateCount doublons.";
    }

    public function render()
    {
        return view('livewire.process-mvola-transactions');
    }
}

}
