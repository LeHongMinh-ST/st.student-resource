<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Khảo Sát Tình Hình Việc Làm Của Sinh Viên Tốt Nghiệp</title>


    <style>
        .container-survey {
            /*background-color: #e3edfb;*/
            font-family: 'DejaVu Sans', sans-serif; /* Sử dụng font Roboto */
        }

        .row {
            background-color: #fff;
            padding: 10px;
            /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
            /*border-radius: 0.25rem;*/
            /*margin: 20px 0;*/
        }

        h1 {
            font-size: 14px;
            margin: 0;
        }

        h2 {
            font-size: 13px;
            margin: 0;
        }

        h3 {
            font-size: 12px;
            margin: 0;
        }

        h6, h5, h4 {
            font-size: 10.5px;
            margin: 0;
        }

        p {
            font-size: 10px;,
        margin: 0;
            padding: 0;
            margin-bottom: 0 !important;
        }

        label {
            font-weight: 450;
            font-size: 10px;,
        margin: 0;
            padding: 0;
            margin-bottom: 0 !important;
        }

        input[type="text"] {
            font-size: 12px !important;
        }

        .custom-input {
            border-bottom: 1px solid #ced4da;
            border-top: none;
            border-left: none;
            border-right: none;
            border-radius: unset;
            width: 100%;
            font-weight: bold;
        }

        .checkbox-custom {
            margin: 0.375rem 0.75rem;
        }

        .content {
            max-width: 800px;
        }

        .page-break {
            page-break-after: always;
        }

        .mb-2 {
            margin-bottom: 12px !important;
        }


    </style>
    {{--    <link href="{{ asset('assets/css/response-survey.css') }}" id="stylesheet" rel="stylesheet" type="text/css">--}}
</head>
<body>
<main class="container-survey">
    <div class="container content" style="margin: 0 auto">
        <div class="header">
            <div class="row">
                <img src="data:image/png;base64,{{base64_encode(file_get_contents(asset('assets/images/logo-vnua.png'))) }}" alt="logo" style="width: 21%; object-fit: cover;
                        padding-left: 20px;
                    "/>
                <div class="col-6" style="float: right; width: 50%;">
                    <p class="text-end fw-light pb-4" style="text-align: right; opacity: 0.7; padding-bottom: 10px">Ngày {{\Carbon\Carbon::now()->format('d/m/Y')}}</p>
                    <div style="text-align: center;">
                        <h3 class="text-center">BỘ NÔNG NGHIỆP</h3>
                        <h3 class="text-center">VÀ PHÁT TRIỂN NÔNG THÔN</h3>
                        <h3 class="text-center" style="padding-bottom: 0;">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h3>
                        <p class="text-center">Thị trấn Trâu Quỳ, huyện Gia Lâm, thành phố Hà Nội
                            Điện thoại: 024.62617586 - Fax: 024.62617586
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <h1 class="text-center mb-2" style="text-align: center !important;">{{$surveyPeriod['title']}}</h1>
                <p class="text-center mb-2">{{$surveyPeriod['description']}}</p>
                <p class="text-start mb-2">
                    Thời gian khảo sát từ ngày <span class="fw-bold">{{
                        $surveyPeriod['start_time']->format('d/m/Y')
}}</span> đến ngày <span class="fw-bold">{{$surveyPeriod['end_time']->format('d/m/Y')}}</span>
                </p>
            </div>
            <div class="row">
                <h6 class="text-start">I. THÔNG TIN CÁ NHÂN</h6>
            </div>
            <div class="row">
                <p class="fw-bold">1. Mã sinh viên</p>
                <div class="custom-input">

                    <label>{{$surveyResponse['code_student'] ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold">2. Họ và tên</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['full_name'] ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">3. Giới tính</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['gender']->getName() ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">4. Ngày sinh</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['dob']->format('d/m/Y') ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <p class="fw-bold text-muted {{($surveyResponse['identification_card_number']) ?? 'mb-2'}}">5. Số căn cước công dân</p>
                    <div class="custom-input">
                        <label>{{$surveyResponse['identification_card_number'] ?? '   '}}</label>
                    </div>
                </div>
                <div class="">
                    <p class="fw-bold text-muted {{($surveyResponse['identification_issuance_date']) ?? 'mb-2'}}">Ngày cấp</p>
                    <div class="custom-input">
                        <label
                        >{{$surveyResponse['identification_issuance_date']?->format('d/m/Y') ?? '   '}}</label>
                    </div>
                </div>
                <div class="">
                    <p class="fw-bold text-muted {{($surveyResponse['identification_issuance_place']) ?? 'mb-2'}}">Nơi cấp</p>
                    <div class="custom-input">
                        <label
                        >{{$surveyResponse['identification_issuance_place'] ?? '   '}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">6. Khoá học</p>
                <div class="custom-input">
                    <label>{{'K'.mb_substr($surveyResponse['code_student'], 0, 2) ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">7. Tên ngành đào tạo</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['trainingIndustry']?->name ?? '   '}}</label>
                </div>
            </div>
            <div class="page-break"></div>
            <div class="row">
                <p class="fw-bold text-muted {{($surveyResponse['phone_number']) ?? 'mb-2'}}">8. Điện thoại</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['phone_number'] ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted {{($surveyResponse['email']) ?? 'mb-2'}}">9. Email</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['email'] ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">10. Anh/chị vui lòng cho biết tình trạng việc làm hiện tại của Anh/Chị</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\EmploymentStatus::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="radio" name="flexRadioDisabled"
                                   {{$surveyResponse['employment_status'] == $status ? 'checked' : ''}}
                                   id="flexRadioDisabled">
                            <label class="form-check-label" for="flexRadioDisabled">
                                {{$status->getName()}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted {{($surveyResponse['recruit_partner_name']) ?? 'mb-2'}}">11. Tên đơn vị tuyển dụng</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['recruit_partner_name'] ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <div class="">
                    <p class="fw-bold text-muted {{($surveyResponse['recruit_partner_address']) ?? 'mb-2'}}">12. Địa chỉ đơn vị tuyển dụng</p>
                    <div class="custom-input">
                        <label>{{$surveyResponse['recruit_partner_address'] ?? '   '}}</label>
                    </div>
                </div>
                <div class="">
                    <p class="fw-bold text-muted {{($surveyResponse['cityWork']) ?? 'mb-2'}}">Tỉnh/ Thành phố</p>
                    <div class="custom-input">
                        <label>{{$surveyResponse['cityWork']?->name ?? '' ?? '   '}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted {{($surveyResponse['recruit_partner_date']) ?? 'mb-2'}}">13. Thời gian tuyển dụng</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['recruit_partner_date']?->format('d/m/Y') ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted {{($surveyResponse['recruit_partner_position']) ?? 'mb-2'}}">14. Chức vụ, vị trí việc làm</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['recruit_partner_position'] ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <h6 class="text-start">II. NỘI DUNG KHẢO SÁT</h6>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">15. Đơn vị Anh/Chị đang làm việc thuộc khu vực làm việc nào?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\WorkArea::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="radio" name="flexRadioDisabled"
                                   {{$surveyResponse['work_area'] == $status ? 'checked' : ''}}
                                   id="flexRadioDisabled">
                            <label class="form-check-label" for="flexRadioDisabled">
                                {{$status->getName()}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="page-break"></div>
            <div class="row">
                <p class="fw-bold text-muted">16. Sau khi tốt nghiệp, Anh/Chị có việc làm từ khi nào?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\EmployedSince::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="radio" name="flexRadioDisabled"
                                   {{$surveyResponse['employed_since'] == $status ? 'checked' : ''}}

                                   id="flexRadioDisabled">
                            <label class="form-check-label" for="flexRadioDisabled">
                                {{$status->getName()}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">17. Công việc Anh/Chị đang đảm nhận có phù hợp với ngành được đào tạo
                    không?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\TrainedField::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="radio" name="flexRadioDisabled"
                                   {{$surveyResponse['trained_field'] == $status ? 'checked' : ''}}

                                   id="flexRadioDisabled">
                            <label class="form-check-label" for="flexRadioDisabled">
                                {{$status->getName()}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">18. Công việc Anh/Chị đang đảm nhận có phù hợp với trình độ chuyên môn
                    không?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\ProfessionalQualificationField::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="radio" name="flexRadioDisabled"
                                   {{$surveyResponse['professional_qualification_field'] == $status ? 'checked' : ''}}

                                   id="flexRadioDisabled">
                            <label class="form-check-label" for="flexRadioDisabled">
                                {{$status->getName()}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">19. Anh/chị có học được các kiến thức và kỹ năng cần thiết từ nhà trường
                    cho công
                    việc theo ngành tốt nghiệp không?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\LevelKnowledgeAcquired::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="radio" name="flexRadioDisabled"
                                   {{$surveyResponse['level_knowledge_acquired'] == $status ? 'checked' : ''}}

                                   id="flexRadioDisabled">
                            <label class="form-check-label" for="flexRadioDisabled">
                                {{$status->getName()}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted {{($surveyResponse['starting_salary']) ?? 'mb-2'}}">20. Mức lương khởi điểm của Anh/Chị (đơn vị triệu đồng/1 tháng)</p>
                <div class="custom-input">
                    <label>{{$surveyResponse['starting_salary'] ?? '   '}}</label>
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">21. Mức thu nhập bình quân/tháng tính theo VNĐ của Anh/Chị hiện nay?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\AverageIncome::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="radio" name="flexRadioDisabled"
                                   {{$surveyResponse['average_income'] == $status ? 'checked' : ''}}
                                   id="flexRadioDisabled">
                            <label class="form-check-label" for="flexRadioDisabled">
                                {{$status->getName()}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="page-break"></div>
            <div class="row">
                <p class="fw-bold text-muted">22. Anh/Chị tìm được việc làm thông qua những hình thức nào?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\JobSearchMethod::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="checkbox" value="{{$status->value}}"
                                   id="flexCheckDefault"
                                {{in_array($status->value, Arr::get($surveyResponse['job_search_method'], 'value') ?? []) ? 'checked' : ''}}
                            >
                            @if($status->value == 0)
                                <div class="d-flex">
                                    <label class="form-check-label text-nowrap" for="flexCheckDefault">
                                        {{$status->getName()}}:
                                    </label>
                                    <div class="custom-input">
                                        <label type="text" class="custom-input"
                                        >
                                            {{Arr::get($surveyResponse['job_search_method'], 'content_other') ?? ''}}
                                        </label>

                                    </div>
                                </div>
                            @else
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{$status->getName()}}
                                </label>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">23. Anh/chị được tuyển dụng theo hình thức nào?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\RecruitmentType::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="checkbox" value="{{$status->value}}"
                                   id="flexCheckDefault"
                                {{in_array($status->value, Arr::get($surveyResponse['recruitment_type'], 'value') ?? []) ? 'checked' : ''}}
                            >
                            @if($status->value == 0)
                                <div class="d-flex">
                                    <label class="form-check-label text-nowrap" for="flexCheckDefault">
                                        {{$status->getName()}}:
                                    </label>
                                    <div class="custom-input">
                                        <label type="text" class="custom-input"
                                        >
                                            {{Arr::get($surveyResponse['recruitment_type'], 'content_other') ?? ''}}
                                        </label>
                                    </div>
                                </div>
                            @else
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{$status->getName()}}
                                </label>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">24. Trong quá trình làm việc, Anh/Chị cần những kỹ năng mềm nào sau
                    đây?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\SoftSkillsRequired::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="checkbox" value="{{$status->value}}"
                                   id="flexCheckDefault"
                                {{in_array($status->value, Arr::get($surveyResponse['soft_skills_required'], 'value') ?? []) ? 'checked' : ''}}
                            >
                            @if($status->value == 0)
                                <div class="d-flex">
                                    <label class="form-check-label text-nowrap" for="flexCheckDefault">
                                        {{$status->getName()}}:
                                    </label>
                                    <div class="custom-input">
                                        <label type="text" class="custom-input"
                                        >
                                            {{Arr::get($surveyResponse['soft_skills_required'], 'content_other') ?? ''}}
                                        </label>
                                    </div>
                                </div>
                            @else
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{$status->getName()}}
                                </label>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="page-break"></div>
            <div class="row">
                <p class="fw-bold text-muted">25. Sau khi được tuyển dụng, Anh/Chị có phải tham gia khóa học nâng cao
                    nào dưới đây
                    để đáp ứng công việc không?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\MustAttendedCourses::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="checkbox" value="{{$status->value}}"
                                   id="flexCheckDefault"
                                {{in_array($status->value, Arr::get($surveyResponse['must_attended_courses'], 'value') ?? []) ? 'checked' : ''}}
                            >
                            @if($status->value == 0)
                                <div class="d-flex">
                                    <label class="form-check-label text-nowrap" for="flexCheckDefault">
                                        {{$status->getName()}}:
                                    </label>
                                    <div class="custom-input">
                                        <label type="text" class="custom-input"
                                        >
                                            {{Arr::get($surveyResponse['must_attended_courses'], 'content_other') ?? ''}}
                                        </label>
                                    </div>
                                </div>
                            @else
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{$status->getName()}}
                                </label>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row">
                <p class="fw-bold text-muted">26. Theo Anh/Chị, những giải pháp nào sau đây giúp tăng tỷ lệ có việc làm
                    đúng ngành
                    của sinh viên tốt nghiệp từ chương trình đào tạo mà Anh/Chị đã học?</p>
                <div>
                    @foreach(\App\Enums\EmploymentSurvey\SolutionsGetJob::cases() as $status)
                        <div class="form-check checkbox-custom">
                            <input class="form-check-input" type="checkbox" value="{{$status->value}}"
                                   id="flexCheckDefault"
                                {{in_array($status->value, Arr::get($surveyResponse['solutions_get_job'], 'value') ?? []) ? 'checked' : ''}}
                            >
                            @if($status->value == 0)
                                <div class="d-flex">
                                    <label class="form-check-label text-nowrap" for="flexCheckDefault">
                                        {{$status->getName()}}:
                                    </label>
                                    <div class="custom-input">
                                        <label
                                        >
                                            {{Arr::get($surveyResponse['solutions_get_job'], 'content_other') ?? ''}}
                                        </label>
                                    </div>
                                </div>
                            @else
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{$status->getName()}}
                                </label>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</main>
</body>
</html>
