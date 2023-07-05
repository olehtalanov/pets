<?php

namespace App\Enums;

enum SexEnum: string
{
    case Male = 'male';
    case Female = 'female';

    public function getName(): string
    {
        return trans('common.sex.' . $this->value);
    }
}
