<?php

namespace Database\Factories;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Option>
 */
class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Option::class;

    public function definition(): array
    {
        return [
            'option' => $this->faker->sentence(3), // Option text
            'question_id' => Question::factory(),  // Relation to the question
        ];
    }
}
