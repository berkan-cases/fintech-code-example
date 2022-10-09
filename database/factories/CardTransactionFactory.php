<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CurrencyType;
use App\Models\Card;
use App\Models\CardTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CardTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'card_id' => Card::factory(),
            'amount'         => $this->faker->numberBetween(100, 999) * 10,
            'currency_code'  => $this->faker->randomElement(CurrencyType::cases()),
        ];
    }
}
