<?php

namespace App\Enums\Animal;

enum SexEnum: string
{
    case Male = 'male';
    case Female = 'female';

    public function getName(): string
    {
        return trans('common.sex.'.$this->value);
    }
}
