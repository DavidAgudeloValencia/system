<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'survey_answers';
    protected $fillable = ['user_id', 'survey_id', 'question_id','option_id'];

    // Relationship with the user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation to the survey
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    // Relation to the question
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    // Relation to the selected option
    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
    }
}
