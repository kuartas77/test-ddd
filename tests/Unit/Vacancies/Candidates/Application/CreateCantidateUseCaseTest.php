<?php

namespace Tests\Unit\Vacancies\Candidates\Application;

use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Src\Shared\Domain\Exceptions\UnauthorizedUserException;
use Src\Vacancies\Candidates\Application\CreateCantidateUseCase;
use Src\Vacancies\Candidates\Infrastructure\Mappers\CandidateMapper;
use Src\Vacancies\Candidates\Domain\Contracts\CandidateRepositoryContract;

class CreateCantidateUseCaseTest extends TestCase
{

    public function test_create_candidate_use_case_can_save()
    {
        $user = User::factory()->create(['role' => 'manager']);
        
        $attributes = ['name'=> 'juan', 'source'=> 'tester', 'owner'=> 1, 'created_by'=> 1];
        
        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);

        $useCase = new CreateCantidateUseCase($spy);

        $spy->shouldReceive('save')->with(CandidateMapper::toArray($attributes))->once();

        $useCase($attributes);

        $spy->shouldHaveReceived()->save(CandidateMapper::toArray($attributes))->once();
    }

    public function test_create_candidate_use_case_can_save_exception()
    {
        $attributes = ['name'=> 'juan', 'source'=> 'tester', 'owner'=> 1, 'created_by'=> 1];

        $user = User::factory()->create(['role' => 'agent']);

        JWTAuth::shouldReceive('parseToken->authenticate')->andReturn($user);

        $spy = \Mockery::spy(CandidateRepositoryContract::class);

        $useCase = new CreateCantidateUseCase($spy);

        $this->expectException(UnauthorizedUserException::class);

        $spy->shouldReceive('save')->with(CandidateMapper::toArray($attributes));

        $useCase($attributes);

        $spy->shouldHaveReceived()->save(CandidateMapper::toArray($attributes));
    }
}
