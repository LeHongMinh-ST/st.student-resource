<?php

declare(strict_types=1);

namespace App\Services\EmploymentSurveyResponse;

use App\DTO\EmploymentSurveyResponse\CreateEmploymentSurveyResponseDTO;
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

    public function show(mixed $id): EmploymentSurveyResponse
    {
        return $id instanceof EmploymentSurveyResponse ? $id : EmploymentSurveyResponse::where('id', $id)->first();
    }

    public function searchByCode(array $filter): EmploymentSurveyResponse|null
    {
        if (! isset($filter['survey_period_id'])) {
            return null;
        }

        return EmploymentSurveyResponse::when(Arr::get($filter, 'student_code'), fn ($query) => $query->where('code_student', $filter['student_code']))
            ->when(Arr::get($filter, 'code_verify'), fn ($query) => $query->whereHas('student', fn ($query) => $query->whereHas('surveyPeriods', fn ($query) => $query->where('survey_period_student.code_verify', $filter['code_verify']))))
            ->where('survey_period_id', $filter['survey_period_id'])
            ->first();
    }

    private function validateData(CreateEmploymentSurveyResponseDTO $createEmploymentSurveyResponseDTO): void
    {
        $student = Student::where('code', $createEmploymentSurveyResponseDTO->getCodeStudent())->first();

        if (SurveyPeriod::where('id', $createEmploymentSurveyResponseDTO->getSurveyPeriodId())
            ->where('status', Status::Enable)
            ->where('start_time', '<=', now()->format('Y-m-d H:i:s'))
            ->where('end_time', '>=', now()->format('Y-m-d H:i:s'))
            ->doesntExist()) {
            throw ValidationException::withMessages(['survey_period_id' => trans('survey_period_student.message.survey_period_not_found')]);
        }

        // check student in list survey period
        if ($student->surveyPeriods->where('id', $createEmploymentSurveyResponseDTO->getSurveyPeriodId())
            ->where('status', Status::Enable)->isEmpty()) {
            throw ValidationException::withMessages(['code_student' => trans('survey_period_student.message.student_not_in_survey_period')]);
        }

        // data student is correct
        //        if (
        //            $student->info->dob->format('Y-m-d') !== $createEmploymentSurveyResponseDTO->getDob()->format('Y-m-d')
        //            // || $student->info->citizen_identification !== $createEmploymentSurveyResponseDTO->getIdentificationCardNumber()
        //        ) {
        //            throw ValidationException::withMessages([
        //                'message' => 'Dữ liệu sinh viên không chính xác',
        //            ]);
        //        }
    }
}
