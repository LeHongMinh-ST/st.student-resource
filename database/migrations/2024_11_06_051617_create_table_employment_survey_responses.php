<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employment_survey_responses', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('survey_period_id')->index();
            $table->unsignedBigInteger('student_id')->index()->nullable();
            $table->string('email');
            $table->string('full_name');
            $table->date('dob');
            $table->string('gender'); // nam, nu, khac
            $table->string('code_student');
            $table->string('identification_card_number');
            $table->string('identification_issuance_place');
            $table->date('identification_issuance_date');
            $table->unsignedBigInteger('training_industry_id')->index();
            $table->unsignedBigInteger('admission_year_id')->index();
            $table->string('phone_number');
            $table->unsignedInteger('employment_status'); // 1: employed, 2: unemployed, 3: continue studying, 4: not looking job
            $table->string('recruit_partner_name')->nullable();
            $table->string('recruit_partner_address')->nullable(); // Địa chỉ đơn vị tuyển dụng (tinh/thành phố - full address)
            $table->date('recruit_partner_date')->nullable(); // Ngày tuyển dụng
            $table->string('recruit_partner_position')->nullable(); // Chức vụ
            // Noi dung khao sat
            $table->string('work_area')->nullable(); // Khu vực làm việc - 1. public sector, 2. private sector, 3. international organization, 4. self-employed
            // Có việc làm từ khi nào
            $table->unsignedInteger('employed_since')->nullable(); // duoi 3 thang, 3-6 thang, 6-12 thang, tren 12 thang
            // mứd độ phù hợp với ngành đào tạo
            $table->unsignedInteger('trained_field')->nullable(); // 1. đung ngaành đào tạo, 2. lien quan den nganh 3. không lien quan ngành đào tạo
            $table->unsignedInteger('professional_qualification_field')->nullable();
            // muc do hoc duoc kiến thức, kỹ năng cần thiết cho công việc
            $table->unsignedInteger('level_knowledge_acquired')->nullable(); // 1. dã học duoc, 2. khong hoc duoc, 3. chi hoc duoc 1 phan
            // muc luong khoi diem khi moi nhận việc
            $table->unsignedBigInteger('starting_salary')->nullable(); // x trieu
            // muc thu nhap binh quan/thang
            $table->unsignedInteger('average_income')->nullable(); // 1. duoi 5 trieu, 2. 5-10 trieu, 3. 10-15 trieu, 4. tren 15 trieu
            $table->json('recruitment_type')->nullable();
            // tim viec lam theo hinh thuc nao
            $table->json('job_search_method')->nullable(); // 1. truc tuyen, 2. qua trung tam gioi thieu viec lam, 3. qua quen biet, 4. qua truong, 5. qua bao, 6. qua cong ty
            // ky nang mem can co
            $table->json('soft_skills_required')->nullable();
            // cac khoa hoc phai tham ra de nang cao ky nang
            $table->json('must_attended_courses')->nullable();
            // cac giai phap de co viec lam
            $table->json('solutions_get_job')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_survey_responses');
    }
};
