<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CurrencyType;
use App\Enums\PaymentStatus;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $amount = $this->faker->numberBetween(100, 500);

        return [
            'user_id' => User::factory(),
            'amount' => $amount,
            'terms' => $this->faker->numberBetween(3, 8),
            'outstanding_amount' => $amount,
            'currency_code' => CurrencyType::TRY,
            'status' => PaymentStatus::DUE,
            'processed_at' => $this->faker->date(),
        ];
    }

    /**
     * Set customer for Loan.
     */
    public function forCustomer(User $customer): Factory
    {
        return $this->state(fn(): array => [
            'user_id' => $customer->id,
        ]);
    }
}
