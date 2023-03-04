<?php

namespace Src\Shared\Domain;

use JsonSerializable;

abstract class AggregateRoot implements JsonSerializable
{
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    abstract public function toArray(): array;
}
