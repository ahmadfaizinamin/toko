<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInteface;
use Override;

class UserRepository implements UserRepositoryInteface
{
    #[Override]
    public function create(array $data)
    {
        return User::create($data);
    }
}