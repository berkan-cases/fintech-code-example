<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CardCreateRequest;
use App\Http\Requests\CardDeleteRequest;
use App\Http\Requests\CardUpdateRequest;
use App\Http\Requests\CardViewAnyRequest;
use App\Http\Requests\CardViewRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;

class CardController extends Controller
{
    /**
     * List all active Cards.
     */
    public function index(CardViewAnyRequest $request): AnonymousResourceCollection
    {
        $cards = $request->user()
                               ->cards()
                               ->active()
                               ->get();

        return CardResource::collection($cards);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CardCreateRequest $request): CardResource
    {
        $card = $request->user()->cards()->create([
            'type'            => $request->input('type'),
            'number'          => rand(1000000000000000, 9999999999999999),
            'expiration_date' => Carbon::now()->addYear(),
        ]);

        return new CardResource($card);
    }

    /**
     * Display the specified resource.
     */
    public function show(CardViewRequest $request, Card $card): CardResource
    {
        return new CardResource($card);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardUpdateRequest $request, Card $card): CardResource
    {
        $card->update([
            'disabled_at' => $request->input('is_active') === true ? null : Carbon::now(),
        ]);

        return new CardResource($card);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CardDeleteRequest $request, Card $card): CardResource
    {
        $card->delete();

        return new CardResource($card);
    }
}
