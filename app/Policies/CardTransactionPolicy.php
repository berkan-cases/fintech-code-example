<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Card;
use App\Models\CardTransaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Card $card): bool
    {
        return $user->is($card->user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CardTransaction $cardTransaction): bool
    {
        return $user->is($cardTransaction->card->user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Card $card): bool
    {
        return $user->is($card->user);
    }
}
