<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CurrencyType;
use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CardTransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected User $another_customer;

    protected Card $card;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create();

        $this->another_customer = User::factory()->create();

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);

        Passport::actingAs($this->customer);

        $this->card = Card::factory()->create([
            'user_id' => $this->customer->id,
        ]);
    }

    /**
     * @test
     */
    public function a_customer_can_create_a_card_id_transaction(): void
    {
        // 1. Arrange 🏗
        $amount = 1000;

        $currency = CurrencyType::TRY->value;

        Http::fake();

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.card-transactions.store', [
            'card' => $this->card->id,
        ]), [
            'amount'        => $amount,
            'currency_code' => $currency,
        ]);

        // 3. Assert ✅
        $request->assertCreated();
    }

    /**
     * @test
     */
    public function a_customer_can_not_create_a_card_id_transaction_for_a_card_id_of_another_customer(): void
    {
        // 1. Arrange 🏗
        $amount = 1000;

        $currency = CurrencyType::TRY->value;

        Http::fake();

        // 2. Act 🏋🏻‍
        $request = $this->actingAs($this->another_customer)->post(route('cards.card-transactions.store', [
            'card' => $this->card->id,
        ]), [
            'amount'        => $amount,
            'currency_code' => $currency,
        ]);

        // 3. Assert ✅
        $request->assertForbidden();
    }

    /**
     * @test
     */
    public function a_customer_can_see_a_card_id_transaction(): void
    {
        // 1. Arrange 🏗
        $amount = 1000;

        $currency = CurrencyType::TRY->value;

        Http::fake();

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.card-transactions.store', [
            'card' => $this->card->id,
        ]), [
            'amount'        => $amount,
            'currency_code' => $currency,
        ]);

        $list_request = $this->get(route('cards.card-transactions.index', [
            'card' => $this->card->id,
        ]));

        // 3. Assert ✅
        $request->assertCreated();

        $list_request->assertOk();
    }

    /**
     * @test
     */
    public function a_customer_can_not_see_a_card_id_transaction_for_a_card_id_of_another_customer(): void
    {
        // 1. Arrange 🏗
        $amount = 1000;

        $currency = CurrencyType::TRY->value;

        Http::fake();

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.card-transactions.store', [
            'card' => $this->card->id,
        ]), [
            'amount'        => $amount,
            'currency_code' => $currency,
        ]);

        $list_request = $this->actingAs($this->another_customer)->get(route('cards.card-transactions.index', [
            'card' => $this->card->id,
        ]));

        // 3. Assert ✅
        $request->assertCreated();

        $list_request->assertForbidden();

        $this->assertEmpty($list_request->json('data'));
    }

    /**
     * @test
     */
    public function a_customer_can_list_card_id_transactions(): void
    {
        // 1. Arrange 🏗
        $amount = 1000;

        $currency = CurrencyType::TRY->value;

        Http::fake();

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.card-transactions.store', [
            'card' => $this->card->id,
        ]), [
            'amount'        => $amount,
            'currency_code' => $currency,
        ]);

        $list_request = $this->get(route('cards.card-transactions.index', [
            'card' => $this->card->id,
        ]));

        // 3. Assert ✅
        $request->assertCreated();

        $list_request->assertOk();
    }

    /**
     * @test
     */
    public function a_customer_can_not_list_card_id_transactions_for_a_card_id_of_another_customer(): void
    {
        // 1. Arrange 🏗
        $amount = 1000;

        $currency = CurrencyType::TRY->value;

        Http::fake();

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.card-transactions.store', [
            'card' => $this->card->id,
        ]), [
            'amount'        => $amount,
            'currency_code' => $currency,
        ]);


        $list_request = $this->get(route('cards.card-transactions.index', [
            'card' => $this->card->id,
        ]));

        $another_user_list_request = $this->actingAs($this->another_customer)->get(route('cards.card-transactions.index', [
            'card' => $this->card->id,
        ]));

        // 3. Assert ✅
        $request->assertCreated();

        $list_request->assertOk();

        $another_user_list_request->assertForbidden();
    }

    // THE MORE TESTS THE MORE POINTS 🏆
}
