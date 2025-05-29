<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FgmdRate;

class FgmdRatesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['min_days' => 1, 'max_days' => 179, 'rate' => 0.60],
            ['min_days' => 180, 'max_days' => 271, 'rate' => 1.00],
            ['min_days' => 272, 'max_days' => 363, 'rate' => 1.20],
            ['min_days' => 364, 'max_days' => 453, 'rate' => 1.40],
            ['min_days' => 454, 'max_days' => 577, 'rate' => 1.80],
            ['min_days' => 578, 'max_days' => 760, 'rate' => 2.60],
            ['min_days' => 761, 'max_days' => 9999, 'rate' => 3.00],
        ];

        foreach ($data as $item) {
            FgmdRate::create($item);
        }
    }

}
