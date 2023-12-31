<?php

namespace App\Providers;

use App\Models\Animal;
use App\Models\Chat;
use App\Models\Event;
use App\Models\Message;
use App\Models\Note;
use App\Models\Pin;
use App\Models\Review;
use App\Policies\AnimalPolicy;
use App\Policies\ChatPolicy;
use App\Policies\EventPolicy;
use App\Policies\MessagePolicy;
use App\Policies\NotePolicy;
use App\Policies\PinPolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Animal::class => AnimalPolicy::class,
        Event::class => EventPolicy::class,
        Note::class => NotePolicy::class,
        Pin::class => PinPolicy::class,
        Review::class => ReviewPolicy::class,
        Chat::class => ChatPolicy::class,
        Message::class => MessagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //
    }
}
