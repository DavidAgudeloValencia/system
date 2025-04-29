<?php

namespace Tests\Feature;

use App\Models\Survey;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurveyCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_survey()
    {
        $surveyData = [
            'name' => 'Customer Satisfaction Survey',
            'state' => true,
        ];

        $survey = Survey::create($surveyData);

        $this->assertInstanceOf(Survey::class, $survey);
        $this->assertEquals('Customer Satisfaction Survey', $survey->name);
        $this->assertTrue($survey->state);
        $this->assertDatabaseHas('survey_surveys', $surveyData);
    }

    /** @test */
    public function it_can_retrieve_a_survey()
    {
        $survey = Survey::factory()->create([
            'name' => 'Product Feedback Survey',
            'state' => false,
        ]);

        $retrievedSurvey = Survey::find($survey->id);

        $this->assertInstanceOf(Survey::class, $retrievedSurvey);
        $this->assertEquals('Product Feedback Survey', $retrievedSurvey->name);
        $this->assertFalse($retrievedSurvey->state);
    }

    /** @test */
    public function it_can_update_a_survey()
    {
        $survey = Survey::factory()->create([
            'name' => 'Employee Engagement Survey',
            'state' => true,
        ]);

        $survey->update([
            'name' => 'Updated Engagement Survey',
            'state' => false,
        ]);

        $this->assertEquals('Updated Engagement Survey', $survey->name);
        $this->assertFalse($survey->state);
        $this->assertDatabaseHas('survey_surveys', [
            'name' => 'Updated Engagement Survey',
            'state' => false,
        ]);
    }

    /** @test */
    public function it_can_delete_a_survey()
    {
        $survey = Survey::factory()->create([
            'name' => 'Marketing Research Survey',
            'state' => true,
        ]);

        $survey->delete();

        $this->assertDatabaseMissing('survey_surveys', [
            'name' => 'Marketing Research Survey',
        ]);
    }

    /** @test */
    public function it_cannot_update_a_survey_with_answers()
    {
        // Create user
        $user = \App\Models\User::factory()->create();

        // Create survey
        $survey = \App\Models\Survey::factory()->create();

        // Create a question associated with the survey
        $question = \App\Models\Question::factory()->create(['survey_id' => $survey->id]);

        // Create option associated to the question (optional, if used in answers)
        $option = \App\Models\Option::factory()->create(['question_id' => $question->id]);

        // Create response associated with user, survey, question and option
        \App\Models\Answer::factory()->create([
            'user_id' => $user->id,
            'survey_id' => $survey->id,
            'question_id' => $question->id, // Securing the related field
            'option_id' => $option->id,    // Optional according to your rules
        ]);

        // Attempt to update the survey
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No se puede modificar la encuesta porque ya tiene respuestas.');

        $survey->update([
            'name' => 'Updated Survey Name',
        ]);
    }
}
