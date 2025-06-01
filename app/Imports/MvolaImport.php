<?php

namespace App\Imports;

use App\Models\Mvola as ModelsMvola;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;


use Illuminate\Support\Collection;

class MvolaImport implements OnEachRow, WithHeadingRow
{
    public static array $ignoredTransactions = []; // Liste statique

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        if (empty($data['n_transaction'])) {
            return;
        }

        $exists = ModelsMvola::where('Transaction_Id', $data['n_transaction'])->exists();

        if ($exists) {
            self::$ignoredTransactions[] = $data['n_transaction']; // Sauvegarde
            return;
        }

        ModelsMvola::create([
            'Transaction_Date'        => ($data['date'] ?? null) . ' ' . ($data['heure'] ?? ''),
            'Transaction_Id'          => $data['n_transaction'],
            'Tsansaction_Initiateur'  => $data['initiateur'] ?? null,
            'Type'                    => $data['type'] ?? null,
            'Canal'                   => $data['canal'] ?? null,
            'Status'                  => $data['statut'] ?? null,
            'Compte'                  => $data['compte'] ?? null,
            'Montant'                 => $data['montant_mga'] ?? null,
            'RRP'                     => $data['rrp'] ?? null,
            'De'                      => $data['de'] ?? null,
            'Vers'                    => $data['vers'] ?? null,
            'Balance_avant'           => $data['balance_avant'] ?? null,
            'Balance_apres'           => $data['balance_apres'] ?? null,
            'Details_1'               => $data['details_1'] ?? null,
            'Account'                 => $data['details_2'] ?? null,
            'Validateur'              => $data['validateur'] ?? null,
            'Num_notif'               => $data['n_pour_notif'] ?? null,
            'code_operation'          => $data['code_operation'] ?? null,
            'payment_date'            => isset($data['payment_date']) ? date('Y-m-d H:i:s', strtotime($data['payment_date'])) : null,
            'processed_by'            => $data['processed_by'] ?? null,
        ]);
    }

    public static function getIgnoredTransactions(): array
    {
        return self::$ignoredTransactions;
    }
}
