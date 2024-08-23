<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExcelImportFileRecord extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'excel_import_files_id',
        'table_id',
        'table_type',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function tableable(): MorphTo
    {
        return $this->morphTo();
    }

    public function excelImportFile(): BelongsTo
    {
        return $this->belongsTo(ExcelImportFile::class);
    }

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    //----------------------- SCOPES ----------------------------------//
}
