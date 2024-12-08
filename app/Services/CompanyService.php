<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Collection;

class CompanyService implements InterfaceService
{
    public function __construct(private CompanyRepository $companyRepository)
    {
        //
    }

    public function update(Model $company, array $data): Model
    {
        return $this->companyRepository->update($company, $data);
    }

    public function create(array $data): Model
    {
        return $this->companyRepository->create($data);
    }

    public function getAll(array $columns = ['*']): Collection
    {
        return $this->companyRepository->getByConditions($columns);
    }

    public function storeByRelation(User $user, array $data): void
    {
        $user->companies()->create($data);
    }
}
