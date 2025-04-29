<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\User;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnswerCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_answer()
    {
        // Create related data
        $user = User::factory()->create();
        $survey = Survey::factory()->create();
        $question = Question::factory()->create(['survey_id' => $survey->id]);
        $option = Option::factory()->create(['question_id' => $question->id]);

        // Response data
        $answerData = [
            'user_id' => $user->id,
            'survey_id' => $survey->id,
            'question_id' => $question->id,
            'option_id' => $option->id,
        ];

        // Create an answer
        $answer = Answer::create($answerData);

        // Verificar
        $this->assertInstanceOf(Answer::class, $answer);
        $this->assertEquals($user->id, $answer->user_id);
        $this->assertEquals($survey->id, $answer->survey_id);
        $this->assertEquals($question->id, $answer->question_id);
        $this->assertEquals($option->id, $answer->option_id);
        $this->assertDatabaseHas('survey_answers', $answerData);
    }

    /** @test */
    public function it_can_retrieve_an_answer()
    {
        // Create a response with related data
        $answer = Answer::factory()->create();

        $retrievedAnswer = Answer::find($answer->id);

        // Check
        $this->assertInstanceOf(Answer::class, $retrievedAnswer);
        $this->assertEquals($answer->user_id, $retrievedAnswer->user_id);
        $this->assertEquals($answer->survey_id, $retrievedAnswer->survey_id);
        $this->assertEquals($answer->question_id, $retrievedAnswer->question_id);
        $this->assertEquals($answer->option_id, $retrievedAnswer->option_id);
    }

    /** @test */
    public function it_can_update_an_answer()
    {
        // Create an initial response
        $answer = Answer::factory()->create();

        // Update the answer
        $answer->update([
            'option_id' => null, // Change selected option
        ]);

        // Check
        $this->assertNull($answer->option_id);
        $this->assertDatabaseHas('survey_answers', [
            'id' => $answer->id,
            'option_id' => null,
        ]);
    }

    /** @test */
    public function it_can_delete_an_answer()
    {
        // Create an initial response
        $answer = Answer::factory()->create();

        // Delete the answer
        $answer->delete();

        // Check
        $this->assertDatabaseMissing('survey_answers', [
            'id' => $answer->id,
        ]);
    }
}
