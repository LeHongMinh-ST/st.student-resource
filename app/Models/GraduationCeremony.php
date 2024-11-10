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
        'year',
        'certification',
        'certification_date',
        'faculty_id',
    ];

    protected $casts = [
        'certification_date' => 'datetime',
    ];

    //----------------------- SCOPES ----------------------------------//

    // ------------------------ RELATIONS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'graduation_ceremony_students')
            ->withPivot('gpa', 'rank', 'email')->withTimestamps()
            ->using(GraduationCeremonyStudent::class);
    }

    public function surveyPeriods(): BelongsToMany
    {
        return $this->belongsToMany(SurveyPeriod::class, 'survey_period_graduation')
            ->withTimestamps();
    }

    public function excelImportFiles()
    {
        return $this->morphToMany(ExcelImportFile::class, 'excelImportFileable');
    }

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//
}
