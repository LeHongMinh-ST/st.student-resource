<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest;

use App\DTO\Student\CreateRequestUpdateFamilyStudentDTO;
use App\DTO\Student\CreateRequestUpdateStudentDTO;
use App\Exceptions\CreateResourceFailedException;
use App\Models\FamilyUpdate;
use App\Models\StudentInfoUpdate;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentInfoUpdateService
{
    /**
     * @throws CreateResourceFailedException
     */
    public function create(CreateRequestUpdateStudentDTO $createRequestUpdateStudentDTO): StudentInfoUpdate
    {
        DB::beginTransaction();
        try {
            $studentInfoUpdate = StudentInfoUpdate::create($createRequestUpdateStudentDTO->toArray());

            $families = $this->getFamilies($studentInfoUpdate->id, ...$createRequestUpdateStudentDTO->getFamily());

            FamilyUpdate::insert($families);

            DB::commit();
            return $studentInfoUpdate;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('Error storing request update family service', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw new CreateResourceFailedException();
        }
    }

    /**
     * Converts an array of CreateRequestUpdateFamilyStudentDTO objects to an array of family data arrays.
     *
     * @param int $requestUpdateId The ID of the student info update request.
     * @param CreateRequestUpdateFamilyStudentDTO ...$commandFamilies Variable number of family command objects.
     * @return array An array of family data arrays with timestamps.
     */
    private function getFamilies(int $requestUpdateId, CreateRequestUpdateFamilyStudentDTO ...$commandFamilies): array
    {
        $families = [];
        foreach ($commandFamilies as $family) {
            // Set the student info update ID for each family member
            $family->setStudentInfoUpdateId($requestUpdateId);
            // Get the current timestamp
            $now = Carbon::now()->toDateTimeString();
            // Convert family data to an array and add timestamps
            $families[] = [
                ...$family->toArray(),
                'created_at' => $now,
                'updated_at' => $now
            ];
        }

        return $families;

    }
}
