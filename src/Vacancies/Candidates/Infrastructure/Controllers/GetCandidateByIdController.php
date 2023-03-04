<?php

declare(strict_types=1);

namespace Src\Vacancies\Candidates\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Src\Vacancies\Candidates\Application\GetCandidateUseCase;
use Src\Vacancies\Candidates\Application\GetCandidateByOwnerUseCase;
use Src\Vacancies\Candidates\Infrastructure\Repositories\EloquentCandidateRepository;

class GetCandidateByIdController extends Controller
{
    public function __construct(private EloquentCandidateRepository $repository)
    {
    }

    public function __invoke(Request $request, $id)
    {
        $response = null;
        try {

            $user = JWTAuth::parseToken()->authenticate();

            if ($user->role == 'manager') {
                $useCase = new GetCandidateUseCase($this->repository);
            } elseif ($user->role == 'agent') {
                $useCase = new GetCandidateByOwnerUseCase($this->repository, $user->id);
            }

            $response = response()->success($useCase((int)$id));
        } catch (ModelNotFoundException $th) {
            $response = response()->error(["No lead found"], 404);
        }
        return $response;
    }
}
