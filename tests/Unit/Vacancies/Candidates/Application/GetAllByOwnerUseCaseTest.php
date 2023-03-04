<?php

namespace Tests\Unit\Vacancies\Candidates\Application;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Src\Shared\Domain\Exceptions\UnauthorizedUserException;
use Src\Vacancies\Candidates\Application\GetAllByOwnerUseCase;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;

class GetAllByOwnerUseCaseTest extends TestCase
{

    public function test_get_all_find_by_owner_use_case()
    {
        $user = User::factory()->create(['role' => 'agent']);
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);
        $useCase = new GetAllByOwnerUseCase($spy, $user->id);

        $spy->shouldReceive('findAllByOwner')->with($user->id);

        $candidates = $useCase($user->id);

        $this->assertIsArray($candidates);

        $spy->shouldHaveReceived()->findAllByOwner($user->id);
    }

    public function test_get_all_find_by_owner_use_case_exception()
    {
        $user = User::factory()->create(['role' => 'manager']);
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);
        $useCase = new GetAllByOwnerUseCase($spy, $user->id);

        $this->expectException(UnauthorizedUserException::class);
        
        $spy->shouldReceive('findAllByOwner')->with($user->id);

        $candidates = $useCase($user->id);

        $this->assertIsArray($candidates);

        $spy->shouldHaveReceived()->findAllByOwner($user->id);
    }
}
