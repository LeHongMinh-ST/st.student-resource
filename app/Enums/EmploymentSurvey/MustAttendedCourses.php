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
}
