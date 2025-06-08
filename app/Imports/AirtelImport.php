<?php

namespace App\Imports;

use App\Models\Mvola as ModelMomo;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
// use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class AirtelImport implements OnEachRow, WithHeadingRow, WithCustomCsvSettings
{
    public static array $ignoredTransactions = [];

    
    public function headingRow(): int
    {
        return 6;
    }
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ","
        ];
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        if (empty($data['transaction_id'])) {
            return;
        }

        $exists = ModelMomo::where('Transaction_Id', $data['transaction_id'])->exists();

        if ($exists) {
            self::$ignoredTransactions[] = $data['transaction_id'];
            return;
        }

        // ✅ Vérifier si la ligne est "prête" pour traitement
        $isReady = !empty($data['transaction_amount']) && is_numeric($data['transaction_amount']) && $data['transaction_amount'] > 0 &&
                   is_numeric($data['reference_number']) && strtolower($data['transaction_status']) === 'transaction success' && 
                   strtolower($data['transaction_type']) === 'mr';

        ModelMomo::create([
            'Transaction_Date'  => $data['transaction_date_and_time'] ?? null,
            'Transaction_Id'    => $data['transaction_id'],
            'Account'           => $data['reference_number'] ?? null,
            'De'                => $data['sender_msisdn'] ?? null,
            'Vers'              => $data['receiver_msisdn'] ?? null,
            'Montant'           => floatval($data['transaction_amount']),
            'Balance_avant'     => $data['previous_balance'] ?? null,
            'Balance_apres'     => $data['post_balance'] ?? null,
            'Type'              => $data['transaction_type'] ?? null,
            'RRP'               => $data['payment_reference'] ?? null,
            'Details_1'         => $data['service_name'] ?? null,
            'Status'            => $data['transaction_status'] ?? null,
            'processed_by'      => Auth::id(),
            'Compte'            => 'Airtel',
            'code_operation'    => 'new',
            'provider'          => 'airtel',
            'is_ready'          => $isReady,
        ]);
    }

    public static function getIgnoredTransactions(): array
    {
        return self::$ignoredTransactions;
    }
}
