<?php

declare(strict_types=1);

namespace Src\Vacancies\Candidates\Domain\Contracts;

use Src\Vacancies\Candidates\Domain\Candidate;

interface CandidateRepositoryContract
{
    public function find(int $id): ?Candidate;

    public function findOneByOwner(int $owner, int $id): ?Candidate;

    public function findAllByOwner(int $owner): array;

    public function all(): array;

    public function save(array $candidate): Candidate;
}
