<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Card;
use App\Notifications\CardDeletedNotification;

class CardObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public $afterCommit = true;

    /**
     * Handle the Card "deleted" event.
     */
    public function deleted(Card $card): void
    {
        $card->user->notify(new CardDeletedNotification($card));
    }
}
