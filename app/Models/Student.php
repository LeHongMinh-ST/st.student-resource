<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClassType;
use App\Enums\Status;
use App\Enums\StudentStatus;
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


    // ---------------------- ACCESSORS AND MUTATORS --------------------//
    public function getFullNameAttribute(): string
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function getAvatarPathAttribute(): string
    {
        return $this->info->thumbnail ? asset(Storage::url($this->info->thumbnail)) : asset('assets/images/avatar_default.png');
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
