<?php

namespace App\Enums;

enum WeightUnitEnum: string
{
    case Kg = 'kg';
    case Pound = 'pound';

    public function getName(): string
    {
        return trans('common.weight.' . $this->value);
    }
}
