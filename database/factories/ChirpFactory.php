<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChirpFactory extends Factory
{
    public function definition(): array
    {
        return [
            'message' => fake()->sentence(6),
            'user_id' => User::factory(), // Automatycznie wygeneruje i przypisze autora wpisu
        ];
    }
}