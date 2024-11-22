<?php

declare(strict_types=1);

namespace App\Services\ReportSurvey;

use App\DTO\ReportSurvey\ReportSurveyByYearDTO;
use App\Enums\EmploymentSurvey\AverageIncome;
use App\Enums\EmploymentSurvey\EmployedSince;
use App\Enums\EmploymentSurvey\EmploymentStatus;
use App\Enums\EmploymentSurvey\JobSearchMethod;
use App\Enums\EmploymentSurvey\LevelKnowledgeAcquired;
use App\Enums\EmploymentSurvey\MustAttendedCourses;
use App\Enums\EmploymentSurvey\RecruitmentType;
use App\Enums\EmploymentSurvey\SoftSkillsRequired;
use App\Enums\EmploymentSurvey\SolutionsGetJob;
use App\Enums\EmploymentSurvey\TrainedField;
use App\Enums\EmploymentSurvey\WorkArea;
use App\Enums\Gender;
use App\Enums\Status;
use App\Models\EmploymentSurveyResponse;
use App\Models\Faculty;
use App\Models\SurveyPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportSurveyService
{
    public function reportEmploymentSurveyByYear(ReportSurveyByYearDTO $dto): void
    {
        $year = $dto->getYear();
        $surveys = SurveyPeriod::select('id')
            ->where('id', '<>', 1)
            ->where('status', Status::Enable->value)
            ->withCount([
                'students as total_students',
                'students as total_female' => fn ($query) => $query->whereHas('info', fn ($q) => $q->where('gender', Gender::Female->value)),
            ])
            ->where('year', $year)->get()->toArray();

        $data = EmploymentSurveyResponse::selectRaw('
        training_industry_id' .
            ", SUM(if (gender = '" . Gender::Female->value . "', 1, 0)) as 'total_female', COUNT(*) as 'total_gender',
        sum(if (employment_status = " . EmploymentStatus::Unemployed->value . ", 1, 0)) as 'total_unemployment',
        sum(if (employment_status = " . EmploymentStatus::ContinuingEducation->value . ", 1, 0)) as 'continue_education',
        sum(if (employment_status = " . EmploymentStatus::Employed->value . ", 1, 0)) as 'total_employment',
        sum(if (trained_field = " . TrainedField::RightTraining->value . ", 1, 0)) as 'right_training',
        sum(if (trained_field = " . TrainedField::RelatedTraining->value . ", 1, 0)) as 'relation_training',
        sum(if (trained_field = " . TrainedField::NotRelatedTraining->value . ", 1, 0)) as 'not_relation_training',
        sum(if (work_area = " . WorkArea::StateSector->value . ", 1, 0)) as 'total_work_area_state',
        sum(if (work_area = " . WorkArea::PrivateSector->value . ", 1, 0)) as 'total_work_area_private',
        sum(if (work_area = " . WorkArea::SelfEmployment->value . ", 1, 0)) as 'total_work_area_self',
        sum(if (work_area = " . WorkArea::ElementForeignSector->value . ", 1, 0)) as 'total_work_are_foreign',
        GROUP_CONCAT(city_work_id SEPARATOR ',') AS 'work_cities'
    ")->whereIn('survey_period_id', Arr::pluck($surveys, 'id'))->groupBy('training_industry_id')->get();

        dd($data);
    }

    public function getReportEmploymentSurveyTemplateThree(mixed $surveyId): StreamedResponse
    {
        $employmentSurveyResponses = EmploymentSurveyResponse::with(['student.info', 'trainingIndustry.faculty'])
            ->where('survey_period_id', $surveyId)->get();

        $dataTransfer = $employmentSurveyResponses->map(function ($item) {
            return [
                'student_code' => $item->code_student,
                'full_name' => $item->full_name,
                'dob' => $item->dob?->format('d/m/Y'),
                'gender' => $item->gender->getName(),
                'identification_card_number' => $item->identification_card_number,
                'training_industry_code' => $item->trainingIndustry->code,
                'phone_number' => $item->phone_number,
                'email' => $item->email,
                'right_training' => $item->trained_field?->value === TrainedField::RightTraining->value ? 'x' : '',
                'relation_training' => $item->trained_field?->value === TrainedField::RelatedTraining->value ? 'x' : '',
                'not_relation_training' => $item->trained_field?->value === TrainedField::NotRelatedTraining->value ? 'x' : '',
                'continue_education' => $item->employment_status?->value === EmploymentStatus::ContinuingEducation->value ? 'x' : '',
                'unemployment' => $item->employment_status?->value === EmploymentStatus::Unemployed->value ? 'x' : '',
                'work_area_foreign' => $item->work_area?->value === WorkArea::ElementForeignSector->value ? 'x' : '',
                'work_area_private' => $item->work_area?->value === WorkArea::PrivateSector->value ? 'x' : '',
                'work_area_self' => $item->work_area?->value === WorkArea::SelfEmployment->value ? 'x' : '',
                'work_area_state' => $item->work_area?->value === WorkArea::StateSector->value ? 'x' : '',
                'work_cities' => $item->cityWork?->code,
                'employed_since_less_than_3_months' => $item->employed_since?->value === EmployedSince::LessThan3Months->value ? 'x' : '',
                'employed_since_3_to_6_months' => $item->employed_since?->value === EmployedSince::From3To6Months->value ? 'x' : '',
                'employed_since_6_to_12_months' => $item->employed_since?->value === EmployedSince::From6To12Months->value ? 'x' : '',
                'employed_since_more_than_12_months' => $item->employed_since?->value === EmployedSince::MoreThan12Months->value ? 'x' : '',
                'level_knowledge_acquired_full' => $item->level_knowledge_acquired?->value === LevelKnowledgeAcquired::Full->value ? 'x' : '',
                'level_knowledge_acquired_partial' => $item->level_knowledge_acquired?->value === LevelKnowledgeAcquired::Partial->value ? 'x' : '',
                'level_knowledge_acquired_not' => $item->level_knowledge_acquired?->value === LevelKnowledgeAcquired::None->value ? 'x' : '',
                'last_salary' => $item->starting_salary,
                'average_income_less_5_million' => $item->average_income?->value === AverageIncome::LessThan5Million->value ? 'x' : '',
                'average_income_5_to_10_million' => $item->average_income?->value === AverageIncome::From5To10Million->value ? 'x' : '',
                'average_income_10_to_15_million' => $item->average_income?->value === AverageIncome::From10To15Million->value ? 'x' : '',
                'average_income_than_15_million' => $item->average_income?->value === AverageIncome::MoreThan15Million->value ? 'x' : '',
                'job_search_method_by_academy' => in_array(JobSearchMethod::Academy->value, Arr::get($item->job_search_method, 'value') ?? []) ? 'x' : '',
                'job_search_method_by_friend' => in_array(JobSearchMethod::Friend_And_Relative->value, Arr::get($item->job_search_method, 'value') ?? []) ? 'x' : '',
                'job_search_method_by_self' => in_array(JobSearchMethod::Self->value, Arr::get($item->job_search_method, 'value') ?? []) ? 'x' : '',
                'job_search_method_by_create_self' => in_array(JobSearchMethod::Create->value, Arr::get($item->job_search_method, 'value') ?? []) ? 'x' : '',
                'job_search_method_other' => in_array(JobSearchMethod::Other->value, Arr::get($item->job_search_method, 'value') ?? []) ? 'x' : '',
                'recruitment_type_exam' => in_array(RecruitmentType::Exam->value, Arr::get($item->recruitment_type, 'value') ?? []) ? 'x' : '',
                'recruitment_type_contract' => in_array(RecruitmentType::Contract->value, Arr::get($item->recruitment_type, 'value') ?? []) ? 'x' : '',
                'recruitment_type_mobilized' => in_array(RecruitmentType::Mobilized->value, Arr::get($item->recruitment_type, 'value') ?? []) ? 'x' : '',
                'recruitment_type_recruitment' => in_array(RecruitmentType::Recruitment->value, Arr::get($item->recruitment_type, 'value') ?? []) ? 'x' : '',
                'recruitment_type_seconded' => in_array(RecruitmentType::Seconded->value, Arr::get($item->recruitment_type, 'value') ?? []) ? 'x' : '',
                'recruitment_type_other' => in_array(RecruitmentType::Other->value, Arr::get($item->recruitment_type, 'value') ?? []) ? 'x' : '',
                'soft_skill_communication' => in_array(SoftSkillsRequired::CommunicationSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'soft_skill_presenting' => in_array(SoftSkillsRequired::PresentationSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'soft_skill_teamwork' => in_array(SoftSkillsRequired::TeamworkSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'soft_skill_report_writing' => in_array(SoftSkillsRequired::ReportWritingSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'soft_skill_leadership' => in_array(SoftSkillsRequired::LeadershipSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'soft_skill_english' => in_array(SoftSkillsRequired::EnglishSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'soft_skill_it' => in_array(SoftSkillsRequired::ComputerSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'soft_skill_international' => in_array(SoftSkillsRequired::InternationalIntegrationSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'soft_skill_other' => in_array(SoftSkillsRequired::OtherSkills->value, Arr::get($item->soft_skills_required, 'value') ?? []) ? 'x' : '',
                'course_improve' => in_array(MustAttendedCourses::ImproveProfessionalKnowledge->value, Arr::get($item->must_attended_courses, 'value') ?? []) ? 'x' : '',
                'course_professional' => in_array(MustAttendedCourses::ImproveProfessionalSkills->value, Arr::get($item->must_attended_courses, 'value') ?? []) ? 'x' : '',
                'course_it' => in_array(MustAttendedCourses::ImproveInformationTechnologySkills->value, Arr::get($item->must_attended_courses, 'value') ?? []) ? 'x' : '',
                'course_foreign_language' => in_array(MustAttendedCourses::ImproveForeignLanguageSkills->value, Arr::get($item->must_attended_courses, 'value') ?? []) ? 'x' : '',
                'course_management' => in_array(MustAttendedCourses::DevelopManagementSkills->value, Arr::get($item->must_attended_courses, 'value') ?? []) ? 'x' : '',
                'course_studying' => in_array(MustAttendedCourses::ContinueStudyingToHigherDegrees->value, Arr::get($item->must_attended_courses, 'value') ?? []) ? 'x' : '',
                'solution_academy' => in_array(SolutionsGetJob::AcademyOrganizesJobSharing->value, Arr::get($item->solutions_get_job, 'value') ?? []) ? 'x' : '',
                'solution_job_exchange' => in_array(SolutionsGetJob::AcademyOrganizesJobExchange->value, Arr::get($item->solutions_get_job, 'value') ?? []) ? 'x' : '',
                'solution_training' => in_array(SolutionsGetJob::AcademyOrganizesJobExchange->value, Arr::get($item->solutions_get_job, 'value') ?? []) ? 'x' : '',
                'solution_program_update' => in_array(SolutionsGetJob::TrainingProgramsUpdated->value, Arr::get($item->solutions_get_job, 'value') ?? []) ? 'x' : '',
                'solution_activities' => in_array(SolutionsGetJob::EnhancePracticeActivities->value, Arr::get($item->solutions_get_job, 'value') ?? []) ? 'x' : '',
                'solution_other' => in_array(SolutionsGetJob::OtherSolutions->value, Arr::get($item->solutions_get_job, 'value') ?? []) ? 'x' : '',

            ];
        })->toArray();

        return $this->exportErrorRecord($dataTransfer, $employmentSurveyResponses->first()?->trainingIndustry->faculty);
    }

    public function exportErrorRecord(array $data, ?Faculty $faculty, $filename = 'error_record.xlsx'): StreamedResponse
    {
        // Load from xlsx template
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(public_path() . '/template/reports/mau_03_danh_sach_sinh_vien_phan_hoi.xlsx');

        $spreadsheet->getDefaultStyle()->getFont()->getName('Times New Roman');
        // loop data to fill in the template
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', Str::upper($faculty?->name ?? ''));

        $row = 9; //Start row data = 2
        foreach ($data as $dataRow) {
            $sheet->fromArray([
                'index' => $row - 8,
                ...$dataRow,
            ], null, 'A' . $row);
            $row++;
        }

        // Đặt viền cho tiêu đề
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => Color::COLOR_BLACK],
                ],
            ],
        ];

        // Áp dụng viền cho tiêu đề
        $sheet->getStyle('A9:BK9')->applyFromArray($styleArray);

        // Áp dụng viền cho toàn bộ bảng
        $sheet->getStyle('A9:BK' . ($row - 1))->applyFromArray($styleArray);
        $sheet->getStyle('A9:BK' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        return new StreamedResponse(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
    }
}
