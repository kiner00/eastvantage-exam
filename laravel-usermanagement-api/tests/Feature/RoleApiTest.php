<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_roles()
    {
        Role::factory()->create(['name' => 'Author']);
        Role::factory()->create(['name' => 'Editor']);
        Role::factory()->create(['name' => 'Subscriber']);
        Role::factory()->create(['name' => 'Administrator']);

        $response = $this->getJson('/api/roles');

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Author'])
            ->assertJsonFragment(['name' => 'Editor'])
            ->assertJsonFragment(['name' => 'Subscriber'])
            ->assertJsonFragment(['name' => 'Administrator']);

    }
}
