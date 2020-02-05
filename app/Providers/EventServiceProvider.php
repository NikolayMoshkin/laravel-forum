<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        'Illuminate\Auth\Events\Verified' => [
//            'App\Listeners\LogVerifiedUser',
//        ], //TODO:можно добавить свой listner на подтвреждение email

        \App\Events\ThreadHasNewReply::class => [
            \App\Listeners\NotifyThreadSubscribers::class,
            \App\Listeners\NotifyMentionedUsers::class,
        ],
        'App/Events/UserWasBanned' => [
            'App/Listeners/UserBannedNotification'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
