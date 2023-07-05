<?php

use App\Enums\EventRepeatSchemeEnum;
use App\Enums\SexEnum;
use App\Enums\WeightUnitEnum;

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

    'repeatable' => [
        EventRepeatSchemeEnum::Never->value => 'Never',
        EventRepeatSchemeEnum::EveryDay->value => 'Every day',
        EventRepeatSchemeEnum::EveryWorkingDay->value => 'Every working day',
        EventRepeatSchemeEnum::EveryWeekend->value => 'Every weekend',
        EventRepeatSchemeEnum::EveryWeek->value => 'Every week',
        EventRepeatSchemeEnum::EveryMonth->value => 'Every month',
        EventRepeatSchemeEnum::EveryYear->value => 'Every year',
    ],
];
