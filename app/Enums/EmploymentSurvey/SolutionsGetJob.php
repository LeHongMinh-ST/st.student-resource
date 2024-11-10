<?php

declare(strict_types=1);

namespace App\Enums\EmploymentSurvey;

enum SolutionsGetJob: int
{
    // Học viện tổ chức các buổi trao đổi, chia sẻ kinh nghiệm tìm kiếm việc làm giữa cựu sinh viên với sinh viên
    case AcademyOrganizesJobSharing = 1;
    // Học viện tổ chức các buổi trao đổi giữa đơn vị sử dụng lao động với sinh viên
    case AcademyOrganizesJobExchange = 2;
    // Đơn vị sử dụng lao động tham gia vào quá trình đào tạo
    case EmployersParticipateTraining = 3;
    // Chương trình đào tạo được điều chỉnh và cập nhật theo nhu cầu của thị trường lao động
    case TrainingProgramsUpdated = 4;
    // Tăng cường các hoạt động thực hành và chuyên môn tại cơ sở
    case EnhancePracticeActivities = 5;
    // Các giải pháp khác
    case OtherSolutions = 99;
}
