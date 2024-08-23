<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'created_at',
        'updated_at',
        'status',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('is_super_admin', true);
    }

    public function majors(): HasMany
    {
        return $this->hasMany(Major::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function generalClasses(): HasMany
    {
        return $this->hasMany(GeneralClass::class);
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
