<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PeminjamanCreated;
use App\Listeners\SendPeminjamanNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PeminjamanCreated::class => [
            SendPeminjamanNotification::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}

