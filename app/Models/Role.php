<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'status',
        'description',
        'faculty_id',
        'is_default',
    ];


    // ------------------------ RELATIONS -------------------------//
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }


    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function (Role $post): void {
            $post->permissions()->detach();
        });
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
