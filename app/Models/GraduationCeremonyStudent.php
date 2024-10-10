<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RankGraduate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class GraduationCeremonyStudent extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'graduation_ceremony_id',
        'student_id',
        'gpa',
        'rank',
        'email',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function graduationCeremony(): BelongsTo
    {
        return $this->belongsTo(GraduationCeremony::class);
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
    public function casts(): array
    {
        return [
            'rank' => RankGraduate::class,
        ];
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    //----------------------- SCOPES ----------------------------------//
}
