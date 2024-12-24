<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class StudentWarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'warning_id',
        'student_id',
    ];

    protected $table = 'student_warnings';

    // ------------------------ RELATIONS -------------------------//
    public function warning(): BelongsTo
    {
        return $this->belongsTo(Warning::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function excelImportFileRecord(): MorphOne
    {
        return $this->morphOne(ExcelImportFileRecord::class, 'tableable', 'table_type', 'table_id');
    }

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    //----------------------- SCOPES ----------------------------------//
}
