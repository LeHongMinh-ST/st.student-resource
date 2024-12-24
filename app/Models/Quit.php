<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quit extends Model
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

    // ------------------------ RELATIONS -------------------------//
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    // ------------------------ CASTS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_quits');
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

    //----------------------- SCOPES ----------------------------------//
}
