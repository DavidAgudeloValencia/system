<?php

namespace Database\Factories;

use App\Models\Survey;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey>
 */
class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Survey::class;


    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3), // Survey name
            'state' => $this->faker->boolean,   // Status: active/inactive
        ];
    }
}
