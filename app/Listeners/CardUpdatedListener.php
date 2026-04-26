<?php

namespace App\Listeners;

use App\Events\CardUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CardUpdatedListener
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
    public function handle(CardUpdated $event): void
    {
        History::create([
            'card_id' => $event->card->id,
            'user_id' => $event->user->id,
            'text' => $event->text
        ]);
    }
}
