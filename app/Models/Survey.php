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
        'state' => 'boolean', // Convierte automáticamente 0 y 1 en falso y verdadero
    ];

    // Relación con las preguntas
    public function questions()
    {
        return $this->hasMany(Question::class, 'survey_id');
    }

    // Relación con las respuestas
    public function answers()
    {
        return $this->hasMany(Answer::class, 'survey_id');
    }

    // Sobrescribir el método de actualización
    public function update(array $attributes = [], array $options = [])
    {
        if ($this->answers()->exists()) {
            throw new \Exception('No se puede modificar la encuesta porque ya tiene respuestas.');
        }

        return parent::update($attributes, $options);
    }
}
