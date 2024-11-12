<?php

declare(strict_types=1);

namespace App\DTO\EmploymentSurveyResponse;

use App\Enums\EmploymentSurvey\AverageIncome;
use App\Enums\EmploymentSurvey\EmployedSince;
use App\Enums\EmploymentSurvey\EmploymentStatus;
use App\Enums\EmploymentSurvey\LevelKnowledgeAcquired;
use App\Enums\EmploymentSurvey\ProfessionalQualificationField;
use App\Enums\EmploymentSurvey\TrainedField;
use App\Enums\EmploymentSurvey\WorkArea;
use App\Enums\Gender;
use DateTime;

class CreateEmploymentSurveyResponseDTO
{
    private int $surveyPeriodId;

    private string $fullName;

    private ?int $studentId;

    private string $email;

    private DateTime $dob;

    private Gender $gender;

    private string $codeStudent;

    private string $identificationCardNumber;

    private ?string $identificationCardNumberUpdate;

    private string $identificationIssuancePlace;

    private DateTime $identificationIssuanceDate;

    private int $trainingIndustryId;

    private string $phoneNumber;

    private int $admissionYearId;

    private EmploymentStatus $employmentStatus;

    private ?string $recruitPartnerName;

    private ?string $recruitPartnerAddress;

    private ?DateTime $recruitPartnerDate;

    private ?string $recruitPartnerPosition;

    private ?WorkArea $workArea;

    private ?EmployedSince $employedSince;

    private ?TrainedField $trainedField;

    private ?ProfessionalQualificationField $professionalQualificationField;

    private ?LevelKnowledgeAcquired $levelKnowledgeAcquired;

    private ?int $startingSalary;

    private ?AverageIncome $averageIncome;

    private ?ResponseOther $jobSearchMethod;

    private ?ResponseOther $recruitmentType;

    private ?ResponseOther $softSkillsRequired;

    private ?ResponseOther $mustAttendedCourses;

    private ?ResponseOther $solutionsGetJob;

    public function __construct()
    {
        $this->studentId = null;
        $this->recruitPartnerName = null;
        $this->recruitPartnerAddress = null;
        $this->recruitPartnerDate = null;
        $this->recruitPartnerPosition = null;
        $this->workArea = null;
        $this->employedSince = null;
        $this->professionalQualificationField = null;
        $this->levelKnowledgeAcquired = null;
        $this->startingSalary = null;
        $this->averageIncome = null;
        $this->jobSearchMethod = null;
        $this->recruitmentType = null;
        $this->softSkillsRequired = null;
        $this->mustAttendedCourses = null;
        $this->trainedField = null;
        $this->solutionsGetJob = null;
        $this->identificationCardNumberUpdate = null;
    }

    public function getIdentificationCardNumberUpdate(): ?string
    {
        return $this->identificationCardNumberUpdate;
    }

    public function setIdentificationCardNumberUpdate(?string $identificationCardNumberUpdate): void
    {
        $this->identificationCardNumberUpdate = $identificationCardNumberUpdate;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function getSurveyPeriodId(): int
    {
        return $this->surveyPeriodId;
    }

    public function setSurveyPeriodId(int $surveyPeriodId): void
    {
        $this->surveyPeriodId = $surveyPeriodId;
    }

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(?int $studentId): void
    {
        $this->studentId = $studentId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDob(): DateTime
    {
        return $this->dob;
    }

    public function setDob(DateTime $dob): void
    {
        $this->dob = $dob;
    }

    public function getGender(): Gender
    {
        return $this->gender;
    }

    public function setGender(Gender $gender): void
    {
        $this->gender = $gender;
    }

    public function getCodeStudent(): string
    {
        return $this->codeStudent;
    }

    public function setCodeStudent(string $codeStudent): void
    {
        $this->codeStudent = $codeStudent;
    }

    public function getIdentificationCardNumber(): string
    {
        return $this->identificationCardNumber;
    }

    public function setIdentificationCardNumber(string $identificationCardNumber): void
    {
        $this->identificationCardNumber = $identificationCardNumber;
    }

    public function getIdentificationIssuancePlace(): string
    {
        return $this->identificationIssuancePlace;
    }

    public function setIdentificationIssuancePlace(string $identificationIssuancePlace): void
    {
        $this->identificationIssuancePlace = $identificationIssuancePlace;
    }

    public function getIdentificationIssuanceDate(): DateTime
    {
        return $this->identificationIssuanceDate;
    }

    public function setIdentificationIssuanceDate(DateTime $identificationIssuanceDate): void
    {
        $this->identificationIssuanceDate = $identificationIssuanceDate;
    }

    public function getTrainingIndustryId(): int
    {
        return $this->trainingIndustryId;
    }

    public function setTrainingIndustryId(int $trainingIndustryId): void
    {
        $this->trainingIndustryId = $trainingIndustryId;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAdmissionYearId(): int
    {
        return $this->admissionYearId;
    }

    public function setAdmissionYearId(int $admissionYearId): void
    {
        $this->admissionYearId = $admissionYearId;
    }

    public function getEmploymentStatus(): EmploymentStatus
    {
        return $this->employmentStatus;
    }

    public function setEmploymentStatus(EmploymentStatus $employmentStatus): void
    {
        $this->employmentStatus = $employmentStatus;
    }

    public function getRecruitPartnerName(): ?string
    {
        return $this->recruitPartnerName;
    }

    public function setRecruitPartnerName(?string $recruitPartnerName): void
    {
        $this->recruitPartnerName = $recruitPartnerName;
    }

    public function getRecruitPartnerAddress(): ?string
    {
        return $this->recruitPartnerAddress;
    }

    public function setRecruitPartnerAddress(?string $recruitPartnerAddress): void
    {
        $this->recruitPartnerAddress = $recruitPartnerAddress;
    }

    public function getRecruitPartnerDate(): ?DateTime
    {
        return $this->recruitPartnerDate;
    }

    public function setRecruitPartnerDate(?DateTime $recruitPartnerDate): void
    {
        $this->recruitPartnerDate = $recruitPartnerDate;
    }

    public function getRecruitPartnerPosition(): ?string
    {
        return $this->recruitPartnerPosition;
    }

    public function setRecruitPartnerPosition(?string $recruitPartnerPosition): void
    {
        $this->recruitPartnerPosition = $recruitPartnerPosition;
    }

    public function getWorkArea(): ?WorkArea
    {
        return $this->workArea;
    }

    public function setWorkArea(?WorkArea $workArea): void
    {
        $this->workArea = $workArea;
    }

    public function getEmployedSince(): ?EmployedSince
    {
        return $this->employedSince;
    }

    public function setEmployedSince(?EmployedSince $employedSince): void
    {
        $this->employedSince = $employedSince;
    }

    public function getTrainedField(): ?TrainedField
    {
        return $this->trainedField;
    }

    public function setTrainedField(?TrainedField $trainedField): void
    {
        $this->trainedField = $trainedField;
    }

    public function getProfessionalQualificationField(): ?ProfessionalQualificationField
    {
        return $this->professionalQualificationField;
    }

    public function setProfessionalQualificationField(?ProfessionalQualificationField $professionalQualificationField): void
    {
        $this->professionalQualificationField = $professionalQualificationField;
    }

    public function getLevelKnowledgeAcquired(): ?LevelKnowledgeAcquired
    {
        return $this->levelKnowledgeAcquired;
    }

    public function setLevelKnowledgeAcquired(?LevelKnowledgeAcquired $levelKnowledgeAcquired): void
    {
        $this->levelKnowledgeAcquired = $levelKnowledgeAcquired;
    }

    public function getStartingSalary(): ?int
    {
        return $this->startingSalary;
    }

    public function setStartingSalary(?int $startingSalary): void
    {
        $this->startingSalary = $startingSalary;
    }

    public function getAverageIncome(): ?AverageIncome
    {
        return $this->averageIncome;
    }

    public function setAverageIncome(?AverageIncome $averageIncome): void
    {
        $this->averageIncome = $averageIncome;
    }

    public function getJobSearchMethod(): ?ResponseOther
    {
        return $this->jobSearchMethod;
    }

    public function setJobSearchMethod(?ResponseOther $jobSearchMethod): void
    {
        $this->jobSearchMethod = $jobSearchMethod;
    }

    public function getRecruitmentType(): ?ResponseOther
    {
        return $this->recruitmentType;
    }

    public function setRecruitmentType(?ResponseOther $recruitmentType): void
    {
        $this->recruitmentType = $recruitmentType;
    }

    public function getSoftSkillsRequired(): ?ResponseOther
    {
        return $this->softSkillsRequired;
    }

    public function setSoftSkillsRequired(?ResponseOther $softSkillsRequired): void
    {
        $this->softSkillsRequired = $softSkillsRequired;
    }

    public function getMustAttendedCourses(): ?ResponseOther
    {
        return $this->mustAttendedCourses;
    }

    public function setMustAttendedCourses(?ResponseOther $mustAttendedCourses): void
    {
        $this->mustAttendedCourses = $mustAttendedCourses;
    }

    public function getSolutionsGetJob(): ?ResponseOther
    {
        return $this->solutionsGetJob;
    }

    public function setSolutionsGetJob(?ResponseOther $solutionsGetJob): void
    {
        $this->solutionsGetJob = $solutionsGetJob;
    }

    public function toArray(): array
    {
        return [
            'survey_period_id' => $this->getSurveyPeriodId(),
            'student_id' => $this->getStudentId(),
            'email' => $this->getEmail(),
            'dob' => $this->getDob(),
            'gender' => $this->getGender(),
            'code_student' => $this->getCodeStudent(),
            'full_name' => $this->getFullName(),
            'identification_card_number' => $this->getIdentificationCardNumber(),
            'identification_issuance_place' => $this->getIdentificationIssuancePlace(),
            'identification_issuance_date' => $this->getIdentificationIssuanceDate(),
            'training_industry_id' => $this->getTrainingIndustryId(),
            'phone_number' => $this->getPhoneNumber(),
            'admission_year_id' => $this->getAdmissionYearId(),
            'employment_status' => $this->getEmploymentStatus(),
            'recruit_partner_name' => $this->getRecruitPartnerName(),
            'recruit_partner_address' => $this->getRecruitPartnerAddress(),
            'recruit_partner_date' => $this->getRecruitPartnerDate(),
            'recruit_partner_position' => $this->getRecruitPartnerPosition(),
            'work_area' => $this->getWorkArea(),
            'employed_since' => $this->getEmployedSince(),
            'trained_field' => $this->getTrainedField(),
            'professional_qualification_field' => $this->getProfessionalQualificationField(),
            'level_knowledge_acquired' => $this->getLevelKnowledgeAcquired(),
            'starting_salary' => $this->getStartingSalary(),
            'average_income' => $this->getAverageIncome(),
            'job_search_method' => $this->getJobSearchMethod()?->toString(),
            'recruitment_type' => $this->getRecruitmentType()?->toString(),
            'soft_skills_required' => $this->getSoftSkillsRequired()?->toString(),
            'must_attended_courses' => $this->getMustAttendedCourses()?->toString(),
            'solutions_get_job' => $this->getSolutionsGetJob()?->toString(),
        ];
    }
}
