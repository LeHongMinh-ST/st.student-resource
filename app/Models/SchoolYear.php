<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'school_years';

    protected $fillable = ['start_year', 'end_year'];

    // ------------------------ RELATIONS -------------------------//
}
