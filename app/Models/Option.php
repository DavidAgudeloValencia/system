<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'survey_options';
    protected $fillable = ['option', 'question_id'];

    // Relation to the question
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    // Relationship with the answers
    public function answers()
    {
        return $this->hasMany(Answer::class, 'option_id');
    }
}
