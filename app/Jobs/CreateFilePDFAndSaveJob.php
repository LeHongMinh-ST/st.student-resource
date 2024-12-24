<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\ExportSurveyResponseEvent;
use App\Models\EmploymentSurveyResponse;
use App\Models\PdfExportFile;
use App\Models\SurveyPeriod;
use App\Models\ZipExportFile;
use App\Traits\HandlesCsvImportJob;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class CreateFilePDFAndSaveJob implements ShouldQueue
{
    use Dispatchable;
    use HandlesCsvImportJob;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private SurveyPeriod $surveyPeriod,
        private ZipExportFile $zipExportFile,
        private int $userId,
        private ?Collection $surveyResponses = null,
    ) {
    }

    /**
     * Execute the job.
     *
     * @throws Exception
     */
    public function handle(
    ): void {

        foreach ($this->surveyResponses as $surveyResponse) {
            $this->createPDFFile($this->surveyPeriod, $surveyResponse);
        }

        // Create PDF file and save to storage
        event(new ExportSurveyResponseEvent(
            userId: $this->userId
        ));
    }

    private function createPDFFile(SurveyPeriod $surveyPeriod, EmploymentSurveyResponse $surveyResponse): string
    {
        // Create PDF file
        $pdfResponse = Pdf::loadView('pdf.response-survey', ['surveyResponse' => $surveyResponse, 'surveyPeriod' => $surveyPeriod]);
        $fileName = $surveyResponse->code_student . '_' . $this->zipExportFile->id . '.pdf';

        $pdfResponse->setOptions([
            'defaultFont' => 'DejaVu Sans', // Sử dụng font mặc định của DomPDF
            'show_footer' => false, // Tắt footer
            'show_header' => false, // Tắt header
        ]);

        // Tạo thư mục chưa danh sách file PDF
        $pdfResponse->save(storage_path('app/public/pdf/' . $fileName));

        // create pdf file record
        PdfExportFile::create([
            'name' => $fileName,
            'zip_export_file_id' => $this->zipExportFile->id,
        ]);

        ZipExportFile::where('id', $this->zipExportFile->id)
            ->increment('process_total');

        return $fileName;
    }
}
