<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Tests\WithLogin;
use App\Models\Candidate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeadTest extends TestCase
{
    use RefreshDatabase, WithLogin;

    protected string $create_uri;
    protected string $get_all_uri;
    protected string $get_one_uri;
    protected array $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->create_uri = '/api/lead';
        $this->get_all_uri = '/api/leads';
        $this->get_one_uri = '/api/lead';
        $this->headers = ['Accept' => 'application/json'];
    }

    function test_manager_user_can_create_candidate_and_validate_structure_response()
    {
        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        $request_params = [
            "name" => $this->faker()->name(),
            "source" => $this->faker()->text(20),
            "owner" => $agent['id']
        ];

        $this->headers['Authorization'] = $manager['token'];

        $this->withHeaders($this->headers)
            ->post($this->create_uri, $request_params)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'meta' => [
                    'success',
                    'errors'
                ],
                'data' => [
                    'id',
                    'name',
                    'source',
                    'owner',
                    'created_at',
                    'created_by'
                ]
            ]);
    }

    function test_manager_user_cannot_create_candidate_without_name()
    {
        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        $request_params = [
            "name" => $this->faker()->name(),
            "source" => $this->faker()->text(20),
            "owner" => $agent['id']
        ];
        unset($request_params['name']);

        $this->headers['Authorization'] = $manager['token'];

        $this->withHeaders($this->headers)
            ->post($this->create_uri, $request_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertSee('The name field is required.');
    }

    function test_manager_user_cannot_create_candidate_without_source()
    {
        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        $request_params = [
            "name" => $this->faker()->name(),
            "source" => $this->faker()->text(20),
            "owner" => $agent['id']
        ];
        unset($request_params['source']);

        $this->headers['Authorization'] = $manager['token'];

        $this->withHeaders($this->headers)
            ->post($this->create_uri, $request_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertSee('The source field is required.');
    }

    function test_manager_user_cannot_create_candidate_without_owner()
    {
        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        $request_params = [
            "name" => $this->faker()->name(),
            "source" => $this->faker()->text(20),
            "owner" => $agent['id']
        ];
        unset($request_params['owner']);

        $this->headers['Authorization'] = $manager['token'];

        $this->withHeaders($this->headers)
            ->post($this->create_uri, $request_params)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertSee('The owner field is required.');
    }

    function test_manager_user_cannot_create_candidate_with_same_name()
    {
        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        $name = $this->faker()->name();

        Candidate::factory()->create([
            'name' => $name,
            'owner' => $agent['id'],
            'created_by' => $manager['id']
        ]);

        $request_params = [
            "name" => $name,
            "source" => $this->faker()->text(20),
            "owner" => $agent['id']
        ];

        $this->headers['Authorization'] = $manager['token'];

        $this->withHeaders($this->headers)
            ->post($this->create_uri, $request_params)
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertSee("Candidate name already exists.");
    }

    function test_manager_can_get_total_register_candidates()
    {
        $total = 20;

        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        Candidate::factory($total)->create([
            'owner' => $agent['id'],
            'created_by' => $manager['id']
        ]);

        $this->headers['Authorization'] = $manager['token'];

        $this->withHeaders($this->headers)
            ->get($this->get_all_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($total, 'data');
    }

    function test_manager_can_get_one_candidate_by_id()
    {
        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        $model = Candidate::factory(1)->create([
            'owner' => $agent['id'],
            'created_by' => $manager['id']
        ]);

        $this->headers['Authorization'] = $manager['token'];

        $this->withHeaders($this->headers)
            ->get("{$this->get_one_uri}/{$model[0]->id}")
            ->assertStatus(Response::HTTP_OK);
    }

    function test_agent_user_cannot_create_candidate_and_validate_structure_response()
    {
        $agent = $this->newLoggedAgent();

        $request_params = [
            "name" => $this->faker()->name(),
            "source" => $this->faker()->text(20),
            "owner" => $agent['id']
        ];

        $this->headers['Authorization'] = $agent['token'];

        $this->withHeaders($this->headers)
            ->post($this->create_uri, $request_params)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'meta' => [
                    'success' => false,
                    'errors' => [
                        'The user is not authorized to access this resource or perform this action'
                    ]
                ]
            ]);
    }

    function test_agent_cannot_get_total_register_candidates()
    {
        $total = 10;

        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        Candidate::factory($total)->create([
            'owner' => $manager['id'],
            'created_by' => $manager['id']
        ]);

        $this->headers['Authorization'] = $agent['token'];

        $this->withHeaders($this->headers)
            ->get($this->get_all_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');
    }

    function test_agent_can_get_total_register_candidates_by_owner()
    {
        $total = 10;

        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        Candidate::factory($total)->create([
            'owner' => $agent['id'],
            'created_by' => $manager['id']
        ]);

        $this->headers['Authorization'] = $agent['token'];

        $this->withHeaders($this->headers)
            ->get($this->get_all_uri)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount($total, 'data');
    }

    function test_agent_can_get_one_candidate_by_id_being_owner()
    {
        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        $model = Candidate::factory(1)->create([
            'owner' => $agent['id'],
            'created_by' => $manager['id']
        ]);

        $this->headers['Authorization'] = $agent['token'];

        $this->withHeaders($this->headers)
            ->get("{$this->get_one_uri}/{$model[0]->id}")
            ->assertStatus(Response::HTTP_OK);
    }

    function test_agent_cannot_get_one_candidate_by_id_without_owner()
    {
        $manager = $this->newLoggedManager();
        $agent = $this->newLoggedAgent();

        $model = Candidate::factory(1)->create([
            'owner' => $manager['id'],
            'created_by' => $manager['id']
        ]);

        $this->headers['Authorization'] = $agent['token'];

        $this->withHeaders($this->headers)
            ->get("{$this->get_one_uri}/{$model[0]->id}")
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertSee('No lead found');
    }
}
