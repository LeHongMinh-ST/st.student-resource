<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phiếu Khảo Sát Tình Hình Việc Làm Của Sinh Viên Tốt Nghiệp</title>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.esm.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/bootstrap/css/bootstrap-grid.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/bootstrap/css/bootstrap-reboot.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/bootstrap/css/bootstrap-utilities.css') }}" rel="stylesheet" type="text/css">

    <style>
        .container-survey {
            /*background-color: #e3edfb;*/
            font-family: 'DejaVu Sans', sans-serif; /* Sử dụng font Roboto */
            padding: 20px;
            padding-bottom: 0;

        }

        .row {
            background-color: #fff;
            padding: 10px;
            /*box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);*/
            /*border-radius: 0.25rem;*/
            /*margin: 20px 0;*/
        }

        h2, h1 {
            font-size: 13px;
            margin: 0;
        }

        h6, h5, h4, h3 {
            font-size: 12px;
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
    <div class="container content">
        <div class="header">
            <div class="col-6" style="float: left; width: 50%; text-align: center;">
                <img src="data:image/png;base64,{{base64_encode(file_get_contents(asset('assets/images/logo-vnua.png'))) }}" alt="logo" style="width: 30%; object-fit: cover; filter: blur(0.2px);"/>
            </div>
            <div class="col-6" style="float: right; width: 50%;">
                <p class="text-end fw-light pb-4" style="text-align: right; opacity: 0.6">Ngày {{\Carbon\Carbon::now()->format('d/m/Y')}}</p>
                <div style="text-align: center;">
                    <h2 class="text-center">BỘ NÔNG NGHIỆP</h2>
                    <h2 class="text-center">VÀ PHÁT TRIỂN NÔNG THÔN</h2>
                    <h2 class="text-center">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h2>
                    <h2 class="text-center">Thị trấn Trâu Quỳ, huyện Gia Lâm, thành phố Hà Nội
                        Điện thoại: 024.62617586 - Fax: 024.62617586
                    </h2>
                </div>
            </div>
        </div>
{{--        <div class="header">--}}
{{--            <div class="" style="display: flex; justify-content: space-between">--}}
{{--                <div class="col-6" style="text-align: center">--}}
{{--                    <img src="data:image/png;base64,{{base64_encode(file_get_contents(asset('assets/images/logo-vnua.png'))) }}" alt="logo" style="width: 100px; object-fit: cover"/>--}}
{{--                </div>--}}
{{--                <div class="col-6">--}}
{{--                    <p class="" style="text-align: right; opacity: 0.5">Ngày {{\Carbon\Carbon::now()->format('d/m/Y')}}</p>--}}
{{--                    <div style="display: flex; flex-direction: column; align-items: center;">--}}
{{--                        <h2 class="text-center">BỘ NÔNG NGHIỆP</h2>--}}
{{--                        <h2 class="text-center">VÀ PHÁT TRIỂN NÔNG THÔN</h2>--}}
{{--                        <h2 class="text-center">HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h2>--}}
{{--                        <h2 class="text-center">Thị trấn Trâu Quỳ, huyện Gia Lâm, thành phố Hà Nội--}}
{{--                            Điện thoại: 024.62617586 - Fax: 024.62617586--}}
{{--                        </h2>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
</main>
</body>
</html>
