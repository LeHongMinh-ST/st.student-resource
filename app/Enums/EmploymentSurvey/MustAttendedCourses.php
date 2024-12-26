<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum MustAttendedCourses: int
{
    // Nâng cao kiến thức chuyên môn
    case ImproveProfessionalKnowledge = 1;
    // Nâng cao kỹ năng chuyên môn nghiệp vụ
    case ImproveProfessionalSkills = 2;
    // Nâng cao kỹ năng về công nghệ thông tin
    case ImproveInformationTechnologySkills = 3;
    // Nâng cao kỹ năng ngoại ngữ
    case ImproveForeignLanguageSkills = 4;
    // Phát triển kỹ năng quản lý
    case DevelopManagementSkills = 5;
    // Tiếp tục học lên cao lên trình độ thạc sỹ, tiến sỹ
    case ContinueStudyingToHigherDegrees = 6;
    // Các khóa học khác
    case OtherCourses = 0;

    public function getName(): string
    {
        return match ($this) {
            self::ImproveProfessionalKnowledge => 'Nâng cao kiến thức chuyên môn',
            self::ImproveProfessionalSkills => 'Nâng cao kỹ năng chuyên môn nghiệp vụ',
            self::ImproveInformationTechnologySkills => 'Nâng cao kỹ năng về công nghệ thông tin',
            self::ImproveForeignLanguageSkills => 'Nâng cao kỹ năng ngoại ngữ',
            self::DevelopManagementSkills => 'Phát triển kỹ năng quản lý',
            self::ContinueStudyingToHigherDegrees => 'Tiếp tục học lên cao',
            self::OtherCourses => 'Các khóa học khác',
        };
    }
}
