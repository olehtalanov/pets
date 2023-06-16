<?php

namespace App\Listeners\User;

use App\Events\User\Login;
use App\Mail\Auth\PersonalCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendPersonalCode implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        Mail::to($event->code->user)->send(new PersonalCode($event->code));
    }
}
