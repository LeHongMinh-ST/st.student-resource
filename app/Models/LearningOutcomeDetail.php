<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningOutcomeDetail extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'order',
        'subject_code',
        'subject_name',
        'credits',
        'percent_test',
        'percent_exam',
        'diligence_point',
        'progress_point',
        'exam_point',
        'total_point_number',
        'total_point_string',
        'learning_outcomes_id',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function learningOutcome(): BelongsTo
    {
        return $this->belongsTo(LearningOutcome::class);
    }

    // ------------------------ CASTS -------------------------//


    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//
}
