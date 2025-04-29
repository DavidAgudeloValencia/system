<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyAnswerFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_answers_by_date_range_and_survey()
    {
        $user1 = \App\Models\User::factory()->create();
        $user2 = \App\Models\User::factory()->create();

        $survey1 = \App\Models\Survey::factory()->create();
        $survey2 = \App\Models\Survey::factory()->create();

        $question1 = \App\Models\Question::factory()->create(['survey_id' => $survey1->id]);
        $question2 = \App\Models\Question::factory()->create(['survey_id' => $survey2->id]);

        $option1 = \App\Models\Option::factory()->create(['question_id' => $question1->id]);
        $option2 = \App\Models\Option::factory()->create(['question_id' => $question2->id]);

        // Crear respuestas en el rango de fechas
        \App\Models\Answer::factory()->create([
            'user_id' => $user1->id,
            'survey_id' => $survey1->id,
            'question_id' => $question1->id,
            'option_id' => $option1->id,
            'created_at' => now()->subDays(3),
        ]);

        \App\Models\Answer::factory()->create([
            'user_id' => $user2->id,
            'survey_id' => $survey2->id,
            'question_id' => $question2->id,
            'option_id' => $option2->id,
            'created_at' => now()->subDays(2),
        ]);

        // Simulate filtering by date and specific survey
        $answers = \App\Models\Answer::query()
            ->whereBetween('created_at', [now()->subDays(5), now()])
            ->where('survey_id', $survey1->id)
            ->with('user', 'survey', 'question', 'option')
            ->get()
            ->groupBy('user_id');

        $this->assertCount(1, $answers); // Must only include responses from `survey1`.
        $this->assertArrayHasKey($user1->id, $answers);
    }


    /** @test */
    public function it_can_list_surveys_answered_by_a_user()
    {
        $user = \App\Models\User::factory()->create();
        $survey1 = \App\Models\Survey::factory()->create();
        $survey2 = \App\Models\Survey::factory()->create();

        \App\Models\Answer::factory()->create(['user_id' => $user->id, 'survey_id' => $survey1->id]);
        \App\Models\Answer::factory()->create(['user_id' => $user->id, 'survey_id' => $survey2->id]);

        $surveys = \App\Models\Survey::whereHas('answers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $this->assertCount(2, $surveys); // The user must have answered two surveys
        $this->assertTrue($surveys->contains($survey1));
        $this->assertTrue($surveys->contains($survey2));
    }


    /** @test */
    public function it_can_get_user_survey_detail()
    {
        $user = \App\Models\User::factory()->create();
        $survey = \App\Models\Survey::factory()->create();
        $question1 = \App\Models\Question::factory()->create(['survey_id' => $survey->id]);
        $question2 = \App\Models\Question::factory()->create(['survey_id' => $survey->id]);

        $option1 = \App\Models\Option::factory()->create(['question_id' => $question1->id]);
        $option2 = \App\Models\Option::factory()->create(['question_id' => $question2->id]);

        \App\Models\Answer::factory()->create([
            'user_id' => $user->id,
            'survey_id' => $survey->id,
            'question_id' => $question1->id,
            'option_id' => $option1->id,
        ]);

        \App\Models\Answer::factory()->create([
            'user_id' => $user->id,
            'survey_id' => $survey->id,
            'question_id' => $question2->id,
            'option_id' => $option2->id,
        ]);

        $answers = \App\Models\Answer::where('user_id', $user->id)
            ->where('survey_id', $survey->id)
            ->get();

        $this->assertCount(2, $answers); // There must be two user responses in the survey
        $this->assertEquals($answers->first()->question_id, $question1->id);
        $this->assertEquals($answers->last()->question_id, $question2->id);
    }
}
