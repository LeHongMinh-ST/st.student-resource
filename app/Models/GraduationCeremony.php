<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GraduationCeremony extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'school_year',
        'certification',
        'certification_date',
        'faculty_id',
    ];

    //----------------------- SCOPES ----------------------------------//

    public static function boot(): void
    {
        self::deleting(function ($model): void {
            $model->students()->detach();
        });
    }

    // ------------------------ RELATIONS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class)
            ->withPivot('gpa', 'rank', 'email')->withTimestamps()
            ->using(GraduationCeremonyStudent::class);
    }

    public function excelImportFiles()
    {
        return $this->morphToMany(ExcelImportFile::class, 'excelImportFileable');
    }

    // ------------------------ CASTS -------------------------//


    // ---------------------- ACCESSORS AND MUTATORS --------------------//
    public function getStudentCountAttribute(): int
    {
        return $this->students()->count();
    }
}
