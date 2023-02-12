<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Suppliers>
 */
class SuppliersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplier_name' => $this->faker->company(),
            'supplier_contact_name' => $this->faker->name(),
            'supplier_phone' => $this->faker->phoneNumber(),
            'supplier_email' => $this->faker->email(),
            'supplier_address' => $this->faker->address(),
            'city_id' => null,
        ];
    }
}