<?php

namespace App\Imports;

use App\Models\Blacklist;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BlacklistImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // skip header

            Blacklist::create([
                'full_name' => $row[0],
                'national_id' => $row[1],
                'reason' => $row[2],
                'blacklist_type' => $row[3] ?? 'client',
                'company_name' => $row[4] ?? null,
                'notes' => $row[5] ?? null,
                'created_by' => auth()->id() ?? 1,
            ]);
        }
    }
}

