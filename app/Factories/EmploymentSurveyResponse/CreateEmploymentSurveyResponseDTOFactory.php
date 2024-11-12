<?php

declare(strict_types=1);

namespace App\Factories\EmploymentSurveyResponse;

use App\DTO\EmploymentSurveyResponse\CreateEmploymentSurveyResponseDTO;
use App\DTO\EmploymentSurveyResponse\ResponseOther;
use App\Enums\EmploymentSurvey\AverageIncome;
use App\Enums\EmploymentSurvey\EmployedSince;
use App\Enums\EmploymentSurvey\EmploymentStatus;
use App\Enums\EmploymentSurvey\LevelKnowledgeAcquired;
use App\Enums\EmploymentSurvey\ProfessionalQualificationField;
use App\Enums\EmploymentSurvey\TrainedField;
use App\Enums\EmploymentSurvey\WorkArea;
use App\Enums\Gender;
use App\Http\Requests\Student\EmploymentSurveyResponse\StoreEmploymentSurveyResponseRequest;
use App\Models\Student;
use Carbon\Carbon;

class CreateEmploymentSurveyResponseDTOFactory
{
    public static function make(StoreEmploymentSurveyResponseRequest $request): CreateEmploymentSurveyResponseDTO
    {
        $dto = new CreateEmploymentSurveyResponseDTO();
        $dto->setSurveyPeriodId($request->input('survey_period_id'));
        if ($request->has('student_id')) {
            $dto->setStudentId($request->input('student_id'));
        } else {
            $studentId = Student::select('id')->where('code', $request->input('code_student'))->first()->id;
            $dto->setStudentId($studentId);
        }
        $dto->setEmail($request->input('email'));
        $dto->setFullName($request->input('full_name'));
        $dto->setGender(Gender::from($request->input('gender')));
        $dto->setDob(Carbon::createFromFormat('Y-m-d', $request->input('dob')));
        $dto->setPhoneNumber($request->input('phone_number'));
        $dto->setCodeStudent($request->input('code_student'));
        $dto->setIdentificationCardNumber($request->input('identification_card_number'));
        if ($request->has('identification_card_number_update')) {
            $dto->setIdentificationCardNumberUpdate($request->input('identification_card_number_update'));
        }
        $dto->setIdentificationIssuancePlace($request->input('identification_issuance_place'));
        $dto->setIdentificationIssuanceDate(Carbon::createFromFormat('Y-m-d', $request->input('identification_issuance_date')));
        $dto->setTrainingIndustryId($request->input('training_industry_id'));
        $dto->setAdmissionYearId($request->input('admission_year_id'));
        $dto->setEmploymentStatus(EmploymentStatus::from($request->input('employment_status')));
        if (EmploymentStatus::Employed === $dto->getEmploymentStatus()) {
            if ($request->has('recruit_partner_name')) {
                $dto->setRecruitPartnerName($request->input('recruit_partner_name'));
            }
            if ($request->has('recruit_partner_address')) {
                $dto->setRecruitPartnerAddress($request->input('recruit_partner_address'));
            }
            if ($request->has('recruit_partner_date')) {
                $dto->setRecruitPartnerDate(Carbon::createFromFormat('Y-m-d', $request->input('recruit_partner_date')));
            }
            if ($request->has('recruit_partner_position')) {
                $dto->setRecruitPartnerPosition($request->input('recruit_partner_position'));
            }
            if ($request->has('work_area')) {
                $dto->setWorkArea(WorkArea::from((int) ($request->input('work_area'))));
            }
            if ($request->has('employed_since')) {
                $dto->setEmployedSince(EmployedSince::from((int) $request->input('employed_since')));
            }
            if ($request->has('trained_field')) {
                $dto->setTrainedField(TrainedField::from((int) $request->input('trained_field')));
            }
            if ($request->has('professional_qualification_field')) {
                $dto->setProfessionalQualificationField(
                    ProfessionalQualificationField::from((int) $request->input('professional_qualification_field'))
                );
            }
            if ($request->has('level_knowledge_acquired')) {
                $dto->setLevelKnowledgeAcquired(
                    LevelKnowledgeAcquired::from((int) $request->input('level_knowledge_acquired'))
                );
            }
            if ($request->has('starting_salary')) {
                $dto->setStartingSalary((int) $request->input('starting_salary'));
            }
            if ($request->has('average_income')) {
                $dto->setAverageIncome(AverageIncome::from((int) $request->input('average_income')));
            }
            if ($request->has('job_search_method')) {
                $jobSearchMethod = new ResponseOther();
                $dto->setJobSearchMethod($jobSearchMethod);
            }
            if ($request->has('recruitment_type')) {
                $dto->setRecruitmentType(new ResponseOther(
                    $request->input('recruitment_type.value'),
                    $request->input('recruitment_type.content_other')
                ));
            }
            if ($request->has('soft_skills_required')) {
                $dto->setSoftSkillsRequired(new ResponseOther(
                    $request->input('soft_skills_required.value'),
                    $request->input('soft_skills_required.content_other')
                ));
            }
            if ($request->has('must_attended_courses')) {
                $dto->setMustAttendedCourses(new ResponseOther(
                    $request->input('must_attended_courses.value'),
                    $request->input('must_attended_courses.content_other')
                ));
            }
        }
        if ($request->has('solutions_get_job')) {
            $dto->setSolutionsGetJob(new ResponseOther(
                $request->input('solutions_get_job.value'),
                $request->input('solutions_get_job.content_other')
            ));
        }

        return $dto;
    }
}
