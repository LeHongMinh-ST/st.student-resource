<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassAssign extends Model
{
    use HasFactory;

    protected $table = 'class_assigns';
    protected $fillable = [
      'class_id',
      'teacher_id',
      'sub_teacher_id',
      'number',
      'year'
    ];
}
