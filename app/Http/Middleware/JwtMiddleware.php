<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->error(['Token is Invalid'], Response::HTTP_UNAUTHORIZED );
            }else if ($e instanceof TokenExpiredException){
                return response()->error(['Token is Expired'], Response::HTTP_UNAUTHORIZED );
            }else if ($e instanceof JWTException){
                return response()->error(['Authorization Token not found'], Response::HTTP_UNAUTHORIZED );
            }else{
                return response()->error(['Authorization Token not found'], Response::HTTP_UNAUTHORIZED );
            }
        }
        return $next($request);
    }
}
