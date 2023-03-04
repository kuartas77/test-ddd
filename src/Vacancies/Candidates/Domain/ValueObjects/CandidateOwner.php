<?php

namespace Src\Vacancies\Candidates\Domain\ValueObjects;

use JsonSerializable;


class CandidateOwner implements JsonSerializable
{
    public function __construct(public int $owner)
    {
    }

    public function __toString(): string
    {
        return $this->owner;
    }

    public function jsonSerialize(): string
    {
        return $this->owner;
    }
}
