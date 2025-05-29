<?php

namespace App\Imports;

use App\Models\SalaryPayment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalaryPaymentsImport implements ToModel, WithHeadingRow
{
    /**
     * Spécifie à partir de quelle ligne commencer (l'entête est à la ligne 3).
     */

    public function model(array $row)
    {
        // dd($row['matricule']);
        try {
            $doublon = SalaryPayment::where('account_number', $row['compte_courant'])
                ->whereDate('created_at', now())
                ->whereNull('operation_code')
                ->count();

            if ($doublon == 0) {
                return new SalaryPayment([
                    'employee_id'     => (int) $row['matricule'],          // cast en entier
                    'account_number'  => (string) $row['compte_courant'],  // cast en string si besoin
                    'amount'          => (float) $row['montant'],
                    'label'           => trim((string) $row['libelle']),
                    'payment_type_id' => (int) $row['type_de_payement'],
                ]);

            }
        } catch (\Exception $e) {
            // Tu peux aussi logger l'erreur pour mieux diagnostiquer
            report($e);
            abort(404);
        }
    }
}

