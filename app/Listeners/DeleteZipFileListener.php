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
        Storage::delete('public/zip/' . $zipExportFile->name);
        Storage::deleteDirectory('public/pdf/' . strtok($zipExportFile->name, '.') . '/');
        $zipExportFile->pdfExportFiles()->delete();
        $zipExportFile->delete();
    }
}
