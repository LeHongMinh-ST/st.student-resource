<?php

declare(strict_types=1);

namespace App\Factories\AdmissionYear;

use App\DTO\AdmissionYear\ListAdmissionYearDTO;
use App\Http\Requests\Admin\AdmissionYear\ListAdmissionYearRequest;
use App\Supports\MakeDataHelper;

class ListAdmissionYearDTOFactory
{
    public static function make(ListAdmissionYearRequest $request): ListAdmissionYearDTO
    {
        $command = new ListAdmissionYearDTO();

        return MakeDataHelper::makeListData($request, $command);
    }
}
