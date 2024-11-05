<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SurveyPeriodGraduation extends Pivot
{
    use HasFactory;

    public $timestamps = true;

    protected $table = 'survey_period_graduation';

    protected $fillable = [
        'graduation_ceremony_id',
        'survey_period_id',
        'number_mail_send',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function graduationCeremony(): BelongsTo
    {
        return $this->belongsTo(GraduationCeremony::class);
    }

    public function surveyPeriod(): BelongsTo
    {
        return $this->belongsTo(SurveyPeriod::class);
    }

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    //----------------------- SCOPES ----------------------------------//
}
