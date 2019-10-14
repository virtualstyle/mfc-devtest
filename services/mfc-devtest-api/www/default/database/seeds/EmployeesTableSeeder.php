<?php

use App\Company;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 30; $i++) {
            $companies = Company::all()->pluck('id')->toArray();

            App\Employee::create([
              'first_name' => $faker->firstName,
              'last_name' => $faker->lastName,
              'company' => $faker->randomElement($companies),
              'email' => $faker->email,
              'phone' => $faker->phoneNumber,
          ]);
        }
    }
}
