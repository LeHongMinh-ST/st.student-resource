<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    use HasFactory;

    protected $fillable = [
        'mame',
        'priority',
        'map_name',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'priority' => 'integer',
        'map-name' => 'array',
    ];
}
