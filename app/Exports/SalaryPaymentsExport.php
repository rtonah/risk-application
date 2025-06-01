<?php

namespace App\Exports;

use App\Models\SalaryPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;



class SalaryPaymentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Sélectionne explicitement les colonnes correspondant aux headings
        return SalaryPayment::select('employee_id', 'account_number', 'operation_code', 'status', 'payment_date', 'amount', 'created_at')->get();
    }

    public function headings(): array
    {
        return ['Matricule', 'Compte', 'Code Opération', 'Statut', 'Date de Paiement', 'Montant', 'Créé le'];
    }
}

