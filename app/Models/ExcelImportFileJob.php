<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExcelImportFileJob extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'excel_import_file_id',
        'job_id',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function excelImportFile(): BelongsTo
    {
        return $this->belongsTo(ExcelImportFile::class);
    }

    // ------------------------ CASTS -------------------------//


    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//

}
