<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SocialPolicyObject;
use App\Enums\TrainingType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class StudentInfo extends Model
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
    ];

    // ------------------------ RELATIONS -------------------------//
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    public function getThumbnailUrlAttribute(): string
    {
        return config('app.url') . Storage::url($this->thumbnail);
    }

    public function families(): HasMany
    {
        return $this->hasMany(Family::class);
    }

    // ------------------------ CASTS -------------------------//
    protected function casts(): array
    {
        return [
            'training_type' => TrainingType::class,
            'social_policy_object' => SocialPolicyObject::class,
            'dob' => 'date',
        ];
    }

    //----------------------- SCOPES ----------------------------------//
}
