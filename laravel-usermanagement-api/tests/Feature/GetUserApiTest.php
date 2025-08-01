<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetUserApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_return_all_users_with_roles_paginated()
    {
        $role = Role::factory()->create(['name' => 'Subscriber']);

        User::factory()
            ->count(15)
            ->sequence(fn ($i) => ['full_name' => 'User ' . $i->index])
            ->create()
            ->each(fn ($user) => $user->roles()->attach($role));

        $response = $this->getJson('/api/users?per_page=10');

        $response->assertOk()
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function it_can_filter_users_by_role_id()
    {
        $author = Role::factory()->create(['name' => 'Author']);
        $editor = Role::factory()->create(['name' => 'Editor']);

        $userA = User::factory()->create(['full_name' => 'Alice']);
        $userA->roles()->attach($author);

        $userB = User::factory()->create(['full_name' => 'Bob']);
        $userB->roles()->attach($editor);

        $response = $this->getJson('/api/users?role_id=' . $author->id);

        $response->assertOk()
            ->assertJsonFragment(['full_name' => 'Alice'])
            ->assertJsonMissing(['full_name' => 'Bob']);
    }

    /** @test */
    public function it_returns_empty_data_when_no_users_exist()
    {
        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJsonCount(0, 'data')
            ->assertJsonPath('meta.total', 0);
    }

    /** @test */
    public function it_returns_paginated_results_with_default_per_page()
    {
        $role = Role::factory()->create(['name' => 'Admin']);

        User::factory()
            ->count(25)
            ->create()
            ->each(fn ($user) => $user->roles()->attach($role));

        $response = $this->getJson('/api/users'); // default per_page = 10

        $response->assertOk()
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function it_can_return_users_on_second_page()
    {
        $role = Role::factory()->create(['name' => 'Editor']);

        User::factory()
            ->count(30)
            ->sequence(fn ($i) => ['full_name' => 'User ' . $i->index])
            ->create()
            ->each(fn ($user) => $user->roles()->attach($role));

        $response = $this->getJson('/api/users?page=2&per_page=15');

        $response->assertOk()
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonCount(15, 'data');
    }
}
