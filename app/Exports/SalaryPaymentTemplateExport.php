<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class SalaryPaymentTemplateExport implements FromArray
{
    public function array(): array
    {
        return [
            ['matricule', 'compte_courant', 'montant', 'libelle'],
            ['1114', '400156387', 150000, 'Salaire Mai 2025'],
        ];
    }
}
