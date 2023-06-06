<?php

namespace App\Enums\User;

enum EventRepeatSchemeEnum: string
{
    case Never = 'never';
    case EveryDay = 'every_day';
    case EveryWorkingDay = 'every_working_day';
    case EveryWeekend = 'every_weekend';
    case EveryWeek = 'every_week';
    case EveryMonth = 'every_month';
    case EveryYear = 'every_year';
}
