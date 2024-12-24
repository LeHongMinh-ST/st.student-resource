<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdfExportFile extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'zip_export_file_id',
    ];

    public function zipExportFile(): BelongsTo
    {
        return $this->belongsTo(ZipExportFile::class);
    }

}
