<?php

namespace Tests\Unit;

use App\Exceptions\UserCreationException;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;
use Mockery;
use Exception;

class UserServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function it_creates_user_and_assigns_roles()
    {
        $mockUser = Mockery::mock(User::class);
        $mockRelation = Mockery::mock(BelongsToMany::class);

        $mockRelation->shouldReceive('sync')->once()->with([1, 2]);
        $mockUser->shouldReceive('roles')->andReturn($mockRelation);
        $mockUser->shouldReceive('load')->andReturnSelf();

        $mockUserModel = Mockery::mock(User::class);
        $mockUserModel->shouldReceive('create')->once()->andReturn($mockUser);

        $service = new UserService($mockUserModel);

        $user = $service->createUserWithRoles([
            'full_name' => 'Mock User',
            'email' => 'mock@example.com',
            'role_ids' => [1, 2],
        ]);

        $this->assertSame($mockUser, $user);
    }

    /** @test */
    public function it_throws_exception_if_user_creation_fails()
    {
        $this->expectException(UserCreationException::class);

        $mockUserModel = Mockery::mock(User::class);
        $mockUserModel->shouldReceive('create')->andThrow(new Exception('DB error'));

        $service = new UserService($mockUserModel);

        $service->createUserWithRoles([
            'full_name' => 'Failing User',
            'email' => 'fail@example.com',
            'role_ids' => [1],
        ]);
    }
}
