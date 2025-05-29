<?php

namespace App\Exports;

use App\Models\SalaryPayment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalaryPaymentExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SalaryPayment::whereDate('created_at', today())
            ->select('employee_id', 'account_number', 'amount', 'label', 'status', 'operation_code', 'processed_by')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Matricule',
            'Numéro de compte',
            'Montant',
            'Libellé',
            'Statut',
            'Code d opération',
            'Traité par',
        ];
    }
}



