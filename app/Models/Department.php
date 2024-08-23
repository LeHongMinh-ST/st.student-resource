<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'created_at',
        'updated_at',
        'status',
        'faculty_id',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function majors(): HasMany
    {
        return $this->hasMany(Major::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    // ------------------------ CASTS -------------------------//
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//
}
