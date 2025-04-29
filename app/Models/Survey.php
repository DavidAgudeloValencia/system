<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'survey_surveys';
    protected $fillable = ['name', 'state'];

    protected $casts = [
        'state' => 'boolean', // Automatically converts 0 and 1 to false and true
    ];

    // Relationship to questions
    public function questions()
    {
        return $this->hasMany(Question::class, 'survey_id');
    }

    // Relationship with the answers
    public function answers()
    {
        return $this->hasMany(Answer::class, 'survey_id');
    }

    // Overwrite the update method
    public function update(array $attributes = [], array $options = [])
    {
        if ($this->answers()->exists()) {
            throw new \Exception('No se puede modificar la encuesta porque ya tiene respuestas.');
        }

        return parent::update($attributes, $options);
    }
}
