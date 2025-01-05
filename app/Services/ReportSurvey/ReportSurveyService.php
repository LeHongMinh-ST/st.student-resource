<?php

declare(strict_types=1);

namespace App\Services\ReportSurvey;

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
use App\Models\Cities;
use App\Models\EmploymentSurveyResponse;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\TrainingIndustry;
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
    public function downloadReportEmploymentSurveyTemplateOne(mixed $surveyId): StreamedResponse
    {
        $dataReport = $this->getReportEmploymentSurveyTemplateOne($surveyId);

        return $this->exportTemplateOne($dataReport['data'], $dataReport['faculty']);
    }

    public function getReportEmploymentSurveyTemplateOne(mixed $surveyId): array
    {
        $data = EmploymentSurveyResponse::selectRaw('
        training_industry_id' .
            ", COUNT(*) as 'total_gender', SUM(if (gender = '" . Gender::Female->value . "', 1, 0)) as 'total_female',
        sum(if (trained_field = " . TrainedField::RightTraining->value . ", 1, 0)) as 'right_training',
        sum(if (trained_field = " . TrainedField::RelatedTraining->value . ", 1, 0)) as 'relation_training',
        sum(if (trained_field = " . TrainedField::NotRelatedTraining->value . ", 1, 0)) as 'not_relation_training',
        sum(if (employment_status = " . EmploymentStatus::ContinuingEducation->value . ", 1, 0)) as 'continue_education',
        sum(if (employment_status = " . EmploymentStatus::Unemployed->value . ", 1, 0)) as 'total_unemployment',
        sum(if (work_area = " . WorkArea::StateSector->value . ", 1, 0)) as 'total_work_area_state',
        sum(if (work_area = " . WorkArea::PrivateSector->value . ", 1, 0)) as 'total_work_area_private',
        sum(if (work_area = " . WorkArea::SelfEmployment->value . ", 1, 0)) as 'total_work_area_self',
        sum(if (work_area = " . WorkArea::ElementForeignSector->value . ", 1, 0)) as 'total_work_are_foreign',
            GROUP_CONCAT(DISTINCT city_work_id SEPARATOR ',') AS 'work_cities'
        ")->where('survey_period_id', $surveyId)->groupBy('training_industry_id')->get();

        $citiesId = collect($data->whereNotNull('work_cities')->pluck('work_cities')->toArray())->map(fn ($item) => explode(',', $item ?? ''))->flatten()->unique()->toArray();
        $citiesCode = Cities::select(['id', 'code'])->whereIn('id', $citiesId)->get();

        $trainingIndustrySurveys = TrainingIndustry::select([
            'id',
            'code',
            'name',
        ])->whereHas('students', fn ($q) => $q->whereHas('surveyPeriods', fn ($q) => $q->where('survey_period_id', $surveyId)))->withCount([
            'students as total_student' => fn ($q) => $q->whereHas('surveyPeriods', fn ($q) => $q->where('survey_period_id', $surveyId)),
            'students as total_student_female' => fn ($q) => $q->whereHas('info', fn ($q) => $q->where('gender', Gender::Female->value))
                ->whereHas('surveyPeriods', fn ($q) => $q->where('survey_period_id', $surveyId)),
        ])->get();

        $faculty = Faculty::whereHas('trainingIndustries', function ($q) use ($trainingIndustrySurveys): void {
            $q->whereIn('id', $trainingIndustrySurveys->pluck('id'));
        })->first();

        $dataTransfer = $trainingIndustrySurveys->map(function ($item) use ($data, $citiesCode): array {
            $trainingIndustrySurveyData = $data->where('training_industry_id', $item->id)->first();
            $cities = collect(explode(',', $trainingIndustrySurveyData->work_cities ?? ''))->map(fn ($item) => $citiesCode->where('id', $item)->first()?->code)->filter()->toArray();

            $dataTransform = $item->toArray();

            // tỉ lệ sinh viên có việc làm / phản hồi
            $rateTotalEmploymentWithTotalResponse = $trainingIndustrySurveyData->total_gender ? (
                (int) $trainingIndustrySurveyData->right_training
                + (int) $trainingIndustrySurveyData->relation_training
                + (int) $trainingIndustrySurveyData->not_relation_training
                + (int) $trainingIndustrySurveyData->continue_education
            ) / (int) $trainingIndustrySurveyData->total_gender : 0;

            // tỉ lệ sinh viên có việc làm / tổng sinh viên
            $rateTotalEmploymentWithTotalStudent = $item->total_student ? (
                (int) $trainingIndustrySurveyData->right_training
                + (int) $trainingIndustrySurveyData->relation_training
                + (int) $trainingIndustrySurveyData->not_relation_training
                + (int) $trainingIndustrySurveyData->continue_education
            ) / (int) $item->total_student : 0;

            $dataTransform = [
                ...$dataTransform,
                ...Arr::except($trainingIndustrySurveyData->toArray(), [
                    'total_work_area_state',
                    'total_work_area_private',
                    'total_work_area_self',
                    'total_work_are_foreign',
                ]),
                'rate_total_employment_with_total_response' => round($rateTotalEmploymentWithTotalResponse * 100, 2) . '%',
                'rate_total_employment_with_total_student' => round($rateTotalEmploymentWithTotalStudent * 100, 2) . '%',
                ...Arr::only($trainingIndustrySurveyData->toArray(), [
                    'total_work_area_state',
                    'total_work_area_private',
                    'total_work_area_self',
                    'total_work_are_foreign',
                ]),
                'work_code_cities' => implode(', ', $cities),
            ];

            $dataTransform['total_female'] = (string) $dataTransform['total_female'];
            $dataTransform['total_student_female'] = (string) $dataTransform['total_student_female'];

            unset($dataTransform['id'], $dataTransform['training_industry_id'], $dataTransform['work_cities']);

            return $dataTransform;
        });

        return [
            'data' => $dataTransfer->toArray(),
            'faculty' => $faculty,
        ];
    }

    public function getDatReportEmploymentSurveyTemplateOne(mixed $surveyId): array
    {
        $data = $this->getReportEmploymentSurveyTemplateOne($surveyId)['data'];
        return collect($data)->map(function ($item) {
            $listCodeCity = explode(', ', Arr::get($item, 'work_code_cities'));
            $cities = Cities::select(['id', 'code', 'name'])->whereIn('code', $listCodeCity)->get();
            $citiesName = implode(', ', $cities->pluck('name')->toArray());
            $item['work_code_cities'] = $citiesName;
            return $item;
        })->toArray();
    }

    public function getReportEmploymentSurveyTemplateThree(mixed $surveyId): array
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
                'work_area_state' => $item->work_area?->value === WorkArea::StateSector->value ? 'x' : '',
                'work_area_private' => $item->work_area?->value === WorkArea::PrivateSector->value ? 'x' : '',
                'work_area_self' => $item->work_area?->value === WorkArea::SelfEmployment->value ? 'x' : '',
                'work_area_foreign' => $item->work_area?->value === WorkArea::ElementForeignSector->value ? 'x' : '',
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

        return [
            'data' => $dataTransfer,
            'faculty' => $employmentSurveyResponses->first()?->trainingIndustry->faculty,
        ];
    }

    public function getReportEmploymentSurveyTemplateTwo(mixed $surveyId): array
    {
        $studentList = Student::with(['info', 'trainingIndustry.faculty', 'currentGraduationCeremony', 'employmentSurveyResponses'])
            ->whereHas('surveyPeriods', fn ($q) => $q->where('survey_period_id', $surveyId))
            ->get();

        // transfer data
        $data = $studentList->map(function ($item) use ($surveyId) {
            $employmentSurveyResponse = $item->employmentSurveyResponses->where('survey_period_id', $surveyId)->first();
            return [
                'student_code' => $item->code,
                'full_name' => $item->full_name,
                'gender_female' => Gender::Female->value === $item->info->gender ? '1' : '',
                'identification_card_number' => $item->info->citizen_identification,
                'training_industry_code' => $item->trainingIndustry->code,
                'certification' => $item->currentGraduationCeremony->certification,
                'certification_date' => $item->currentGraduationCeremony->certification_date?->format('d/m/Y'),
                'phone_number' => $item->info->phone,
                'email' => $item->info->person_email,
                'type_survey' => null !== $employmentSurveyResponse ? 'Online' : '',
                'status_survey' => null !== $employmentSurveyResponse ? 'x' : '',
                'training_industry_name' => $item->trainingIndustry->name,
                'faculty_name' => $item->trainingIndustry->faculty->name,
            ];
        })->toArray();

        return [
            'data' => $data,
            'faculty' => $studentList->first()?->trainingIndustry->faculty,
        ];
    }

    public function downloadReportEmploymentSurveyTemplateTwo(mixed $surveyId): StreamedResponse
    {
        // transfer data
        $dataReport = $this->getReportEmploymentSurveyTemplateTwo($surveyId);

        return $this->exportTemplateTwo($dataReport['data'], $dataReport['faculty']);
    }

    public function downloadReportEmploymentSurveyTemplateThree(mixed $surveyId): StreamedResponse
    {
        $dataReport = $this->getReportEmploymentSurveyTemplateThree($surveyId);

        return $this->exportTemplateThree($dataReport['data'], $dataReport['faculty']);
    }

    public function exportTemplateThree(array $data, ?Faculty $faculty, $filename = 'error_record.xlsx'): StreamedResponse
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

    public function exportTemplateOne(array $data, ?Faculty $faculty, $filename = 'mau_01_danh_sach_sinh_vien_phan_hoi.xlsx'): StreamedResponse
    {
        // Load from xlsx template
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(public_path() . '/template/reports/mau_01_danh_sach_sinh_vien_phan_hoi.xlsx');

        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(12);
        // loop data to fill in the template
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A2', Str::upper($faculty?->name ?? ''));

        $row = 9;
        foreach ($data as $dataRow) {
            $sheet->fromArray([
                'index' => $row - 8,
                ...$dataRow,
            ], null, 'A' . $row);
            $row++;
        }
        $sheet->getColumnDimension('S')->setAutoSize(true);

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
        $sheet->getStyle('A9:S9')->applyFromArray($styleArray);

        // Áp dụng viền cho toàn bộ bảng
        $sheet->getStyle('A9:S' . ($row - 1))->applyFromArray($styleArray);
        $sheet->getStyle('A9:S' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Kết hợp ô từ P13 đến S13
        $sheet->mergeCells('P' . ($row + 2) . ':S' . ($row + 2));
        $sheet->mergeCells('P' . ($row + 3) . ':S' . ($row + 3));

        // Đặt giá trị cho ô đã merge
        $sheet->setCellValue('P' . ($row + 2), 'Hà Nội, ngày ….. tháng …. năm 2024');
        $sheet->setCellValue('P' . ($row + 3), 'Trưởng khoa');

        // Căn giữa nội dung
        $sheet->getStyle('P' . ($row + 2))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('P' . ($row + 3))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Đặt chữ in đậm
        $sheet->getStyle('P' . ($row + 3))->getFont()->setBold(true);

        return new StreamedResponse(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
    }

    public function exportTemplateTwo(array $data, ?Faculty $faculty, $filename = 'mau_02_danh_sach_sinh_vien_phan_hoi.xlsx'): StreamedResponse
    {
        // Load from xlsx template
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load(public_path() . '/template/reports/mau_02_danh_sach_sinh_vien_tot_nghiep.xlsx');

        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');
        // loop data to fill in the template
        $sheet = $spreadsheet->getActiveSheet();

        $row = 5;
        foreach ($data as $dataRow) {
            $sheet->fromArray([
                'index' => $row - 4,
                ...$dataRow,
            ], null, 'A' . $row);
            $row++;
        }
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

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
        $sheet->getStyle('A5:N5')->applyFromArray($styleArray);

        // Áp dụng viền cho toàn bộ bảng
        $sheet->getStyle('A5:N' . ($row - 1))->applyFromArray($styleArray);
        $sheet->getStyle('A5:N' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5:C' . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        return new StreamedResponse(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
    }
}
