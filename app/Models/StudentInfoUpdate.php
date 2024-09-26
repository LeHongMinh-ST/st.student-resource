<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SocialPolicyObject;
use App\Enums\StudentInfoUpdateStatus;
use App\Enums\TrainingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentInfoUpdate extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'person_email',
        'gender',
        'permanent_residence',
        'dob',
        'pob',
        'countryside',
        'address',
        'training_type',
        'phone',
        'nationality',
        'citizen_identification',
        'ethnic',
        'religion',
        'thumbnail',
        'social_policy_object',
        'note',
        'student_id',
        'status',
    ];

    public static function boot(): void
    {
        self::deleting(function (StudentInfoUpdate $studentInfoUpdate): void {
            $studentInfoUpdate->families()->delete();
        });
    }

    // ------------------------ RELATIONS -------------------------//
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function approveStudent(): HasMany
    {
        return $this->hasMany(ApproveStudentUpdate::class);
    }

    public function families(): HasMany
    {
        return $this->hasMany(FamilyUpdate::class);
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    //----------------------- SCOPES ----------------------------------//

    public function getRouteKeyName(): string
    {
        return 'studentInfoUpdate';
    }


    // ------------------------ CASTS -------------------------//
    protected function casts(): array
    {
        return [
            'training_type' => TrainingType::class,
            'social_policy_object' => SocialPolicyObject::class,
            'status' => StudentInfoUpdateStatus::class,
        ];
    }
}
