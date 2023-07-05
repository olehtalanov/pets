<?php

namespace App\Enums;

enum AppealStatusEnum: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Processed = 'processed';

    public function getName(): string
    {
        return trans('admin.appeals.statuses.' . $this->value);
    }
}
