<?php

declare(strict_types=1);

namespace App\Factories\SchoolYear;

use App\DTO\SchoolYear\ListSchoolYearDTO;
use App\Http\Requests\Admin\SchoolYear\ListSchoolYearRequest;
use App\Supports\MakeDataHelper;

class ListSchoolYearDTOFactory
{
    public static function make(ListSchoolYearRequest $request): ListSchoolYearDTO
    {
        $command = new ListSchoolYearDTO();

        return MakeDataHelper::makeListData($request, $command);
    }
}
