<?php

declare(strict_types=1);

namespace App\DTO\EmploymentSurveyResponse;

class ResponseOther
{
    private array $value;

    private ?string $contentOther;

    private ?string $valueOther = '0';

    public function __construct($value = null, $contentOther = null)
    {
        $this->value = null !== $value ? $value : [$this->valueOther];
        $this->contentOther = $contentOther;
    }

    public function init($value, $contentOther): void
    {
        $this->value = $value;
        $this->contentOther = $contentOther;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function setValue(array $value): void
    {
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'content_other' => in_array($this->valueOther, $this->value) ? $this->contentOther : null,
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
