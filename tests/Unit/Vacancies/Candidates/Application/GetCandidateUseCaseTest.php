<?php

namespace Tests\Unit\Vacancies\Candidates\Application;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Src\Vacancies\Candidates\Application\GetCandidateUseCase;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;

class GetCandidateUseCaseTest extends TestCase
{
    public function test_get_candidate_by_owner_use_case_agent()
    {
        $user = User::factory()->create(['role' => 'agent']);
        
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);
      
        $useCase = new GetCandidateUseCase($spy, $user->id);

        $spy->shouldReceive('find')->once();

        $useCase(1);

        $spy->shouldHaveReceived()->find(1)->once();
    }

    public function test_get_candidate_by_owner_use_case_manager()
    {
        $user = User::factory()->create(['role' => 'manager']);

        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);

        $useCase = new GetCandidateUseCase($spy, $user->id);

        $spy->shouldReceive('find')->once();

        $useCase(1);

        $spy->shouldHaveReceived()->find(1)->once();
    }
}
