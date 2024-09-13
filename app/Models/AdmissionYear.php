<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdmissionYear extends Model
{
    use HasFactory;

    protected $fillable = ['admission_year', 'school_year'];

    // ------------------------ RELATIONS -------------------------//
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
