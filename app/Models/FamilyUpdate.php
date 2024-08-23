<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FamilyRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyUpdate extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'relationship',
        'full_name',
        'job',
        'phone',
        'student_info_update_id',
    ];

    // ------------------------ CASTS -------------------------//

    protected $casts = [
        'relationship' => FamilyRelationship::class,
    ];

    // ------------------------ RELATIONS -------------------------//
    public function studentInfoUpdate(): BelongsTo
    {
        return $this->belongsTo(StudentInfoUpdate::class);
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//
}
