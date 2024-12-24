<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warning extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'semester_id',
        'faculty_id',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_warnings');
    }

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    public function getSchoolYearAttribute()
    {
        return $this->semester?->schoolYear?->start_year . ' - ' . $this->semester?->schoolYear?->end_year;
    }

    //----------------------- SCOPES ----------------------------------//
}
