<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartedQuizes extends Model
{
    use HasFactory;
    protected $table = "started_quizes";
    protected $fillable = ['started_by', 'finished'];
    protected $hidden = [];
}
