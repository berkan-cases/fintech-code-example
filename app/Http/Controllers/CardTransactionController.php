<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CardTransactionCreateRequest;
use App\Http\Requests\CardTransactionViewAnyRequest;
use App\Http\Requests\CardTransactionViewRequest;
use App\Http\Resources\CardTransactionResource;
use App\Jobs\ProcessCardTransactionJob;
use App\Models\Card;
use App\Models\CardTransaction;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CardTransactionController extends Controller
{
    /**
     * Display a listing of the CardTransaction for the given card.
     */
    public function index(CardTransactionViewAnyRequest $request, Card $card): AnonymousResourceCollection
    {
        return CardTransactionResource::collection($card->transactions);
    }

    /**
     * Store a newly created CardTransaction in storage.
     */
    public function store(CardTransactionCreateRequest $request, Card $card): CardTransactionResource
    {
        /** @var \App\Models\CardTransaction $newCardTransaction */
        $newCardTransaction = $card->transactions()->create($request->validated());

        ProcessCardTransactionJob::dispatchAfterResponse($newCardTransaction->id);

        return new CardTransactionResource($newCardTransaction);
    }

    /**
     * Display the specified CardTransaction.
     */
    public function show(CardTransactionViewRequest $request, CardTransaction $cardTransaction): CardTransactionResource
    {
        return new CardTransactionResource($cardTransaction);
    }
}
