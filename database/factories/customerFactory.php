<?php

namespace Database\Factories;

use App\Customers;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomersFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 2,
            'last_name' => $this->faker->lastName,
            'first_name' => $this->faker->firstName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'country' => $this->faker->country,
            'emmergency_contact' => $this->faker->e164PhoneNumber,
            'departure_date' => $this->faker->date,
            'created_at' => now()
        ];
    }
}
