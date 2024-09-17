<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExcelImportType;
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
        'admission_year_id'
    ];

    protected $casts = [
        'type' => ExcelImportType::class,
    ];

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

    //----------------------- SCOPES ----------------------------------//

}
