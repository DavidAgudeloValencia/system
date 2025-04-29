<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Option;
use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

     protected $model = Answer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),          // Relationship with the user
            'survey_id' => Survey::factory(),      // Relation to the survey
            'question_id' => Question::factory(),  // Relation to the question
            'option_id' => Option::factory(),      // Relation to the selected option
        ];
    }
}
