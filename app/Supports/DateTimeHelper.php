<?php

declare(strict_types=1);

namespace App\Supports;

use Exception;
use Illuminate\Support\Carbon;

class DateTimeHelper
{
    public static function createDateTime($date): bool|Carbon|null
    {
        $formats = ['d/m/y', 'd-m-Y', 'd/m/Y', 'd-m-y', 'Y-m-d', 'm/d/Y', 'j-n-Y'];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $date);
            } catch (Exception $e) {
                continue;
            }
        }

        return null;
    }
}