<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quit extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'semester_id',
        'faculty_id',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    // ------------------------ CASTS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//
}
