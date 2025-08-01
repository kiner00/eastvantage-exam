<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\UserCreationException;
use App\Models\User;
use Throwable;

class UserService
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createUserWithRoles(array $data): User
    {
        try {
            $user = $this->user->create([
                'full_name' => $data['full_name'],
                'email' => $data['email'],
            ]);

            $user->roles()->sync($data['role_ids']);

            return $user->load('roles');
        } catch (\Throwable $e) {
            throw new UserCreationException("Failed to create user: " . $e->getMessage(), 0, $e);
        }
    }

    public function getUsersByRole(?int $roleId = null, int $perPage = 10)
    {
        $query = User::with('roles');

        if ($roleId) {
            $query->whereHas('roles', fn ($q) => $q->where('roles.id', $roleId));
        }

        return $query->paginate($perPage);
    }
}
