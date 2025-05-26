<?php

namespace App\Exports;

use App\Models\Blacklist;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BlacklistExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Blacklist::select('full_name', 'national_id', 'reason', 'status', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'National ID',
            'Reason',
            'Status',
            'Date Added',
        ];
    }
}

