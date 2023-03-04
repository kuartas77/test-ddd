<?php declare(strict_types=1);

namespace Src\Vacancies\Candidates\Infrastructure\Controllers;


use Illuminate\Database\QueryException;
use Src\Shared\Domain\Exceptions\UnauthorizedUserException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Src\Vacancies\Candidates\Application\CreateCantidateUseCase;
use Src\Vacancies\Candidates\Infrastructure\Requests\CreateUserRequest;
use Src\Vacancies\Candidates\Infrastructure\Repositories\EloquentCandidateRepository;

class CreateCantidateController extends Controller
{
    public function __construct(private EloquentCandidateRepository $repository)
    {
    }

    public function __invoke(CreateUserRequest $request)
    {
        $response = null;
        try {

            $useCase = new CreateCantidateUseCase($this->repository);
            $response = $useCase($request->validated());
            $response = response()->success($response, HttpResponse::HTTP_CREATED);

        } catch (QueryException $th) {
            $response = response()->error(["Candidate name already exists."]);
        } catch (UnauthorizedUserException $th) {
            $response = response()->error([$th->getMessage()], $th->getCode());
        }
        return $response;
    }
}
