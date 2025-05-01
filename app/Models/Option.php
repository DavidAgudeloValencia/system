<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'survey_options';
    protected $fillable = ['option', 'question_id'];

    // Relación con la pregunta
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    // Relación con las respuestas
    public function answers()
    {
        return $this->hasMany(Answer::class, 'option_id');
    }
}
