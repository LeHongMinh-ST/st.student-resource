<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClassType;
use App\Enums\Status;
use App\Enums\StudentRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GeneralClass extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'code',
        'status',
        'faculty_id',
        'training_industry_id',
        'teacher_id',
        'sub_teacher_id',
        'type',
        'admission_year_id'
    ];

    protected $table = 'classes';

    // ------------------------ RELATIONS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function admissionYear(): BelongsTo
    {
        return $this->belongsTo(AdmissionYear::class);
    }

    public function trainingIndustry(): BelongsTo
    {
        return $this->belongsTo(TrainingIndustry::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'class_students', 'class_id', 'student_id')
            ->withPivot(['status', 'start_date', 'end_date', 'role'])
            ->withTimestamps();
    }

    public function studentPresident()
    {
        return $this->hasOneThrough(Student::class, ClassStudent::class, 'class_id', 'id', 'id', 'student_id')
            ->where('class_students.role', StudentRole::President);
    }

    public function studentSecretary()
    {
        return $this->hasOneThrough(Student::class, ClassStudent::class, 'class_id', 'id', 'id', 'student_id')
            ->where('class_students.role', StudentRole::Secretary);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function subTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sub_teacher_id', 'id');
    }


    // ------------------------ CASTS -------------------------//
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'type' => ClassType::class,
        ];
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//
}
