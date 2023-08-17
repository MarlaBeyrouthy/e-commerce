<?php

namespace App\Providers;

use App\Events\NewOrder;
use App\Events\ProductQuantityEmpty;
use App\Events\ProductSaleChanged;
use App\Listeners\ProductSaleChangedListener;
use App\Listeners\SendOrderNotification;
use App\Notifications\ProductQuantityEmptyNotification;
use App\Notifications\ProductSaleChangedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewOrder::class => [
            SendOrderNotification::class,
        ],

        ProductSaleChanged::class => [
            ProductSaleChangedListener::class,
        ],


        \App\Events\ProductQuantityEmpty::class => [
            \App\Listeners\ProductQuantityEmptyListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
