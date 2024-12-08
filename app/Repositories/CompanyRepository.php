<?php

namespace App\Repositories;

use App\Models\Company;

class CompanyRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return Company::class;
    }
}
