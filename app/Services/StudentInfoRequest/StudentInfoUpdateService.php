<?php

declare(strict_types=1);

namespace App\Services\StudentInfoRequest;

use App\DTO\Student\CreateRequestUpdateFamilyStudentDTO;
use App\DTO\Student\CreateRequestUpdateStudentDTO;
use App\DTO\Student\ListRequestUpdateStudentDTO;
use App\DTO\Student\UpdateRequestUpdateStudentDTO;
use App\Enums\StudentInfoUpdateStatus;
use App\Exceptions\ConflictRecordException;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Models\FamilyUpdate;
use App\Models\StudentInfoUpdate;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentInfoUpdateService
{
    public function getList(ListRequestUpdateStudentDTO $listRequestUpdateStudentDTO)
    {
        $query = StudentInfoUpdate::query()
            ->when($listRequestUpdateStudentDTO->getStatus(), fn ($q) => $q->where('status', $listRequestUpdateStudentDTO->getStatus()))
            ->when($listRequestUpdateStudentDTO->getStudentId(), fn ($q) => $q->where('student_id', $listRequestUpdateStudentDTO->getStudentId()))
            ->when($listRequestUpdateStudentDTO->getClassId(), fn ($q) => $q->whereHas('student', fn ($q) => $q->whereHas('currentClass', fn ($q) => $q->where('id', $listRequestUpdateStudentDTO->getClassId()))))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->with(['families'])
            ->orderBy($listRequestUpdateStudentDTO->getOrderBy(), $listRequestUpdateStudentDTO->getOrder()->value);

        return $listRequestUpdateStudentDTO->getPage() ? $query->paginate($listRequestUpdateStudentDTO->getLimit()) : $query->get();
    }

    /**
     * @throws CreateResourceFailedException
     * @throws ConflictRecordException
     */
    public function create(CreateRequestUpdateStudentDTO $createRequestUpdateStudentDTO): StudentInfoUpdate
    {
        DB::beginTransaction();
        try {
            if ($this->isExistRequestUpdatePending()) {
                throw new ConflictRecordException();
            }

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
     * @throws UpdateResourceFailedException
     */
    public function update(UpdateRequestUpdateStudentDTO $updateRequestUpdateStudentDTO): StudentInfoUpdate
    {
        DB::beginTransaction();
        try {

            $studentInfoUpdate = StudentInfoUpdate::where(['id' => $updateRequestUpdateStudentDTO->getId()])
                ->update($updateRequestUpdateStudentDTO->toArray());

            $families = $this->getFamilies($studentInfoUpdate->id, ...$updateRequestUpdateStudentDTO->getFamily());

            FamilyUpdate::where('student_info_id', $updateRequestUpdateStudentDTO->getId())->delete();

            FamilyUpdate::insert($families);

            DB::commit();

            return $studentInfoUpdate;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('Error updating request update family service', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw new UpdateResourceFailedException();
        }
    }

    /**
     * @throws DeleteResourceFailedException
     */
    public function delete(StudentInfoUpdate $studentInfoUpdate): void
    {
        if (StudentInfoUpdateStatus::Pending !== $studentInfoUpdate->status) {
            throw new DeleteResourceFailedException();
        }

        StudentInfoUpdate::query()->where('id', $studentInfoUpdate->id)->delete();
    }

    public function isExistRequestUpdatePending(): bool
    {
        return StudentInfoUpdate::query()
            ->where('student_id', auth('student')->id())
            ->where('status', StudentInfoUpdateStatus::Pending)
            ->exists();
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
