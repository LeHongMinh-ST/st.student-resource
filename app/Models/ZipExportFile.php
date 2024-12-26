<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusFileImport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZipExportFile extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'file_total',
        'process_total',
        'faculty_id',
        'survey_period_id',
    ];

    public function surveyPeriod(): BelongsTo
    {
        return $this->belongsTo(SurveyPeriod::class);
    }

    public function pdfExportFiles(): HasMany
    {
        return $this->hasMany(PdfExportFile::class);
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    public function getStatusAttribute(): StatusFileImport
    {
        if ($this->pdfExportFiles->count() === $this->file_total) {
            return StatusFileImport::Completed;
        }

        if (0 === $this->pdfExportFiles->count()) {
            return StatusFileImport::Pending;
        }

        if ($this->pdfExportFiles->count() < $this->file_total) {
            return StatusFileImport::Processing;
        }

        return StatusFileImport::Completed;
    }

    //----------------------- SCOPES ----------------------------------//

}
