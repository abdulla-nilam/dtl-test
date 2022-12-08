<?php

namespace App\Providers;

use App\Models\Changelog;
use App\Providers\ChangelogEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreChangelog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ChangelogEvent $event
     * @return void
     */
    public function handle(ChangelogEvent $event)
    {
        $payload = $event->log;

        $changelog = new Changelog();
        $changelog->action = $payload['action'];
        $changelog->new_payload = $payload['new_payload'];
        $changelog->old_payload = $payload['old_payload'] ?? [];
        $changelog->save();
    }
}
