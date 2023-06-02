<?php

namespace App\Enums\User;

enum EventRepeatSchemeEnum
{
    case Never;
    case EveryDay;
    case EveryWorkingDay;
    case EveryWeekend;
    case EveryWeek;
    case EveryMonth;
    case EveryYear;
}
