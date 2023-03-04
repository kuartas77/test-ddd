<?php

namespace Src\Shared\Domain\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

final class UnauthorizedUserException extends Exception
{
    public function __construct(string $customMessage = '', int $code = Response::HTTP_UNAUTHORIZED)
    {
        $message = 'The user is not authorized to access this resource or perform this action';
        parent::__construct($customMessage ?: $message, $code);
    }
}
