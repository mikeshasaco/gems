<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NftdetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 2,
            'nft_name' => $this->faker->name,
            'nft_link' => $this->faker->url,
            'image' => '["1641986867-1634915066449.png"]',
            'verify' => 1,
            'popularity' => 20,
            'community' => 25,
            'originality' => 30,
            'total' => 105,
            'utility' => 'Reward'
        ];
    }
}
