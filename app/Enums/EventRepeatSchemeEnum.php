<?php

namespace App\Enums;

enum EventRepeatSchemeEnum: string
{
    case Never = 'never';
    case EveryDay = 'every_day';
    case EveryWorkingDay = 'every_working_day';
    case EveryWeekend = 'every_weekend';
    case EveryWeek = 'every_week';
    case EveryMonth = 'every_month';
    case EveryYear = 'every_year';

    public static function getNames(): array
    {
        return trans('common.repeatable');
    }

    public function getName(): string
    {
        return trans('common.repeatable.' . $this->value);
    }
}
