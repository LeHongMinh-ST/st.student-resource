<?php

declare(strict_types=1);

namespace App\Services\Graduation;

use App\DTO\Graduation\CreateGraduationDTO;
use App\DTO\Graduation\ListGraduationDTO;
use App\DTO\Graduation\UpdateGraduationDTO;
use App\Enums\AuthApiSection;
use App\Enums\Status;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Models\GraduationCeremony;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GraduationService
{
    public function getList(ListGraduationDTO $graduationDTO): Collection|LengthAwarePaginator|array
    {
        $query = GraduationCeremony::query()
            ->when($graduationDTO->getYear(), fn ($query) => $query->where('year', $graduationDTO->getYear()))
            ->when($graduationDTO->getCertification(), fn ($query) => $query->where('certification', $graduationDTO->getCertification()))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->when($graduationDTO->getIsGraduationDoesntHaveSurveyPeriod(), fn ($query) => $query->whereDoesntHave('surveyPeriods', function ($query): void {
                $query->where('status', Status::Enable->value);
            }))
            ->when(
                $graduationDTO->getWithIdSurveyPeriod(),
                fn ($query) => $query
                    ->where(function ($query) use ($graduationDTO): void {
                        $query->whereDoesntHave('surveyPeriods', function ($query): void {
                            $query->where('status', Status::Enable->value);
                        })->orWhereHas('surveyPeriods', function ($query) use ($graduationDTO): void {
                            $query->where('survey_period_graduation.survey_period_id', $graduationDTO->getWithIdSurveyPeriod());
                        });
                    })
            )
            ->withCount('students')
            ->orderBy($graduationDTO->getOrderBy(), $graduationDTO->getOrder()->value);

        return $graduationDTO->getPage() ? $query->paginate($graduationDTO->getLimit()) : $query->get();
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function create(CreateGraduationDTO $graduationDTO): GraduationCeremony
    {
        try {
            return GraduationCeremony::create([
                ...$graduationDTO->toArray(),
                'faculty_id' => auth(AuthApiSection::Admin->value)->user()->faculty_id,
            ]);
        } catch (Exception $exception) {
            Log::error('Error create graduation ceremony action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw new CreateResourceFailedException();
        }
    }

    /**
     * @throws UpdateResourceFailedException
     */
    public function update(UpdateGraduationDTO $graduationDTO): GraduationCeremony
    {
        try {
            $graduation = GraduationCeremony::where('id', $graduationDTO->getId())->first();

            $graduation->fill($graduationDTO->toArray());

            $graduation->save();

            return $graduation;
        } catch (Exception $exception) {
            Log::error('Error update graduation ceremony action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw new UpdateResourceFailedException();
        }
    }

    /**
     * @throws ValidationException
     */
    public function delete(GraduationCeremony $graduation): bool
    {
        if ($graduation->students()->exists()) {
            throw ValidationException::withMessages(
                ['message' => 'Không thể xóa lễ tốt nghiệp đã có sinh viên tham gia']
            );
        }

        return $graduation->delete();
    }
}
