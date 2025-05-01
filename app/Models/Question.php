<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'survey_questions';
    protected $fillable = ['title', 'survey_id'];

    // Relación con la encuesta
    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    // Relación con las opciones
    public function options()
    {
        return $this->hasMany(Option::class, 'question_id');
    }

    // Relación con las respuestas
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    // Sobrescribir el método de actualización
    public function update(array $attributes = [], array $options = [])
    {
        if ($this->answers()->exists()) {
            throw new \Exception('No se puede modificar la pregunta porque ya tiene respuestas.');
        }

        return parent::update($attributes, $options);
    }
}
