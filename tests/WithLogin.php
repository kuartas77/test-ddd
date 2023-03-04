<?php

namespace Tests;

use App\Models\User;
use Illuminate\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;

trait WithLogin
{
    use WithFaker;

    protected function validCredentials(array $attributes = null): array
    {
        $user = User::factory()->create($attributes);

        return $user->toArray();
    }

    protected function newLoggedManager(): array
    {
        $credentials = $this->validCredentials(['role' => 'manager']);
        $credentials['password'] = 'password';
        $response = $this->post('/api/auth', $credentials, ['Accept' => 'application/json']);
        return ['token' => $this->getToken($response), ...$credentials];
    }

    protected function newLoggedAgent(): array
    {
        $credentials = $this->validCredentials(['role' => 'agent']);
        $credentials['password'] = 'password';
        $response = $this->post('/api/auth', $credentials, ['Accept' => 'application/json']);
        return ['token' => $this->getToken($response), ...$credentials];
    }

    protected function getToken(TestResponse $response)
    {
        $arResponse = json_decode($response->getContent(), true);
        return $arResponse['data']['token'];
    }
}