<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupPermission extends Model
{
    use HasFactory;

    protected $table = 'group_permissions';

    protected $fillable = [
        'name',
        'code',
    ];

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'code_group', 'code');
    }
}
