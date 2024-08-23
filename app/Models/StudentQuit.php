<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentQuit extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'note_quit',
        'quit_id',
        'student_id',
    ];

    // ------------------------ RELATIONS -------------------------//
    public function quit(): BelongsTo
    {
        return $this->belongsTo(Quit::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // ------------------------ CASTS -------------------------//

    // ---------------------- ACCESSORS AND MUTATORS --------------------//

    //----------------------- SCOPES ----------------------------------//
}
