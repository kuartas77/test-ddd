<?php

namespace Src\Vacancies\Candidates\Infrastructure\Mappers;

use App\Models\Candidate as EloquentModel;
use Src\Vacancies\Candidates\Domain\Candidate;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateCreatedAt;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateCreatedBy;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateName;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateOwner;
use Src\Vacancies\Candidates\Domain\ValueObjects\CandidateSource;

class CandidateMapper
{
    public static function toArray(array $attributes): array
    {
        $arr = (new Candidate(
            id: null,
            name: new CandidateName($attributes['name']),
            source: new CandidateSource($attributes['source']),
            owner: new CandidateOwner($attributes['owner']),
            createdBy: new CandidateCreatedBy($attributes['created_by']),
            createdAt: null
        ))->toArray();

        array_shift($arr);
        array_pop($arr);

        return $arr;
    }

    public static function fromEloquent(EloquentModel $eloquentModel): Candidate
    {
        return new Candidate(
            id: $eloquentModel->id,
            name: new CandidateName($eloquentModel->name),
            source: new CandidateSource($eloquentModel->source),
            owner: new CandidateOwner($eloquentModel->owner),
            createdBy: new CandidateCreatedBy($eloquentModel->created_by),
            createdAt: new CandidateCreatedAt($eloquentModel->created_at)
        );
    }
}
