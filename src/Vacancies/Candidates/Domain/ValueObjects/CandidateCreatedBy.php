<?php

namespace Src\Vacancies\Candidates\Domain\ValueObjects;

use JsonSerializable;

class CandidateCreatedBy implements JsonSerializable
{
    public function __construct(public int $createdBy)
    {
    }

    public function __toString(): string
    {
        return $this->createdBy;
    }

    public function jsonSerialize(): string
    {
        return $this->createdBy;
    }
}
