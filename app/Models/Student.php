<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClassType;
use App\Enums\Status;
use App\Enums\StudentInfoUpdateStatus;
use App\Enums\StudentStatus;
use App\Enums\WarningStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Student extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'code',
        'password',
        'faculty_id',
        'status',
        'admission_year_id',
        'training_industry_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function trainingIndustry(): BelongsTo
    {
        return $this->belongsTo(TrainingIndustry::class);
    }

    public function info(): HasOne
    {
        return $this->hasOne(StudentInfo::class);
    }

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    public function generalClass(): BelongsToMany
    {
        return $this->belongsToMany(GeneralClass::class, 'class_students', 'student_id', 'class_id')
            ->withPivot(['status', 'start_date', 'end_date', 'role'])
            ->withTimestamps()->using(ClassStudent::class);
    }

    public function currentClass(): HasOneThrough
    {
        return $this->hasOneThrough(GeneralClass::class, ClassStudent::class, 'student_id', 'id', 'id', 'class_id')
            ->whereIn('classes.type', [ClassType::Basic, ClassType::Major])
            ->where('class_students.status', Status::Enable)
            ->select('classes.*', 'class_students.role as role');
    }

    public function reflects(): HasMany
    {
        return $this->hasMany(Reflect::class);
    }

    public function admissionYear(): BelongsTo
    {
        return $this->belongsTo(AdmissionYear::class);
    }

    public function excelImportFileRecord(): MorphOne
    {
        return $this->morphOne(ExcelImportFileRecord::class, 'tableable', 'table_type', 'table_id');
    }

    public function learningOutcomes(): HasMany
    {
        return $this->hasMany(LearningOutcome::class)->with(['detail']);
    }

    public function graduationCeremonies(): BelongsToMany
    {
        return $this->belongsToMany(GraduationCeremony::class, 'graduation_ceremony_students', 'student_id', 'graduation_ceremony_id')
            ->withPivot(['gpa', 'rank', 'email'])->withTimestamps()
            ->using(GraduationCeremonyStudent::class);
    }

    public function currentGraduationCeremony(): HasOneThrough
    {
        return $this->hasOneThrough(GraduationCeremony::class, GraduationCeremonyStudent::class, 'student_id', 'id', 'id', 'graduation_ceremony_id')
            ->select('graduation_ceremonies.*', 'graduation_ceremony_students.gpa', 'graduation_ceremony_students.rank', 'graduation_ceremony_students.email');
    }

    public function surveyPeriods(): BelongsToMany
    {
        return $this->belongsToMany(SurveyPeriod::class, 'survey_period_student', 'student_id', 'survey_period_id')
            ->withPivot(['number_mail_send', 'code_verify'])
            ->withTimestamps()->using(SurveyPeriodStudent::class);
    }

    public function activeResponseSurvey(): HasOne|\Illuminate\Database\Eloquent\Builder
    {
        return $this->hasOne(EmploymentSurveyResponse::class)->whereHas('surveyPeriod', function ($query): void {
            $query->where('status', Status::Enable);
        });
    }

    public function currentSurvey(): HasOneThrough
    {
        return $this->hasOneThrough(SurveyPeriod::class, SurveyPeriodStudent::class, 'student_id', 'id', 'id', 'survey_period_id')
            ->where('survey_periods.status', Status::Enable)
            ->select('survey_periods.*', 'survey_period_student.number_mail_send', 'survey_period_student.updated_at as send_mail_updated_at');
    }

    public function employmentSurveyResponses(): HasMany
    {
        return $this->hasMany(EmploymentSurveyResponse::class, 'student_id');
    }

    public function warnings(): BelongsToMany
    {
        return $this->belongsToMany(Warning::class, 'student_warnings');
    }


    public function studentInfoUpdate(): HasMany
    {
        return $this->hasMany(StudentInfoUpdate::class);
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//
    public function getFullNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function getAvatarPathAttribute(): string
    {
        return $this->info->thumbnail ? asset(Storage::url($this->info->thumbnail)) : asset('assets/images/avatar_default.png');
    }

    public function getWarningStatusAttribute(): WarningStatus
    {
        $latestWarningIds = Warning::orderBy('created_at', 'desc')
            ->take(2)
            ->pluck('id')
            ->toArray();

        if (! $latestWarningIds) {
            return WarningStatus::NoWarning;
        }

        $warningCount = $this->warnings()
            ->whereIn('warnings.id', $latestWarningIds)
            ->count();

        if ($warningCount >= 2) {
            return WarningStatus::AtRisk;
        }
        if (1 === $warningCount) {
            return WarningStatus::UnderObservation;
        }

        return WarningStatus::NoWarning;
    }

    public function getHasRequestUpdateAttribute(): bool
    {
        $count = $this->studentInfoUpdate()->where('status', StudentInfoUpdateStatus::Pending)
            ->orWhere('status', StudentInfoUpdateStatus::ClassOfficerApproved)
            ->orWhere('status', StudentInfoUpdateStatus::TeacherApproved)->count();
        return $count > 0;
    }

    //----------------------- SCOPES ----------------------------------//

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    // ------------------------ CASTS -------------------------//
    protected function casts(): array
    {
        return [
            'status' => StudentStatus::class,
            'password' => 'hashed',
        ];
    }
}
