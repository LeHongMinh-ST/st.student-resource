<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ClassType;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassStudent extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'class_id',
        'student_id',
        'end_date',
        'start_date',
        'status',
    ];

    protected $table = 'class_students';

    // ------------------------ RELATIONS -------------------------//
    public function generalClass(): BelongsTo
    {
        return $this->belongsTo(GeneralClass::class);
    }

    // ------------------------ CASTS -------------------------//
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'type' => ClassType::class,
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    // ---------------------- ACCESSORS AND MUTATORS --------------------//


    //----------------------- SCOPES ----------------------------------//
}
