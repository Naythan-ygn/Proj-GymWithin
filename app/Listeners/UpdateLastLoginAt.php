<?php

namespace App\Listeners;

// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Auth\Events\Login;
use Carbon\Carbon;

class UpdateLastLoginAt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // When the login event happens, update the user's timestamp
        $event->user->update([
            'last_login_at' => Carbon::now(),
        ]);
    }
}
