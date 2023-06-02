<?php

namespace App\Enums\Animal;

enum SexEnum
{
    case Male;
    case Female;

    public function getName(): string
    {
        return trans('common.sex.'.$this->name);
    }
}
