<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use App\Enums\SurveyPeriodType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_time',
        'end_time',
        'status',
        'description',
        'type',
        'year',
        'created_by',
        'updated_by',
        'faculty_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => Status::class,
        'type' => SurveyPeriodType::class,
    ];

    // ------------------------ RELATIONS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function graduationCeremonies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(GraduationCeremony::class, 'survey_period_graduation')
            ->withTimestamps()
            ->using(SurveyPeriodGraduation::class);
    }

    public function students(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'survey_period_student')
            ->withPivot(['number_mail_send', 'code_verify'])
            ->withTimestamps()
            ->using(SurveyPeriodStudent::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//
    public function getPrimaryGraduationCeremonyAttribute()
    {
        return $this->graduationCeremonies->first();
    }

    //----------------------- SCOPES ----------------------------------//
}
