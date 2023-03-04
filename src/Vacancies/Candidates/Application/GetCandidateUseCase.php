<?php declare(strict_types=1);

namespace Src\Vacancies\Candidates\Application;

use Src\Shared\Domain\Exceptions\UnauthorizedUserException;
use Src\Vacancies\Candidates\Domain\Candidate;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;
use Src\Vacancies\Candidates\Domain\Policies\UserPolicy;

class GetCandidateUseCase
{
    public function __construct(private CandidateRepositoryContract $repository)
    {
    }

    /**
     * @throws UnauthorizedUserException
     */
    public function __invoke(int $id): ?Candidate
    {
        authorize('find', UserPolicy::class);
        return $this->repository->find($id);
    }
}
