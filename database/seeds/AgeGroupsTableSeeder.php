<?php

use Illuminate\Database\Seeder;
use App\AgeGroup;

class AgeGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ageGroups = [
            'Vaikams',
            'Paaugliams',
            'Suaugusiems',
            'Pagyvenusiems žmonėms'
        ];

        foreach ($ageGroups as $ageGroup) {
            AgeGroup::create(['name' => $ageGroup]);
        }
    }
}
