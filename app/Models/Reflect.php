<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReflectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reflect extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'title',
        'subject',
        'status',
        'student_id',
        'user_id',
        'content',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // ------------------------ CASTS -------------------------//
    protected function casts(): array
    {
        return [
            'status' => ReflectStatus::class,
        ];
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//
}
