<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExcelImportType;
use App\Enums\StatusFileImport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExcelImportFile extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'type',
        'total_record',
        'process_record',
        'faculty_id',
        'user_id',
        'type_id',
        'total_job'
    ];

    protected $casts = [
        'type' => ExcelImportType::class,
    ];

    public function students()
    {
        return $this->morphedByMany(Student::class, 'excelImportFileable');
    }

    public function warnings()
    {
        return $this->morphedByMany(Warning::class, 'excelImportFileable');
    }

    public function quits()
    {
        return $this->morphedByMany(Quit::class, 'excelImportFileable');
    }

    public function graduationCeremonies()
    {
        return $this->morphedByMany(GraduationCeremony::class, 'excelImportFileable');

    }

    // ------------------------ RELATIONS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function excelImportFileRecords(): HasMany
    {
        return $this->hasMany(ExcelImportFileRecord::class, 'excel_import_files_id');
    }

    public function excelImportFileJobs(): HasMany
    {
        return $this->hasMany(ExcelImportFileJob::class);
    }

    public function excelImportFileErrors(): HasMany
    {
        return $this->hasMany(ExcelImportFileError::class, 'excel_import_files_id');
    }

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    public function getRecordErrorCountAttribute(): int
    {
        return $this->excelImportFileErrors()->count();
    }

    public function getStatusAttribute(): StatusFileImport
    {
        if ($this->excelImportFileJobs()->count() === $this->total_job) {
            return StatusFileImport::Completed;
        }

        if (0 === $this->excelImportFileJobs()->count()) {
            return StatusFileImport::Pending;
        }

        if ($this->excelImportFileJobs()->count() < $this->total_job) {
            return StatusFileImport::Processing;
        }

        return StatusFileImport::Completed;
    }


    //----------------------- SCOPES ----------------------------------//

}
