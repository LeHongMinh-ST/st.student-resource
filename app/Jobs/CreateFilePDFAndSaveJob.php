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
use Log;

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
        private int $zipExportFileId,
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

        try {
            foreach ($this->surveyResponses as $surveyResponse) {
                $this->createPDFFile($this->surveyPeriod, $surveyResponse);
            }

            // Create PDF file and save to storage
            event(new ExportSurveyResponseEvent(
                userId: $this->userId
            ));
        } catch (Exception $e) {
            Log::info([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function createPDFFile(SurveyPeriod $surveyPeriod, EmploymentSurveyResponse $surveyResponse): string
    {
        $surveyPeriod->start_time_format = $surveyPeriod->start_time->format('d/m/Y');
        $surveyPeriod->end_time_format = $surveyPeriod->end_time->format('d/m/Y');
        $surveyResponse->dob_format = $surveyResponse?->dob?->format('d/m/Y');
        $surveyResponse->gender_txt = $surveyResponse?->gender?->getName();
        $surveyResponse->trainingIndustry_name = $surveyResponse?->trainingIndustry?->name;
        $surveyResponse->cityWork_name = $surveyResponse?->cityWork?->name;
        $surveyResponse->identification_issuance_date_format = $surveyResponse?->identification_issuance_date?->format('d/m/Y');
        $surveyResponse->recruit_partner_date_format = $surveyResponse?->recruit_partner_date?->format('d/m/Y');
        // Create PDF file
        $pdfResponse = Pdf::loadView('pdf.response-survey', ['surveyResponse' => $surveyResponse->toArray(), 'surveyPeriod' => $surveyPeriod->toArray()]);
        $fileName = $surveyResponse->code_student . '_' . $this->zipExportFileId . '.pdf';

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
            'zip_export_file_id' => $this->zipExportFileId,
        ]);

        ZipExportFile::where('id', $this->zipExportFileId)
            ->increment('process_total');

        return $fileName;
    }
}
