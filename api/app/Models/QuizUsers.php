<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizUsers extends Model
{
    use HasFactory;
    protected $table = "quiz_users";
    protected $fillable = ['name', 'surname'];
    protected $hidden = [];
}
