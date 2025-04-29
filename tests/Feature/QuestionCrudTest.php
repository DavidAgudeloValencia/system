<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Survey;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_question()
    {
        $survey = Survey::factory()->create();

        $questionData = [
            'title' => 'What is your favorite color?',
            'survey_id' => $survey->id,
        ];

        $question = Question::create($questionData);

        $this->assertInstanceOf(Question::class, $question);
        $this->assertEquals('What is your favorite color?', $question->title);
        $this->assertEquals($survey->id, $question->survey_id);
        $this->assertDatabaseHas('survey_questions', $questionData);
    }

    /** @test */
    public function it_can_retrieve_a_question()
    {
        $question = Question::factory()->create([
            'title' => 'What is your favorite animal?',
        ]);

        $retrievedQuestion = Question::find($question->id);

        $this->assertInstanceOf(Question::class, $retrievedQuestion);
        $this->assertEquals('What is your favorite animal?', $retrievedQuestion->title);
        $this->assertEquals($question->survey_id, $retrievedQuestion->survey_id);
    }

    /** @test */
    public function it_can_update_a_question()
    {
        $question = Question::factory()->create([
            'title' => 'What is your favorite movie?',
        ]);

        $question->update([
            'title' => 'Updated question about movies',
        ]);

        $this->assertEquals('Updated question about movies', $question->title);
        $this->assertDatabaseHas('survey_questions', [
            'title' => 'Updated question about movies',
        ]);
    }

    /** @test */
    public function it_cannot_update_a_question_with_answers()
    {
        $user = User::factory()->create(); // Create a user
        $survey = Survey::factory()->create(); // Create a survey
        $question = Question::factory()->create(['survey_id' => $survey->id]); // Create an associated question

        // Create an answer to the question
        Answer::factory()->create([
            'user_id' => $user->id,
            'survey_id' => $survey->id,
            'question_id' => $question->id,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se puede modificar la pregunta porque ya tiene respuestas.');

        $question->update([
            'title' => 'Updated Question Title',
        ]);
    }

    /** @test */
    public function it_can_delete_a_question()
    {
        $question = Question::factory()->create([
            'title' => 'What is your favorite hobby?',
        ]);

        $question->delete();

        $this->assertDatabaseMissing('survey_questions', [
            'title' => 'What is your favorite hobby?',
        ]);
    }
}
