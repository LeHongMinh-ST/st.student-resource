<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StudentInfoUpdateStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApproveStudentUpdate extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'approveable_type',
        'approveable_id',
        'status',
        'note',
        'student_info_update_id',
    ];


    // ------------------------ RELATIONS -------------------------//
    public function approveStudentUpdateable(): MorphTo
    {
        return $this->morphTo();
    }

    public function studentInfoUpdate(): BelongsTo
    {
        return $this->belongsTo(StudentInfoUpdate::class);
    }

    // ------------------------ CASTS -------------------------//
    protected function casts(): array
    {
        return [
            'status' => StudentInfoUpdateStatus::class,
        ];
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//
}
