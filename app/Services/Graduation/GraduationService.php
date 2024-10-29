<?php

declare(strict_types=1);

namespace App\Services\Graduation;

use App\DTO\Graduation\CreateGraduationDTO;
use App\DTO\Graduation\ListGraduationDTO;
use App\DTO\Graduation\UpdateGraduationDTO;
use App\Enums\AuthApiSection;
use App\Exceptions\CreateResourceFailedException;
use App\Exceptions\DeleteResourceFailedException;
use App\Exceptions\UpdateResourceFailedException;
use App\Models\GraduationCeremony;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GraduationService
{
    public function getList(ListGraduationDTO $graduationDTO): Collection|LengthAwarePaginator|array
    {
        $query = GraduationCeremony::query()
            ->when($graduationDTO->getSchoolYearId(), fn ($query) => $query->where('school_year_id', $graduationDTO->getSchoolYearId()))
            ->when($graduationDTO->getCertification(), fn ($query) => $query->where('certification', $graduationDTO->getCertification()))
            ->where('faculty_id', '=', auth()->user()->faculty_id ?? null)
            ->withCount('students')
            ->with(['schoolYear'])
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
     * @throws DeleteResourceFailedException
     */
    public function delete(GraduationCeremony $graduation): bool
    {
        try {
            if ($graduation->students()->exists()) {
                throw new Exception('Graduation ceremony has student');
            }

            return $graduation->delete();
        } catch (Exception $exception) {
            Log::error('Error delete graduation ceremony action', [
                'method' => __METHOD__,
                'message' => $exception->getMessage(),
            ]);

            throw new DeleteResourceFailedException();
        }
    }
}
