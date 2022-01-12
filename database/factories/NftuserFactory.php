<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NftuserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_name' => $this->faker->userName,
            'email' => $this->faker->email,
            'opensea_link' => $this->faker->url,
            'website' => $this->faker->url,
            'twitter_link' => $this->faker->url,
            'discord_link' => $this->faker->url,
        ];
    }
}
