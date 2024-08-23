<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'start_year',
        'end_year',
        'semester',
    ];

    // ------------------------ RELATIONS -------------------------//

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    //----------------------- SCOPES ----------------------------------//
}
