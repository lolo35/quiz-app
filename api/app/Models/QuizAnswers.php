<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswers extends Model
{
    use HasFactory;
    protected $table = "quiz_answers";
    protected $fillable = ['question_id', 'answer', 'correct'];
    protected $hidden = [];
}
