<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    public function getCompanies(User $user)
    {
        return $user->companies;
    }
}
