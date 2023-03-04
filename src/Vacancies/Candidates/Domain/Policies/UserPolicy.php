<?php

namespace Src\Vacancies\Candidates\Domain\Policies;

use Tymon\JWTAuth\Facades\JWTAuth;

class UserPolicy
{
    public static function find(): bool
    {
        return (bool)JWTAuth::parseToken();
    }

    public static function findOneByOwner(): bool
    {
        return JWTAuth::parseToken()->authenticate()->role == 'agent';
    }

    public static function findAllByOwner(): bool
    {
        return JWTAuth::parseToken()->authenticate()->role == 'agent';
    }

    public static function all(): bool
    {
        return JWTAuth::parseToken()->authenticate()->role == 'manager';
    }

    public static function save(): bool
    {
        return JWTAuth::parseToken()->authenticate()->role == 'manager';
    }
}
