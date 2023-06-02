<?php

use App\Enums\Animal\SexEnum;
use App\Enums\Animal\WeightUnitEnum;

return [
    'placeholder' => [
        'unknown' => 'Unknown',
    ],

    'sex' => [
        SexEnum::Male->name => 'Male',
        SexEnum::Female->name => 'Female',
    ],

    'weight' => [
        WeightUnitEnum::Kg->name => 'Kg',
        WeightUnitEnum::Pound->name => 'Pound(s)',
    ],
];
