<?php

namespace Src\Vacancies\Candidates\Domain\ValueObjects;

use JsonSerializable;

class CandidateSource implements JsonSerializable
{
    public function __construct(public string $source)
    {
    }

    public function __toString(): string
    {
        return $this->source;
    }

    public function jsonSerialize(): string
    {
        return $this->source;
    }
}
