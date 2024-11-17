<?php

namespace Database\Factories;

use App\Models\BookCopy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'book_copy_id' => BookCopy::inRandomOrder()->first()->id,
            'loan_date' => $this->faker->dateTimeThisYear,
            'return_due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'returned_date' => $this->faker->optional()->dateTimeBetween('now', '+2 months'),
            'late_fee' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
