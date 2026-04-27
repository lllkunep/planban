<?php

namespace App\Notifications;

use App\Models\Card;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CardUpdatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Card $card, public string|array $texts, public ?User $associatedUser = null)
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if (!is_array($this->texts)){
            $this->texts = [$this->texts];
        }
        $associatedUserData = null;
        if ($this->associatedUser){
            $associatedUserData = [
                'id' => $this->associatedUser->id,
                'name' => $this->associatedUser->name,
                'email' => $this->associatedUser->email,
            ];
        }
        return [
            'type' => 'CardUpdated',
            'title' => 'Card updated',
            'associated_user' => $associatedUserData,
            'card' => [
                'id' => $this->card->id,
                'name' => $this->card->name,
                'board_id' => $this->card->column->board->id,
            ],
            'texts' => $this->texts,
        ];
    }
}
