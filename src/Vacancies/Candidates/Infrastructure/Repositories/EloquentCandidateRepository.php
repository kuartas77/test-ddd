<?php declare(strict_types=1);

namespace Src\Vacancies\Candidates\Infrastructure\Repositories;

use App\Models\Candidate as EloquentModel;
use Illuminate\Support\Facades\Cache;
use Src\Vacancies\Candidates\Domain\Candidate;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;
use Src\Vacancies\Candidates\Infrastructure\Mappers\CandidateMapper;

class EloquentCandidateRepository implements CandidateRepositoryContract
{
    private const TIME_CACHE = 60;

    public function __construct(private EloquentModel $eloquentCandidateModel)
    {
    }

    public function findOneByOwner(int $owner, int $id): ?Candidate
    {
        $candidate = Cache::remember("candidate.owner:{$owner}.{$id}", self::TIME_CACHE,
            fn() => $this->eloquentCandidateModel->query()
                ->where('owner', $owner)->where('id', $id)->firstOrFail());
        return CandidateMapper::fromEloquent($candidate);
    }

    public function findAllByOwner(int $owner): array
    {
        $candidates = Cache::remember("candidates.owner:{$owner}", self::TIME_CACHE,
            fn() => $this->eloquentCandidateModel->query()->where('owner', $owner)->get());
        return $candidates->map(fn($candidate) => CandidateMapper::fromEloquent($candidate))->toArray();
    }

    public function all(): array
    {
        $candidates = [];
        $allCandidates = Cache::remember("candidates.manager", self::TIME_CACHE,
            fn() => $this->eloquentCandidateModel->all());
        foreach ($allCandidates as $candidate) {
            $candidates[] = CandidateMapper::fromEloquent($candidate);
        }
        return $candidates;
    }

    public function save(array $attributes): Candidate
    {
        $eloquentCandidate = $this->eloquentCandidateModel->query()->create($attributes);
        return $this->find($eloquentCandidate->id);
    }

    public function find(int $id): ?Candidate
    {
        $candidate = Cache::remember("candidate.{$id}", self::TIME_CACHE,
            fn() => $this->eloquentCandidateModel->query()->findOrFail($id));
        return CandidateMapper::fromEloquent($candidate);
    }
}
