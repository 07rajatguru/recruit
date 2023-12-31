<?php

namespace App\Providers;
use App\Events\sendMail;
use App\Listeners\NotificationEventListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PermissionSeederEvent' => [
            'App\Listeners\PermissionSeederEventListener',
        ],
        'App\Events\NotificationEvent' => [
            'App\Listeners\NotificationEventListener',
        ],
        'App\Events\NotificationMail' => [
            'App\Listeners\NotificationMailListener',
        ],

        // sendMail::class => [
        //     NotificationEventListener::class,  
        // ],
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
