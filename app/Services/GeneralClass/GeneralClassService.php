<?php

declare(strict_types=1);

namespace App\Services\GeneralClass;

use App\DTO\GeneralClass\CreateGeneralClassDTO;
use App\DTO\GeneralClass\ListGeneralClassDTO;
use App\DTO\GeneralClass\UpdateGeneralClassDTO;
use App\Enums\AuthApiSection;
use App\Enums\Status;
use App\Enums\StudentRole;
use App\Enums\UserRole;
use App\Models\GeneralClass;
use Illuminate\Validation\ValidationException;

class GeneralClassService
{
    public function getList(ListGeneralClassDTO $listFacultyDTO): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator|array
    {
        $auth = auth(AuthApiSection::Admin->value)->user();
        $query = GeneralClass::query()
            ->when(UserRole::Teacher === $auth->role, function ($q) use ($auth, $listFacultyDTO) {
                if ('teacher' === $listFacultyDTO->getType()) {
                    return $q->where('teacher_id', $auth->id);
                }
                return $q->where('sub_teacher_id', $auth->id);

            })
            ->when($listFacultyDTO->getQ(), fn ($q) => $q->where('code', 'like', "%{$listFacultyDTO->getQ()}%"))
            ->when($listFacultyDTO->getTeacherId(), fn ($q) => $q->where('teacher_id', $listFacultyDTO->getTeacherId()))
            ->when($listFacultyDTO->getSubTeacherId(), fn ($q) => $q->where('sub_teacher_id', $listFacultyDTO->getSubTeacherId()))
            ->when($listFacultyDTO->getFacultyId(), fn ($q) => $q->where('faculty_id', $listFacultyDTO->getFacultyId()))
            ->when($listFacultyDTO->getStatus(), fn ($q) => $q->where('status', $listFacultyDTO->getStatus()))
            ->when($listFacultyDTO->getAdmissionYearId(), fn ($q) => $q->where('admission_year_id', $listFacultyDTO->getAdmissionYearId()))
            ->with(['teacher', 'subTeacher', 'admissionYear'])
            ->join('admission_years', 'admission_years.id', '=', 'classes.admission_year_id')
            ->select('classes.*')
            ->orderBy('admission_years.admission_year', 'desc')
            ->orderBy('classes.' . $listFacultyDTO->getOrderBy(), $listFacultyDTO->getOrder()->value);
        return $listFacultyDTO->getPage() ? $query->paginate($listFacultyDTO->getLimit()) : $query->get();
    }

    public function create(CreateGeneralClassDTO $createFacultyDTO): GeneralClass
    {
        return GeneralClass::create($createFacultyDTO->toArray());
    }

    public function update(UpdateGeneralClassDTO $dto): GeneralClass
    {
        $class = GeneralClass::where('id', $dto->getId())->first();
        $class->update($dto->toArray());

        $class->students()->wherePivot('role', StudentRole::President->value)->update([
            'role' => StudentRole::Basic,
        ]);

        if ($dto->getStudentPresidentId()) {
            $class->students()->updateExistingPivot($dto->getStudentPresidentId(), [
                'role' => StudentRole::President->value,
            ]);
        }


        $class->students()->wherePivot('role', StudentRole::Secretary->value)->update([
            'role' => StudentRole::Basic,
        ]);

        if ($dto->getStudentSecretaryId()) {
            $class->students()->updateExistingPivot($dto->getStudentSecretaryId(), [
                'role' => StudentRole::Secretary->value,
            ]);
        }


        return $class;
    }

    public function delete(mixed $id): bool
    {
        $generalClass = $this->getGeneralClassById($id);
        if ($generalClass->students->count()) {
            throw ValidationException::withMessages(
                ['message' => 'Lớp học đang được sử dụng bởi sinh viên']
            );
        }

        return $generalClass->delete();
    }

    public function getGeneralClassById(mixed $id): GeneralClass
    {
        return $id instanceof GeneralClass ? $id : GeneralClass::find($id);
    }

    public function getGeneralClassByCode(string $code): ?GeneralClass
    {
        return GeneralClass::where('code', $code)->first();
    }

    public function getGeneralClassCount(): int
    {
        $auth = auth(AuthApiSection::Admin->value)->user();

        $classCount = GeneralClass::query()
            ->where('faculty_id', $auth->faculty_id)
            ->where('status', Status::Enable)
            ->when(UserRole::Teacher === $auth->role, fn ($q) => $q->where('teacher_id', $auth->id)->orWhere('sub_teacher_id', $auth->id))
            ->count();

        return $classCount;
    }


}
