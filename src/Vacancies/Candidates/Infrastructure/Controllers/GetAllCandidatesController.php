<?php declare(strict_types=1);

namespace Src\Vacancies\Candidates\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Contracts\Routing\ResponseFactory;
use Src\Vacancies\Candidates\Application\GetAllByOwnerUseCase;
use Src\Vacancies\Candidates\Application\GetAllCandidatesUseCase;
use Src\Vacancies\Candidates\Infrastructure\Repositories\EloquentCandidateRepository;

class GetAllCandidatesController extends Controller
{
    public function __construct(private EloquentCandidateRepository $repository)
    {
    }

    public function __invoke(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($user->role == 'manager') {
            $useCase = new GetAllCandidatesUseCase($this->repository);
        } elseif ($user->role == 'agent') {
            $useCase = new GetAllByOwnerUseCase($this->repository, $user->id);
        }

        return response()->success($useCase());
    }
}
