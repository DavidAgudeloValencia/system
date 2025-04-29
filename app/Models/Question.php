<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'survey_questions';
    protected $fillable = ['title', 'survey_id'];

    // Relation to the survey
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    // Relation to options
    public function options()
    {
        return $this->hasMany(Option::class, 'question_id');
    }

    // Relationship with the answers
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    // Overwrite the update method
    public function update(array $attributes = [], array $options = [])
    {
        if ($this->answers()->exists()) {
            throw new \Exception('No se puede modificar la pregunta porque ya tiene respuestas.');
        }

        return parent::update($attributes, $options);
    }
}
