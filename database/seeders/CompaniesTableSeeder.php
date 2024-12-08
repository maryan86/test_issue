<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    public function run(): void
    {
        Company::factory()
            ->count(10)
            ->create();
    }
}
