<?php

namespace Tests\Unit\Vacancies\Candidates\Application;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Src\Shared\Domain\Exceptions\UnauthorizedUserException;
use Src\Vacancies\Candidates\Application\GetAllCandidatesUseCase;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;

class GetAllCandidatesUseCaseTest extends TestCase
{

    public function test_get_all_candidates_use_case()
    {
        $user = User::factory()->create(['role' => 'manager']);
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);
        $useCase = new GetAllCandidatesUseCase($spy);

        $spy->shouldReceive('all');

        $candidates = $useCase();

        $this->assertIsArray($candidates);

        $spy->shouldHaveReceived()->all();
    }

    public function test_get_all_candidates_use_case_exception()
    {
        $user = User::factory()->create(['role' => 'agent']);
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);
        
        $spy = \Mockery::spy(CandidateRepositoryContract::class);
        $useCase = new GetAllCandidatesUseCase($spy);
        
        $this->expectException(UnauthorizedUserException::class);

        $spy->shouldReceive('all');

        $useCase();

        $spy->shouldHaveReceived()->all();
    }
}
