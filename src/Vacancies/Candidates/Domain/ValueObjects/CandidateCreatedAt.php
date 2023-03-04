<?php

namespace Src\Vacancies\Candidates\Domain\ValueObjects;

use DateTime;
use JsonSerializable;

class CandidateCreatedAt implements JsonSerializable
{
    public function __construct(public ?DateTime $createdAt)
    {
    }

    public function jsonSerialize(): string
    {
        return $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : '';
    }
}
