<?php

namespace Database\Factories;

use App\Models\IntraPerson;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Attributes\UseModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
#[UseModel(User::class)]
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $intraPerson = IntraPerson::firstOrCreate([
            'name' => "Leonardo",
            'cpf' => "12345678901",
            'birthdate' => "1990-01-01",
            'email' => "any@gmail.com",
            'phone' => "12345678901"
        ]);
        $user = User::create(
            [
                'intra_person_id' => $intraPerson->id,
                'login' => "Leonardo",
                'password' => "admin1234",
            ]
        );
        return [];
    }
}
