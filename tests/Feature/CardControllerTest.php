<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected User $another_customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create();

        $this->another_customer = User::factory()->create();

        $this->withHeaders([
            'Accept' => 'application/json',
        ]);

        Passport::actingAs($this->customer);
    }

    /**
     * @test
     */
    public function a_customer_can_create_a_card_id(): void
    {
        // 1. Arrange 🏗
        $type = Card::factory()->make()->getAttribute('type');

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.store'), [
            'type' => $type,
        ]);

        // 3. Assert ✅
        $request->assertCreated();

        $request->assertJsonStructure([
            'data' => [
                'id',
                'number',
                'type',
                'expiration_date',
                'is_active',
            ],
        ]);

        $resource = $request->collect()->first();

        $this->assertDatabaseHas(Card::class, [
            'type' => $type,
            'user_id' => $this->customer->id,
            'number' => $resource['number'],
            'expiration_date' => Carbon::parse($resource['expiration_date'])->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @test
     */
    public function a_customer_can_not_create_an_invalid_card_id(): void
    {
        // 1. Arrange 🏗
        $type = 'UNKNOWN';

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.store'), [
            'type' => $type,
        ]);

        // 3. Assert ✅
        $request->assertInvalid([
            'type',
        ]);

        $request->assertStatus(422);
    }

    /**
     * @test
     */
    public function a_customer_can_see_a_card_id(): void
    {
        // 1. Arrange 🏗
        $type = Card::factory()->make()->getAttribute('type');

        $params = [];

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.store'), [
            'type' => $type,
        ]);

        $list_request = $this->get(route('cards.index', $params));

        // 3. Assert ✅
        $request->assertCreated();

        $list_request->assertOk();
    }

    /**
     * @test
     */
    public function a_customer_can_not_see_a_card_id_of_another_customer(): void
    {
        // 1. Arrange 🏗
        $type = Card::factory()->make()->getAttribute('type');

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.store'), [
            'type' => $type,
        ]);

        $customer_card_id = $request->json('data.id');

        Passport::actingAs($this->another_customer);

        $list_another_request = $this->get(route('cards.index'));

        $another_customer_card_id = $list_another_request->json('data.id');

        // 3. Assert ✅
        $request->assertCreated();

        $list_another_request->assertOk();

        $this->assertNotEquals($customer_card_id, $another_customer_card_id);
    }

    /**
     * @test
     */
    public function a_customer_can_list_card_ids(): void
    {
        // 1. Arrange 🏗
        $params = [];

        // 2. Act 🏋🏻‍
        $request = $this->get(route('cards.index', $params));

        // 3. Assert ✅
        $request->assertOk();
    }

    /**
     * @test
     */
    public function a_customer_can_activate_the_card_id(): void
    {
        // 1. Arrange 🏗
        $type = Card::factory()->make()->getAttribute('type');

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.store'), [
            'type' => $type,
        ]);

        $id = $request->json('data.id');

        $disable_request = $this->put(route('cards.update', ['card' => $id]), [
            'is_active' => false,
        ]);

        $active_request = $this->put(route('cards.update', ['card' => $id]), [
            'is_active' => true,
        ]);

        // 3. Assert ✅
        $this->assertEquals(false, $disable_request->json('data.is_active'));

        $this->assertEquals(true, $active_request->json('data.is_active'));
    }

    /**
     * @test
     */
    public function a_customer_can_deactivate_the_card_id(): void
    {
        // 1. Arrange 🏗
        $type = Card::factory()->make()->getAttribute('type');

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.store'), [
            'type' => $type,
        ]);

        $id = $request->json('data.id');

        $disable_request = $this->put(route('cards.update', ['card' => $id]), [
            'is_active' => false,
        ]);

        // 3. Assert ✅
        $this->assertEquals(false, $disable_request->json('data.is_active'));
    }

    /**
     * @test
     */
    public function a_customer_can_delete_a_card_id(): void
    {
        // 1. Arrange 🏗
        $type = Card::factory()->make()->getAttribute('type');

        Mail::fake();

        Http::fake();

        Notification::fake();

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.store'), [
            'type' => $type,
        ]);

        $id = $request->json('data.id');

        $delete_request = $this->delete(route('cards.destroy', ['card' => $id]));

        // 3. Assert ✅
        $request->assertCreated();

        $delete_request->assertOk();
    }

    // THE MORE TESTS THE MORE POINTS 🏆

    /**
     * @test
     */
    public function a_customer_can_not_delete_card_id_of_another_customer(): void
    {
        // 1. Arrange 🏗
        $type = Card::factory()->make()->getAttribute('type');

        // 2. Act 🏋🏻‍
        $request = $this->post(route('cards.store'), [
            'type' => $type,
        ]);

        $id = $request->json('data.id');

        Passport::actingAs($this->another_customer);

        $delete_request = $this->delete(route('cards.destroy', ['card' => $id]));

        // 3. Assert ✅
        $request->assertCreated();

        $delete_request->assertForbidden();
    }
}
