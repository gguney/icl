<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TeamFactory extends Factory
{
    const SUFFIXES = ['United', 'Club', 'Athletic', 'Rangers', 'Town', 'Villa'];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->city . ' ' . $this->faker->randomElement(self::SUFFIXES),
            'power' => rand(1, 100),
        ];
    }
}
