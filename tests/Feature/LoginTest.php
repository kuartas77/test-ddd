<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Tests\WithLogin;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithLogin;

    protected string $login_uri;
    protected string $logout_uri;
    protected string $refresh_uri;
    protected string $user_uri;
    protected array $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->login_uri = '/api/auth';
        $this->logout_uri = '/api/logout';
        $this->refresh_uri = '/api/refresh';
        $this->user_uri = '/api/user';
        $this->headers = ['Accept' => 'application/json'];
    }

    public function test_active_user_can_login()
    {
        $credentials = $this->validCredentials(['is_active' => true]);
        $credentials['password'] = 'password';

        $this->withHeaders($this->headers)
            ->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['token']);

        $this->assertAuthenticated();
    }

    public function test_inactive_user_cannot_login()
    {
        $credentials = $this->validCredentials(['is_active' => false]);
        $credentials['password'] = 'password';

        $this->withHeaders($this->headers)
            ->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertSee(['The selected username is invalid.']);
    }

    public function test_user_can_not_login_without_credentials()
    {
        $this->withHeaders($this->headers)
            ->post($this->login_uri, [])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertSee([
                'username' => 'The username field is required.',
                'password' => 'The password field is required.',
            ]);
    }

    public function test_user_can_not_login_without_username()
    {
        $credentials = $this->validCredentials();
        unset($credentials['username']);

        $this->withHeaders($this->headers)
            ->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertSee([
                'username' => 'The username field is required.',
            ]);
    }

    public function test_user_can_not_login_without_password()
    {
        $credentials = $this->validCredentials();

        $this->withHeaders($this->headers)
            ->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertSee([
                'password' => 'The password field is required.',
            ]);
    }

    public function test_user_can_not_login_with_invalid_credentials()
    {
        $credentials = ['username' => 'usernametest', 'password' => 'invalid'];

        $this->withHeaders($this->headers)
            ->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertSee(['The selected username is invalid']);
    }

    public function test_user_cannot_login_invalid_password()
    {
        $credentials = $this->validCredentials(['is_active' => true]);
        $credentials['password'] = 'password@@@';

        $this->withHeaders($this->headers)
            ->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee(['Password incorrect for:']);
    }

    public function test_manager_user_can_login()
    {
        $credentials = $this->validCredentials(['is_active' => true, 'role' => 'manager']);
        $credentials['password'] = 'password';

        $this->withHeaders($this->headers)
            ->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['token']);

        $this->assertAuthenticated();
    }

    public function test_agent_user_can_login()
    {
        $credentials = $this->validCredentials(['is_active' => true, 'role' => 'agent']);
        $credentials['password'] = 'password';

        $this->withHeaders($this->headers)
            ->post($this->login_uri, $credentials)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['token']);

        $this->assertAuthenticated();
    }

    public function test_user_can_logout()
    {
        $credentials = $this->newLoggedManager();

        $this->headers['Authorization'] = $credentials['token'];

        $this->withHeaders($this->headers)
            ->post($this->logout_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee('Successfully logged out');
    }

    public function test_user_can_refresh()
    {
        $credentials = $this->newLoggedManager();

        $this->headers['Authorization'] = $credentials['token'];

        $response = $this->withHeaders($this->headers)
            ->post($this->refresh_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertSee(['token']);

        $newToken = $this->getToken($response);

        $this->headers['Authorization'] = $newToken;

        $this->withHeaders($this->headers)
            ->get($this->user_uri)
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_token_invalid()
    {
        $credentials = $this->newLoggedManager();

        $this->headers['Authorization'] = $credentials['token'].'l';

        $this->withHeaders($this->headers)
            ->get($this->user_uri)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee('Token is Invalid');
    }

    public function test_token_not_found()
    {
        $credentials = $this->newLoggedManager();
        JWTAuth::shouldReceive('parseToken->invalidate');

        $this->headers['Authorization'] = $credentials['token'];

        $this->withHeaders($this->headers)
            ->post($this->logout_uri)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee('Authorization Token not found');
    }

    public function test_without_token()
    {
        $credentials = $this->newLoggedManager();
        JWTAuth::shouldReceive('parseToken->unsetToken');

        $this->headers['Authorization'] = '';

        $this->withHeaders($this->headers)
            ->post($this->logout_uri)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertSee('Authorization Token not found');
    }
    
}
