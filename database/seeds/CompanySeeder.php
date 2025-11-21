<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = [
            'company_name' => 'Yogi Traders',
            'address' => 'Vadodara',
            'logo' => 'logo.png',
        ];

        \App\company::insert($company);
    }
}
