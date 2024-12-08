<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class UserService implements InterfaceService
{
    public function __construct(private UserRepository $userRepository)
    {

    }

    public function update(Model $user, array $data): Model
    {
        return $this->userRepository->update($user, $data);
    }

    public function create(array $data): Model
    {
        return $this->userRepository->create($data);
    }

    public function getAll(array $columns = ['*']): Collection
    {
        return $this->userRepository->get($columns);
    }
}
