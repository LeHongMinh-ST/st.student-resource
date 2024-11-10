<?php

declare(strict_types=1);

namespace App\Supports;

use App\Enums\ExcelImportType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelFileHelper
{
    /**
     * Chunk file to csv
     *
     * @return array
     *               {
     *               'total_row_data' => int,
     *               'file_names' => array
     *               }
     */
    public static function chunkFileToCsv(UploadedFile $fileExcel, ExcelImportType $typeFile, int $chunkSize = 100, int $rowStart = 2): array
    {
        // Create a new Reader of the type defined in $inputFileType
        $reader = IOFactory::createReader(ucfirst($fileExcel->extension()));
        $spreadsheet = $reader->load($fileExcel->getRealPath());
        $worksheet = $spreadsheet->getActiveSheet();

        $maxDataRow = $worksheet->getHighestDataRow();
        $maxDataColumn = $worksheet->getHighestDataColumn();

        // chunk file with chunk size
        $startNumber = 1;

        // Define the storage path for the avatar image
        $pathStorage = storage_path('app/public/') . Constants::PATH_FILE_IMPORT_COURSE;

        // Check if the directory exists in the public disk, if not, create it
        if (! Storage::disk('public')->exists(Constants::PATH_FILE_IMPORT_COURSE)) {
            Storage::disk('public')->makeDirectory(Constants::PATH_FILE_IMPORT_COURSE);
        }

        $rowHear = $worksheet->rangeToArray("A1:{$maxDataColumn}1")[0];
        $nameFunctionConvertKey = 'convertKeyCourseVietnameseToUnicode' . ucfirst($typeFile->value);
        $rowHear = array_map(fn ($value) => self::$nameFunctionConvertKey($value), $rowHear);
        $fileNames = [];

        while ($rowStart <= $maxDataRow) {
            $endRow = min($rowStart + $chunkSize - 1, $maxDataRow);
            $endNumber = min($startNumber + $chunkSize - 1, $maxDataRow - 1);
            $dataArray = $worksheet->rangeToArray("A{$rowStart}:{$maxDataColumn}{$endRow}");

            $sourceData = [
                $rowHear,
            ];
            $spreadsheetWrite = new Spreadsheet();

            foreach ($dataArray as $row) {
                // Check for empty rows
                if (! count(array_filter($row, fn ($value) => null !== $value))) {
                    // Ignore empty rows
                    continue;
                }
                $sourceData[] = $row;
            }

            // Write data to csv file
            $spreadsheetWrite->getActiveSheet()->fromArray($sourceData, null, 'A1');
            $writer = IOFactory::createWriter($spreadsheetWrite, 'Csv');
            $fileName = ImageHelper::generateNameFile($fileExcel) . '_row_start_' . $rowStart . '.csv';
            $writer->save($pathStorage . $fileName);
            $fileNames[] = $fileName;
            $rowStart += $chunkSize;
            $startNumber = $startNumber + $chunkSize;
        }

        return [
            'file_names' => $fileNames,
            'total_row_data' => $maxDataRow,
        ];
    }

    public static function convertKeyCourseVietnameseToUnicodeCourse(string $keyMap): ?string
    {
        $keys = [
            'STT' => 'index',
            'Lớp' => 'class_code',
            'Mã SV' => 'code',
            'Niên khóa' => 'school_year',
            'Họ tên' => 'full_name',
            'Giới tính' => 'gender',
            'Ngày sinh' => 'dob',
            'Dân tộc' => 'ethnic', // 'nation',
            'Điện thoại' => 'phone_number',
            'Email' => 'person_email',
            'Địa chỉ báo tin' => 'address',
            'Họ tên bố' => 'father_name',
            'SĐT của bố' => 'father_phone',
            'Họ tên mẹ' => 'mother_name',
            'SĐT của mẹ' => 'mother_phone',
            'Khoa' => 'faculty',
        ];

        return Arr::get($keys, trim($keyMap));
    }

    public static function convertKeyCourseVietnameseToUnicodeGraduate(string $keyMap): ?string
    {
        $keys = [
            'STT' => 'index',
            'Mã SV' => 'code',
            'Email' => 'email',
            'Họ và tên' => 'full_name',
            'Ngày sinh' => 'dob',
            'Mã ngành đào tạo' => 'training_industry_code',
            'Ngành đào tạo' => 'training_industry_name',
            'Mã lớp' => 'class_code',
            'Điểm TB' => 'gpa',
            'Xếp loại' => 'rank',
            'CCCD/CMND' => 'identity_card',
            'Nơi sinh' => 'address',
            'Hệ đào tạo' => 'training_system',
            'Lớp' => 'class_name',
            'SĐT' => 'phone_number',
            'Giới tính' => 'gender',
        ];

        return Arr::get($keys, trim($keyMap));
    }

    public static function convertKeyCourseVietnameseToUnicodeQuit(string $keyMap): ?string
    {
        $keys = [
            'STT' => 'index',
            'Mã SV' => 'code',
            'Lý do' => 'quit_note',
        ];

        return Arr::get($keys, trim($keyMap));
    }

    public static function convertKeyCourseVietnameseToUnicodeWarning(string $keyMap): ?string
    {
        $keys = [
            'STT' => 'index',
            'Mã SV' => 'code',
        ];

        return Arr::get($keys, trim($keyMap));
    }
}
