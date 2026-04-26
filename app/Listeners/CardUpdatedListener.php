<?php

namespace App\Listeners;

use App\Events\CardUpdated;
use App\Models\History;
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
        $texts = $event->texts;
        if (!is_array($texts)){
            $texts = [$texts];
        }
        foreach ($texts as $text){
            History::create([
                'card_id' => $event->card->id,
                'user_id' => $event->user->id,
                'text' => $text
            ]);
        }
    }
}
