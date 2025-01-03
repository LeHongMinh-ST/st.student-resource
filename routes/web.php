<?php

declare(strict_types=1);

use App\Events\ImportStudentCourseEvent;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    //    event(new ImportStudentCourseEvent('test'));
    Illuminate\Support\Facades\Log::info('minh');
    event(new ImportStudentCourseEvent(
        message: 'success',
        userId: 1
    ));
    return view('welcome');
});

Route::get('/test', function () {
    $surveyPeriod = App\Models\SurveyPeriod::find(10);
    $surveyResponse = $surveyPeriod->employmentSurveyResponses;
    return view('pdf.1response-survey', [
        'surveyResponse' => $surveyResponse[0],
        'surveyPeriod' => $surveyPeriod
    ]);
});
