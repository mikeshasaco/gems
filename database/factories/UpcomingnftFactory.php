<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UpcomingnftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_name' => $this->faker->company,
            'release_date' => $this->faker->date ,
            'release_time' => $this->faker->time,
            'Website' => $this->faker->url,
            'socialmedia' => '[{"media":"twitter","media_link":"http://twitter.com"},{"media":"discord","media_link":"http://discord.com"},{"media":"facebook","media_link":"http://facebook.com"}]',
            'briefdescription' => $this->faker->text,
            'network' => $this->faker->word,
            'verify' => 1,
            'images' => '["1641986867-1634915066449.png"]'
        ];
    }
}
