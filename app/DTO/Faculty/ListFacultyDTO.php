<?php

declare(strict_types=1);

namespace App\DTO\Faculty;

use App\DTO\BaseDTO;
use App\DTO\BaseListDTO;

class ListFacultyDTO extends BaseListDTO implements BaseDTO
{
    protected ?string $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }



    public function toArray(): array
    {
        return [];
    }
}
