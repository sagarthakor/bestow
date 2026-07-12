<?php

use Illuminate\Database\Seeder;

class RawMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $materialGroup = [
            [
                'group_name' => 'Cotton',
                'uom' => 'KG'
            ],
            [
                'group_name' => 'Spendex',
                'uom' => 'KG'
            ],
            [
                'group_name' => 'Nylon',
                'uom' => 'KG'
            ],
            [
                'group_name' => 'Polyester',
                'uom' => 'KG'
            ],
            [
                'group_name' => 'P_P_Yarn',
                'uom' => 'KG'
            ]
        ];
        \App\raw_material_group::insert($materialGroup);
    }
}
