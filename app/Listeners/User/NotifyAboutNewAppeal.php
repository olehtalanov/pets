<?php

namespace App\Listeners\User;

use App\Events\User\AppealAdded;
use App\Mail\Admin\Appeal;
use Mail;

class NotifyAboutNewAppeal
{
    /**
     * Handle the event.
     */
    public function handle(AppealAdded $event): void
    {
        Mail::to(config('app.notifications.appeal'))->send(new Appeal($event->appeal));
    }
}
