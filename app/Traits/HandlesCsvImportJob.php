<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\ExcelImportFileError;
use App\Models\ExcelImportFileJob;
use App\Supports\Constants;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

trait HandlesCsvImportJob
{
    /**
     * Store job in ExcelImportFileJob if a Job ID exists.
     *
     * @param string|int $jobId
     * @param int $excelImportFileId
     * @param ExcelImportFileJob $excelImportFileJobModel
     */
    protected function storeJob(string|int $jobId, int $excelImportFileId, ExcelImportFileJob $excelImportFileJobModel): void
    {
        if ($jobId) {
            $excelImportFileJobModel::create([
                'excel_import_file_id' => $excelImportFileId,
                'job_id' => $jobId,
            ]);
        }
    }

    /**
     * Get the CSV file path based on the file name.
     *
     * @param string $fileName
     * @return string
     */
    protected function getFilePath(string $fileName): string
    {
        return storage_path('app/public/' . Constants::PATH_FILE_IMPORT_COURSE . $fileName);
    }


    /**
     * Get the CSV file row start on the file name.
     *
     * @param string $fileName
     * @return int
     */
    protected function getRowStart(string $fileName): int
    {
        return (int)str_replace('.csv', '', Arr::last(explode('_', $fileName))) * 1;
    }

    /**
     * Read the CSV file and return it as an array.
     *
     * @param string $filePath
     * @return array
     * @throws Exception
     */
    protected function loadCsvFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new Exception('File not found: ' . $filePath);
        }

        return IOFactory::load($filePath)->getActiveSheet()->toArray();
    }

    /**
     * Log error and save to ExcelImportFileError table.
     *
     * @param Exception $exception
     * @param int $rowStart
     * @param ExcelImportFileError $excelImportFileErrorModel
     * @param int $excelImportFileId
     */
    protected function handleException(
        Exception            $exception,
        int                  $rowStart,
        ExcelImportFileError $excelImportFileErrorModel,
        int                  $excelImportFileId
    ): void {
        Log::error('Error during CSV processing', [
            'method' => __METHOD__,
            'message' => $exception->getMessage(),
        ]);

        $excelImportFileErrorModel->create([
            'excel_import_files_id' => $excelImportFileId,
            'row' => $rowStart,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * Delete the file after processing if no errors occurred.
     *
     * @param string $filePath
     */
    protected function deleteFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
