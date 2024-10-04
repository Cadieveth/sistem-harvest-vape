<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\PurchaseCreated;
use App\Events\PurchaseUpdated;
use App\Events\PurchaseDeleted;
use App\Listeners\UpdateInventoryOnPurchaseCreated;
use App\Listeners\UpdateInventoryOnPurchaseUpdated;
use App\Listeners\UpdateInventoryOnPurchaseDeleted;

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
        PurchaseCreated::class => [
            UpdateInventoryOnPurchaseCreated::class,
        ],
        PurchaseUpdated::class => [
            UpdateInventoryOnPurchaseUpdated::class,
        ],
        PurchaseDeleted::class => [
            UpdateInventoryOnPurchaseDeleted::class,
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
