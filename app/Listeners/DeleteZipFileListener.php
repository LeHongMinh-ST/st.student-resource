<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\DownloadSurveyResponseEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class DeleteZipFileListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(DownloadSurveyResponseEvent $event): void
    {
        $zipExportFile = $event->zipExportFile;
        $listFilePdfNames = $zipExportFile->pdfExportFiles->pluck('name')->map(fn ($name) => 'public/pdf/' . $name)->toArray();
        Storage::delete($listFilePdfNames);
        $zipExportFile->pdfExportFiles()->delete();
        $zipExportFile->delete();
    }
}
