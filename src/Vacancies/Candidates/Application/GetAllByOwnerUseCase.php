<?php declare(strict_types=1);

namespace Src\Vacancies\Candidates\Application;

use Src\Shared\Domain\Exceptions\UnauthorizedUserException;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;
use Src\Vacancies\Candidates\Domain\Policies\UserPolicy;

class GetAllByOwnerUseCase
{
    public function __construct(private CandidateRepositoryContract $repository, private int $owner)
    {
    }

    /**
     * @throws UnauthorizedUserException
     */
    public function __invoke(): array
    {
        authorize('findAllByOwner', UserPolicy::class);
        return $this->repository->findAllByOwner($this->owner);
    }
}
