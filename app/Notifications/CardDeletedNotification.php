<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Client\Response;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class CardDeletedNotification extends Notification
{
    use Queueable;

    public Card $card;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     */
    public function toMail($notifiable): Response
    {
        return Http::post('http://send-example.com', [
            'card_id' => $this->card->id,
            'message' => "Your Card #{$this->card->number} is deleted.",
        ]);
    }
}
