<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ZipExportFile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DownloadSurveyResponseEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public ZipExportFile $zipExportFile;

    /**
     * Create a new event instance.
     */
    public function __construct(
        ZipExportFile $zipExportFile
    ) {
        $this->zipExportFile = $zipExportFile;
    }
}
