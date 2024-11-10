<?php

declare(strict_types=1);

namespace App\DTO\EmploymentSurveyResponse;

class ResponseOther
{
    private int $value;

    private ?string $contentOther;

    public function __construct($value = null, $contentOther = null)
    {
        $this->value = null !== $value ? $value : 99;
        $this->contentOther = $contentOther;
    }

    public function init($value, $contentOther): void
    {
        $this->value = $value;
        $this->contentOther = $contentOther;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'content_other' => $this->getContentOther(),
        ];
    }

    public function toString(): string
    {
        return json_encode($this->toArray());
    }

    public function getContentOther(): ?string
    {
        return $this->contentOther;
    }

    public function setContentOther(?string $contentOther): void
    {
        $this->contentOther = $contentOther;
    }
}
