<?php

use App\User;
use App\Company;
use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $mgr = User::where('name', 'Manager')->first();
        for ($i = 0; $i < 30; $i++) {
            $company = new Company();
            $company->name = $faker->company;
            $company->email = $faker->email;
            $company->logo = $faker->imageUrl;
            $company->website = $faker->domainName;
            $company->save();

            if ($i % 4 === 0) {
                $company->users()->attach($mgr);
            }
        }
    }
}
