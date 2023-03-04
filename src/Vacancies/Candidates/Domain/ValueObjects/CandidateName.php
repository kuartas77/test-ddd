<?php

namespace Src\Vacancies\Candidates\Domain\ValueObjects;

use JsonSerializable;

class CandidateName implements JsonSerializable
{
    public function __construct(public string $name)
    {
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
