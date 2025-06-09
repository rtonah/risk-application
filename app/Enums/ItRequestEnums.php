<?php

// app/Enums/ItRequestEnums.php

namespace App\Enums;

class ItRequestEnums
{
    public const CATEGORIES = [
        'musoni',
        'odoo',
        'informatique',
        'sim_flotte',
        'mobile_banking',
    ];

    public const STATUSES = [
        'ouvert',
        'en_cours',
        'traite',
    ];

    public const PRIORITIES = [
        'faible',
        'normal',
        'urgent',
        'critique',
    ];
}
