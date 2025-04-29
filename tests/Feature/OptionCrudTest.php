<?php

namespace Tests\Feature;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptionCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_option()
    {
        $question = \App\Models\Question::factory()->create();

        $optionData = [
            'option' => 'Blue',
            'question_id' => $question->id,
        ];

        $option = Option::create($optionData);

        $this->assertInstanceOf(Option::class, $option);
        $this->assertEquals('Blue', $option->option);
        $this->assertEquals($question->id, $option->question_id);
        $this->assertDatabaseHas('survey_options', $optionData);
    }

    /** @test */
    public function it_can_retrieve_an_option()
    {
        $option = Option::factory()->create([
            'option' => 'Green',
        ]);

        $retrievedOption = Option::find($option->id);

        $this->assertInstanceOf(Option::class, $retrievedOption);
        $this->assertEquals('Green', $retrievedOption->option);
        $this->assertEquals($option->question_id, $retrievedOption->question_id);
    }

    /** @test */
    public function it_can_update_an_option()
    {
        $option = Option::factory()->create([
            'option' => 'Red',
        ]);

        $option->update([
            'option' => 'Updated Red',
        ]);

        $this->assertEquals('Updated Red', $option->option);
        $this->assertDatabaseHas('survey_options', [
            'option' => 'Updated Red',
        ]);
    }

    /** @test */
    public function it_can_delete_an_option()
    {
        $option = Option::factory()->create([
            'option' => 'Yellow',
        ]);

        $option->delete();

        $this->assertDatabaseMissing('survey_options', [
            'option' => 'Yellow',
        ]);
    }
}
