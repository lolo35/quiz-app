<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmittedAnswers extends Model
{
    use HasFactory;
    protected $table = "submitted_answers";
    protected $fillable = ['user_id', 'quiz_id', 'question_id', 'answer_id'];
    protected $hidden = [];
}
