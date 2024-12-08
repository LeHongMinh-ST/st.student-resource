<?php

declare(strict_types=1);

namespace App\Services\EmploymentSurveyResponse;

use App\DTO\EmploymentSurveyResponse\CreateEmploymentSurveyResponseDTO;
use App\DTO\EmploymentSurveyResponse\UpdateEmploymentSurveyResponseDTO;
use App\Enums\Status;
use App\Models\EmploymentSurveyResponse;
use App\Models\Student;
use App\Models\SurveyPeriod;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EmploymentSurveyResponseService
{
    /**
     * @throws ValidationException
     */
    public function create(CreateEmploymentSurveyResponseDTO $createEmploymentSurveyResponseDTO): EmploymentSurveyResponse
    {
        $this->validateData($createEmploymentSurveyResponseDTO);

        return EmploymentSurveyResponse::create($createEmploymentSurveyResponseDTO->toArray());
    }

    /**
     * @throws ValidationException
     */
    public function createOrUpdate(CreateEmploymentSurveyResponseDTO $createEmploymentSurveyResponseDTO): EmploymentSurveyResponse
    {
        $this->validateData($createEmploymentSurveyResponseDTO);
        $employmentSurveyResponse = EmploymentSurveyResponse::where('student_id', $createEmploymentSurveyResponseDTO->getStudentId())
            ->where('survey_period_id', $createEmploymentSurveyResponseDTO->getSurveyPeriodId())
            ->first();
        if ($employmentSurveyResponse) {
            $employmentSurveyResponse->update($createEmploymentSurveyResponseDTO->toArray());
        } else {
            $employmentSurveyResponse = EmploymentSurveyResponse::create($createEmploymentSurveyResponseDTO->toArray());
        }

        return $employmentSurveyResponse;
    }

    public function update(UpdateEmploymentSurveyResponseDTO $createEmploymentSurveyResponseDTO, mixed $id): EmploymentSurveyResponse
    {
        $this->validateData($createEmploymentSurveyResponseDTO);
        $employmentSurveyResponse = EmploymentSurveyResponse::where('id', $id)->first();
        $employmentSurveyResponse->update($createEmploymentSurveyResponseDTO->toArray());

        return $employmentSurveyResponse;
    }

    public function show(mixed $id): EmploymentSurveyResponse
    {
        return $id instanceof EmploymentSurveyResponse ? $id : EmploymentSurveyResponse::where('id', $id)->first();
    }

    public function searchByCode(array $filter): ?EmploymentSurveyResponse
    {
        if (! isset($filter['survey_period_id'])) {
            return null;
        }

        return EmploymentSurveyResponse::when(Arr::get($filter, 'student_code'), fn ($query) => $query->where('code_student', $filter['student_code']))
            ->when(Arr::get($filter, 'code_verify'), fn ($query) => $query->whereHas('student', fn ($query) => $query->whereHas('surveyPeriods', fn ($query) => $query->where('survey_period_student.code_verify', $filter['code_verify']))))
            ->where('survey_period_id', $filter['survey_period_id'])
            ->first();
    }

    private function validateData(CreateEmploymentSurveyResponseDTO|UpdateEmploymentSurveyResponseDTO $createEmploymentSurveyResponseDTO): void
    {
        $student = Student::where('code', $createEmploymentSurveyResponseDTO->getCodeStudent())->first();

        if (SurveyPeriod::where('id', $createEmploymentSurveyResponseDTO->getSurveyPeriodId())
            ->where('status', Status::Enable)
            ->where('start_time', '<=', now()->format('Y-m-d H:i:s'))
            ->where('end_time', '>=', now()->format('Y-m-d H:i:s'))
            ->doesntExist()) {
            throw ValidationException::withMessages(['survey_period_id' => 'Đợt khảo sát không tồn tại']);
        }

        // check student in list survey period
        if ($student->surveyPeriods->where('id', $createEmploymentSurveyResponseDTO->getSurveyPeriodId())
            ->where('status', Status::Enable)->isEmpty()) {
            throw ValidationException::withMessages(['code_student' => 'Sinh viên không thuộc đợt khảo sát này']);
        }

        if ($createEmploymentSurveyResponseDTO instanceof CreateEmploymentSurveyResponseDTO) {
            // data student is correct
            $isValidate = true;
            if (
                $student->info->dob->format('Y-m-d') === $createEmploymentSurveyResponseDTO->getDob()->format('Y-m-d')
                || $student->info->citizen_identification === $createEmploymentSurveyResponseDTO->getIdentificationCardNumber()
                || ($student->info->phone === $createEmploymentSurveyResponseDTO->getPhoneNumber() && null !== $student->info->phone)
                || $student->training_industry_id === $createEmploymentSurveyResponseDTO->getTrainingIndustryId()
                || ($student->info->person_email === $createEmploymentSurveyResponseDTO->getEmail() && null !== $student->info->person_email)
            ) {
                $isValidate = false;
            }
            if (
                $isValidate
            ) {
                throw ValidationException::withMessages([
                    'message' => 'Để xác nhận thông tin vui lòng nhập đúng một trong các thông tin: ngày sinh, số CCCD, ngành đào tạo, số điện thoại, email. ',
                ]);
            }
        } else {
            // data student is correct
            $isValidate = true;
            if (
                $student->training_industry_id === $createEmploymentSurveyResponseDTO->getTrainingIndustryId()
            ) {
                $isValidate = false;
            }
            if (
                $isValidate
            ) {
                throw ValidationException::withMessages([
                    'message' => 'Để xác nhận thông tin vui lòng nhập đúng: ngành đào tạo.',
                ]);
            }
        }
    }
}
