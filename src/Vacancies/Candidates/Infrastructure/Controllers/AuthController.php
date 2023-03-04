<?php

namespace Src\Vacancies\Candidates\Infrastructure\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'jwt.verify'], ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => [
                'required',
                'string',
                Rule::exists('users')->where(fn ($query) => $query->where('is_active', true))
            ],
            'password' => ['required', 'string'],
        ]);

        $token = Auth::attempt($request->only('username', 'password'));

        if (!$token) {
            $username = $request->string('username');
            return response()->error([
                "Password incorrect for: {$username}"
            ], HttpResponse::HTTP_UNAUTHORIZED);
        }

        Auth::user()->update(['last_login' => now()]);

        return response()->success([
            'token' => "Bearer {$token}",
            'minutes_to_expire' => config('jwt.ttl')
        ], HttpResponse::HTTP_OK);
    }

    public function logout()
    {
        Auth::logout();
        return response()->success([
            'message' => 'Successfully logged out',
        ], HttpResponse::HTTP_OK);
    }

    public function refresh()
    {
        $token = Auth::refresh();
        return response()->success([
            'token' => "Bearer {$token}",
            'minutes_to_expire' => config('jwt.ttl'),
        ], HttpResponse::HTTP_OK);
    }

}
