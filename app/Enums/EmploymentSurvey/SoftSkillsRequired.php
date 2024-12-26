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

    public function getName()
    {
        return match ($this) {
            self::CommunicationSkills => 'Kỹ năng giao tiếp',
            self::LeadershipSkills => 'Kỹ năng lãnh đạo',
            self::PresentationSkills => 'Kỹ năng thuyết trình',
            self::EnglishSkills => 'Kỹ năng tiếng Anh',
            self::TeamworkSkills => 'Kỹ năng làm việc nhóm',
            self::ComputerSkills => 'Kỹ năng tin học',
            self::ReportWritingSkills => 'Kỹ năng viết báo cáo tài liệu',
            self::InternationalIntegrationSkills => 'Kỹ năng hội nhập quốc tế',
            self::OtherSkills => 'Kỹ năng khác',
        };
    }

}
