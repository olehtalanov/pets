<?php

use App\Enums\Animal\SexEnum;
use App\Enums\Animal\WeightUnitEnum;

return [
    'placeholder' => [
        'unknown' => 'Unknown',
    ],

    'sex' => [
        SexEnum::Male->value => 'Male',
        SexEnum::Female->value => 'Female',
    ],

    'weight' => [
        WeightUnitEnum::Kg->value => 'Kg',
        WeightUnitEnum::Pound->value => 'Pound(s)',
    ],
];
