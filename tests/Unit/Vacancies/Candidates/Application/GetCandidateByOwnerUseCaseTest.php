<?php

namespace Tests\Unit\Vacancies\Candidates\Application;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Src\Shared\Domain\Exceptions\UnauthorizedUserException;
use Src\Vacancies\Candidates\Application\GetCandidateByOwnerUseCase;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;

class GetCandidateByOwnerUseCaseTest extends TestCase
{
    public function test_get_candidate_by_owner_use_case()
    {
        $user = User::factory()->create(['role' => 'agent']);
        
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);
      
        $useCase = new GetCandidateByOwnerUseCase($spy, $user->id);

        $spy->shouldReceive('findOneByOwner')->once();

        $useCase($user->id, 1);

        $spy->shouldHaveReceived()->findOneByOwner($user->id, 1)->once();
    }

    public function test_get_candidate_by_owner_use_case_exception()
    {
        $user = User::factory()->create(['role' => 'manager']);

        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);

        $useCase = new GetCandidateByOwnerUseCase($spy, $user->id);

        $this->expectException(UnauthorizedUserException::class);

        $spy->shouldReceive('findOneByOwner');

        $useCase($user->id, 1);

        $spy->shouldHaveReceived()->findOneByOwner($user->id, 1);
    }
}
