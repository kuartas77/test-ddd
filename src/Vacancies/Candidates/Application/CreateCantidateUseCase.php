<?php declare(strict_types=1);

namespace Src\Vacancies\Candidates\Application;

use Src\Shared\Domain\Exceptions\UnauthorizedUserException;
use Src\Vacancies\Candidates\Domain\Candidate;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;
use Src\Vacancies\Candidates\Domain\Policies\UserPolicy;
use Src\Vacancies\Candidates\Infrastructure\Mappers\CandidateMapper;

class CreateCantidateUseCase
{
    public function __construct(private CandidateRepositoryContract $repository)
    {
    }

    /**
     * @throws UnauthorizedUserException
     */
    public function __invoke(array $attributes): Candidate
    {
        authorize('save', UserPolicy::class);
        return $this->repository->save(CandidateMapper::toArray($attributes));
    }
}
