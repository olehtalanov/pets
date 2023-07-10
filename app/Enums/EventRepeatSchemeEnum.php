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
        return [
            self::Never->value => trans('common.repeatable.' . self::Never->value),
            self::EveryDay->value => trans('common.repeatable.' . self::EveryDay->value),
            self::EveryWorkingDay->value => trans('common.repeatable.' . self::EveryWorkingDay->value),
            self::EveryWeekend->value => trans('common.repeatable.' . self::EveryWeekend->value),
            self::EveryWeek->value => trans('common.repeatable.' . self::EveryWeek->value),
            self::EveryMonth->value => trans('common.repeatable.' . self::EveryMonth->value),
            self::EveryYear->value => trans('common.repeatable.' . self::EveryYear->value),
        ];
    }

    public function getName(): string
    {
        return trans('common.repeatable.' . $this->value);
    }
}
