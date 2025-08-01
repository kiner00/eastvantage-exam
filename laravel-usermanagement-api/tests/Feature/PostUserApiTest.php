<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostUserApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_user_with_roles()
    {
        $role1 = Role::factory()->create(['name' => 'Author']);
        $role2 = Role::factory()->create(['name' => 'Editor']);

        $response = $this->postJson('/api/users', [
            'full_name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role_ids' => [$role1->id, $role2->id],
        ]);

        $response->assertCreated()
            ->assertJsonFragment(['email' => 'jane@example.com', 'full_name' => 'Jane Doe']);

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $this->assertDatabaseHas('role_user', ['role_id' => $role1->id]);
    }

    /**
     * @test
     * @dataProvider invalidUserPayloads
     */
    public function it_returns_validation_errors_for_invalid_input(array $payload, array $expectedErrors)
    {
        $response = $this->postJson('/api/users', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors($expectedErrors);
    }

    public function invalidUserPayloads(): array
    {
        return [
            'missing all fields' => [
                [],
                ['full_name', 'email', 'role_ids'],
            ],
            'missing full name' => [
                ['email' => 'a@example.com', 'role_ids' => [1]],
                ['full_name'],
            ],
            'invalid email format' => [
                ['full_name' => 'Test', 'email' => 'not-an-email', 'role_ids' => [1]],
                ['email'],
            ],
            'non-existent role_ids' => [
                ['full_name' => 'Test', 'email' => 'a@example.com', 'role_ids' => [999]],
                ['role_ids.0'],
            ],
        ];
    }

    /** @test */
    public function it_rejects_duplicate_email()
    {
        $role = Role::factory()->create(['name' => 'Admin']);
        User::factory()->create(['email' => 'duplicate@example.com']);

        $response = $this->postJson('/api/users', [
            'full_name' => 'Duplicate User',
            'email' => 'duplicate@example.com',
            'role_ids' => [$role->id],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
