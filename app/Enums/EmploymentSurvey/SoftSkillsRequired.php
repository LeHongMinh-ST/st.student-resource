<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum SoftSkillsRequired: int
{
    // Kỹ năng giao tiếp
    case CommunicationSkills = 1;
    // Kỹ năng lãnh đạo
    case LeadershipSkills = 2;
    // Kỹ năng thuyết trình
    case PresentationSkills = 3;
    // Kỹ năng tiếng Anh
    case EnglishSkills = 4;
    // Kỹ năng làm việc nhóm
    case TeamworkSkills = 5;
    // Kỹ năng tin học
    case ComputerSkills = 6;
    // Kỹ năng viết báo cáo tài liệu
    case ReportWritingSkills = 7;
    // Kỹ năng hội nhập quốc tế
    case InternationalIntegrationSkills = 8;
    // Kỹ năng khác
    case OtherSkills = 0;

}
