<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Topic;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'user_id'  => function () {
                return \App\Models\User::factory()->create()->id;
            }
        ];
    }
}
