<?php

declare(strict_types=1);

namespace App\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case Unspecified = 'unspecified';

    public static function mapValue(string $gender): self
    {
        return match (mb_strtolower($gender)) {
            'nam', 'male' => self::Male,
            'nữ', 'nu', 'female' => self::Female,
            default => self::Unspecified,
        };
    }

    public function getName(): string
    {
        return match ($this) {
            self::Male => 'Nam',
            self::Female => 'Nữ',
            default => 'Khác',
        };
    }
}
