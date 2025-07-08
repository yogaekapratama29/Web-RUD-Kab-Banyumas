<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['respondent_id', 'question_id', 'jawaban'];


    public $timestamps = false;
}