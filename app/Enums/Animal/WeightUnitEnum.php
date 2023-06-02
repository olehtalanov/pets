<?php

namespace App\Enums\Animal;

enum WeightUnitEnum
{
    case Kg;
    case Pound;

    public function getName(): string
    {
        return trans('common.weight.'.$this->name);
    }
}
