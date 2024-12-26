<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semester extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'semester',
        'school_year_id'
    ];

    // ------------------------ RELATIONS -------------------------//

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    //----------------------- SCOPES ----------------------------------//
}
