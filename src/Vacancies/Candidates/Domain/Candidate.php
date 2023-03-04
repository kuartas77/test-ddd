<?php

namespace Src\Vacancies\Candidates\Domain;

use Src\Shared\Domain\AggregateRoot;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateCreatedAt;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateCreatedBy;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateName;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateOwner;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateSource;

class Candidate extends AggregateRoot
{

    public function __construct(
        public ?int                $id,
        public CandidateName       $name,
        public CandidateSource     $source,
        public CandidateOwner      $owner,
        public CandidateCreatedBy  $createdBy,
        public ?CandidateCreatedAt $createdAt
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'source' => $this->source,
            'owner' => $this->owner,
            'created_by' => $this->createdBy,
            'created_at' => $this->createdAt,
        ];
    }
}
