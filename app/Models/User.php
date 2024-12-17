<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Status;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'role',
    ];

    protected $casts = [
        'status' => Status::class,
        'role' => UserRole::class,
        'password' => 'hashed'
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

    public function ownsClass()
    {
        return $this->hasMany(GeneralClass::class, 'teacher_id', 'id');
    }

    public function ownsSubClass()
    {
        return $this->hasMany(GeneralClass::class, 'sub_teacher_id', 'id');
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//
    public function getThumbnailPathAttribute(): string
    {
        return isset($this->thumbnail) ? asset(Storage::url($this->thumbnail)) : '';
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
            'role' => UserRole::class,
        ];
    }

    //----------------------- SCOPES ----------------------------------//
}
