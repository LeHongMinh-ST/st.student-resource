<?php

declare(strict_types=1);

namespace App\Services\SurveyPeriod;

use App\DTO\SurveyPeriod\CreateSurveyPeriodDTO;
use App\DTO\SurveyPeriod\ListSurveyPeriodDTO;
use App\DTO\SurveyPeriod\UpdateSurveyPeriodDTO;
use App\Enums\Status;
use App\Events\DownloadSurveyResponseEvent;
use App\Jobs\CreateFilePDFAndSaveJob;
use App\Jobs\SendMailForm;
use App\Models\EmploymentSurveyResponse;
use App\Models\SurveyPeriod;
use App\Models\SurveyPeriodStudent;
use App\Models\ZipExportFile;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipStream\ZipStream;

class SurveyPeriodService
{
    public function getList(ListSurveyPeriodDTO $listSurveyPeriodDTO): Collection|LengthAwarePaginator|array
    {
        $query = SurveyPeriod::query()
            ->when(
                $listSurveyPeriodDTO->getQ(),
                fn ($q) => $q->where('title', 'like', '%' . $listSurveyPeriodDTO->getQ() . '%')
            )
            ->when($listSurveyPeriodDTO->getStatus(), fn ($q) => $q->where('status', $listSurveyPeriodDTO->getStatus()))
            ->when($listSurveyPeriodDTO->getType(), fn ($q) => $q->where('type', $listSurveyPeriodDTO->getType()))
            ->when($listSurveyPeriodDTO->getYear(), fn ($q) => $q->where('year', $listSurveyPeriodDTO->getYear()))
            ->when($listSurveyPeriodDTO->getStartDate(), fn ($q) => $q->where('start_date', '>=', $listSurveyPeriodDTO->getStartDate()))
            ->when($listSurveyPeriodDTO->getEndDate(), fn ($q) => $q->where('end_date', '<=', $listSurveyPeriodDTO->getEndDate()))
            ->when($listSurveyPeriodDTO->getFacultyId(), fn ($q) => $q->where('faculty_id', $listSurveyPeriodDTO->getFacultyId()))
            ->with('createdBy')
            ->withCount([
                'employmentSurveyResponses as total_student_responses',
                'students as total_student',
            ])
            ->orderBy($listSurveyPeriodDTO->getOrderBy(), $listSurveyPeriodDTO->getOrder()->value);

        return $listSurveyPeriodDTO->getPage() ? $query->paginate($listSurveyPeriodDTO->getLimit()) : $query->get();
    }

    public function create(CreateSurveyPeriodDTO $createSurveyPeriodDTO): SurveyPeriod
    {
        $surveyPeriod = SurveyPeriod::create(Arr::except($createSurveyPeriodDTO->toArray(), ['graduation_ceremony_ids']));
        $surveyPeriod->graduationCeremonies()->attach($createSurveyPeriodDTO->getGraduationCeremonyIds());
        $studentIds = $surveyPeriod->graduationCeremonies->pluck('students')->flatten()->pluck('id')->toArray();
        $surveyPeriod->students()->attach($studentIds);

        return $surveyPeriod;
    }

    public function update(UpdateSurveyPeriodDTO $updateSurveyPeriodDTO): SurveyPeriod
    {
        try {
            DB::beginTransaction();
            $surveyPeriod = SurveyPeriod::where('id', $updateSurveyPeriodDTO->getId())->first();
            $surveyPeriod->update(Arr::except($updateSurveyPeriodDTO->toArray(), ['graduation_ceremony_ids']));

            if ($updateSurveyPeriodDTO->getGraduationCeremonyIds()) {
                $currentGraduationCeremonyIds = $surveyPeriod->graduationCeremonies->pluck('id')->toArray();
                // Phần tử bị xóa
                $deletedGraduationIds = Arr::flatten(array_diff($currentGraduationCeremonyIds, $updateSurveyPeriodDTO->getGraduationCeremonyIds()));

                // Phần tử được thêm mới
                $addedGraduationIds = Arr::flatten(array_diff($updateSurveyPeriodDTO->getGraduationCeremonyIds(), $currentGraduationCeremonyIds));

                // Xóa các phần tử bị xóa
                if (count($deletedGraduationIds) > 0) {
                    $surveyPeriod->graduationCeremonies()->detach($deletedGraduationIds);
                    $studentIds = $surveyPeriod->graduationCeremonies->whereIn('id', $deletedGraduationIds)
                        ->pluck('students')->flatten()->pluck('id')->toArray();
                    $surveyPeriod->students()->detach($studentIds);
                }

                if (count($addedGraduationIds) > 0) {
                    $surveyPeriod->graduationCeremonies()->syncWithoutDetaching($addedGraduationIds);
                    $studentAdds = $surveyPeriod->graduationCeremonies()->with('students')->whereIn('graduation_ceremonies.id', $addedGraduationIds)->get();
                    $studentIds = $studentAdds->pluck('students')->flatten()->pluck('id')->toArray();
                    $surveyPeriod->students()->syncWithoutDetaching($studentIds);
                }
            }
            DB::commit();

            return $surveyPeriod;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function show(mixed $id): SurveyPeriod
    {
        return SurveyPeriod::where('id', $id)
            ->withCount([
                'employmentSurveyResponses as total_student_responses',
                'students as total_student' => function ($query) use ($id): void {
                    $query->whereHas('surveyPeriods', function ($q) use ($id): void {
                        $q->where('survey_periods.id', $id);
                    });
                },
            ])->with(['graduationCeremonies'])
            ->first();
    }

    public function delete(mixed $id): bool
    {
        $surveyPeriod = SurveyPeriod::where('id', $id)->first();
        if (Status::Enable === $surveyPeriod->status) {
            throw new Exception('Không thể xóa khảo sát đang hoạt động');
        }

        $surveyPeriod->graduationCeremonies()->detach();
        $surveyPeriod->students()->detach();

        return $surveyPeriod->delete();
    }

    public function sendMail(SurveyPeriod $surveyPeriod, array $data): void
    {
        try {
            if (Arr::get($data, 'is_all_mail_student')) {
                $students = $surveyPeriod->students->load('info');
            } else {
                $students = $surveyPeriod->students->load('info')->whereIn('id', $data['student_ids'])->values();
            }

            $open_time = $surveyPeriod->start_time?->format('d/m/Y');
            $close_time = $surveyPeriod->end_time?->format('d/m/Y');

            foreach ($students as $student) {
                if (null === $student?->info?->person_email) {
                    continue;
                }

                $codeVerify = $this->generateCodeVerifySendMail($student->id, $surveyPeriod->id);
                $url = config('vnua.app_student_url') . '/form-job-survey/' . $surveyPeriod->id . '?code=' . $codeVerify;

                SurveyPeriodStudent::where('student_id', $student->id)
                    ->where('survey_period_id', $surveyPeriod->id)
                    ->update([
                        'code_verify' => $codeVerify,
                        'number_mail_send' => DB::raw('number_mail_send + 1'),
                    ]);

                SendMailForm::dispatch(
                    $student->info->person_email,
                    $surveyPeriod->title,
                    $surveyPeriod->faculty->name,
                    $open_time . ' đến ' . $close_time,
                    $url,
                )->onQueue('default');
            }
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    public function exportResponseSurveyToPDF(SurveyPeriod $surveyPeriod, array $data): ZipExportFile
    {
        try {
            if (Arr::get($data, 'is_all_student')) {
                $listSurveyResponse = $surveyPeriod->employmentSurveyResponses();
            } else {
                $listSurveyResponse = EmploymentSurveyResponse::whereIn('id', $data['student_ids']);
            }

            // Create file Zip
            $zipExportFile = ZipExportFile::create([
                'survey_period_id' => $surveyPeriod->id,
                'name' => 'survey_response_' . Carbon::now()->microsecond . '.zip',
                'faculty_id' => $surveyPeriod->faculty_id,
                'file_total' => $listSurveyResponse->count(),
                'process_total' => 0,
            ]);

            $listSurveyResponse->chunk(1, function ($listSurveyResponseChunk) use ($surveyPeriod, $zipExportFile): void {
                dispatch(new CreateFilePDFAndSaveJob($surveyPeriod, $zipExportFile->id, auth()->user()->id, $listSurveyResponseChunk))
                    ->onQueue('import');
            });

            return $zipExportFile;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function downloadZipSurveyResponse($zipFileId, array $data): StreamedResponse
    {
        $zipFile = ZipExportFile::where('id', $zipFileId)->first();
        $response = new StreamedResponse(function () use ($zipFile): void {
            $listFilePdfNames = $zipFile->pdfExportFiles->pluck('name')->toArray();

            // Tạo một đối tượng ZipStream
            $zip = new ZipStream(outputName: 'Ket_quan_khao_sat_viec_lam_sv_tot_nghiep_' . $zipFile->surveyPeriod->year . '.zip');
            // Lấy tất cả các file trong thư mục và thư mục con
            foreach ($listFilePdfNames as $fileName) {
                $zip->addFileFromPath(
                    fileName: 'SV' . strtok($fileName, '_') . '.pdf',
                    path: storage_path('app/public/pdf/' . $fileName)
                );
            }
        });

        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $zipFile->name . '"');
        $response->headers->set('Cache-Control', 'max-age=0');
        event(new DownloadSurveyResponseEvent(
            $zipFile
        ));

        // Trả về file ZIP để người dùng tải xuống
        return $response;
    }

    public function getFileZipSurveyResponse($zipFileId): ZipExportFile
    {
        return ZipExportFile::where('id', $zipFileId)->first();
    }

    private function generateCodeVerifySendMail(int $studentId, int $surveyPeriodId): string
    {
        return md5($studentId . $surveyPeriodId . now()->timestamp);
    }
}
