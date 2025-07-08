<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
   protected $fillable = ['nama', 'usia', 'desa', 'latitude', 'longitude'];

}