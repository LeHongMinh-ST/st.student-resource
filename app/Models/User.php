<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'faculty_id',
        'remember_token',
        'code',
        'thumbnail',
        'department_id',
        'status',
        'faculty_id',
        'role_id',
        'is_super_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    // ------------------------ JWT FUNCTIONS -------------------------//

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     * `*/
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    // ------------------------ RELATIONS -------------------------//
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }


    // ---------------------- ACCESSORS AND MUTATORS --------------------//
    public function getThumbnailPathAttribute(): string
    {
        return isset($this->thumbnail) ? asset(Storage::url($this->thumbnail)) : '';
    }


    /**
     * Get the permissions for the user through the role.
     *
     * @return Collection
     */
    public function getPermissionsAttribute(): Collection
    {
        if ($this->is_super_admin) {
            return Permission::all();
        }


        return $this->role ? $this->role->permissions : collect([]);
    }


    // ------------------------ CASTS -------------------------//

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => Status::class,
        ];
    }

    //----------------------- SCOPES ----------------------------------//
}
