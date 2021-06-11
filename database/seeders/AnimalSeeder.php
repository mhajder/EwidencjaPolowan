<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('animals')->insert(
            array(
                // Species
                [
                    'id' => 1,
                    'name' => 'Jeleń',
                    'parent_id' => null,
                ],
                [
                    'id' => 2,
                    'name' => 'Daniel',
                    'parent_id' => null,
                ],
                [
                    'id' => 3,
                    'name' => 'Sarna',
                    'parent_id' => null,
                ],
                [
                    'id' => 4,
                    'name' => 'Muflon',
                    'parent_id' => null,
                ],
                [
                    'id' => 5,
                    'name' => 'Dzik',
                    'parent_id' => null,
                ],
                [
                    'id' => 6,
                    'name' => 'Lis',
                    'parent_id' => null,
                ],
                [
                    'id' => 7,
                    'name' => 'Jenot',
                    'parent_id' => null,
                ],
                [
                    'id' => 8,
                    'name' => 'Borsuk',
                    'parent_id' => null,
                ],
                [
                    'id' => 9,
                    'name' => 'Kuna',
                    'parent_id' => null,
                ],
                [
                    'id' => 10,
                    'name' => 'Norka amerykańska',
                    'parent_id' => null,
                ],
                [
                    'id' => 11,
                    'name' => 'Tchórz zwyczajny',
                    'parent_id' => null,
                ],
                [
                    'id' => 12,
                    'name' => 'Szop pracz',
                    'parent_id' => null,
                ],
                [
                    'id' => 13,
                    'name' => 'Piżmak',
                    'parent_id' => null,
                ],
                [
                    'id' => 14,
                    'name' => 'Zając szarak',
                    'parent_id' => null,
                ],
                [
                    'id' => 15,
                    'name' => 'Dziki królik',
                    'parent_id' => null,
                ],
                [
                    'id' => 16,
                    'name' => 'Jarząbek',
                    'parent_id' => null,
                ],
                [
                    'id' => 17,
                    'name' => 'Bażant',
                    'parent_id' => null,
                ],
                [
                    'id' => 18,
                    'name' => 'Kuropatwa',
                    'parent_id' => null,
                ],
                [
                    'id' => 19,
                    'name' => 'Dzika gęś',
                    'parent_id' => null,
                ],
                [
                    'id' => 20,
                    'name' => 'Dzika kaczka',
                    'parent_id' => null,
                ],
                [
                    'id' => 21,
                    'name' => 'Gołąb grzywacz',
                    'parent_id' => null,
                ],
                [
                    'id' => 22,
                    'name' => 'Słonka',
                    'parent_id' => null,
                ],
                [
                    'id' => 23,
                    'name' => 'Łyska',
                    'parent_id' => null,
                ],

                // Types
                [
                    'id' => 24,
                    'name' => 'byk selekcyjny',
                    'parent_id' => 1,
                ],
                [
                    'id' => 25,
                    'name' => 'byk I kl. wieku',
                    'parent_id' => 1,
                ],
                [
                    'id' => 26,
                    'name' => 'byk łowny',
                    'parent_id' => 1,
                ],
                [
                    'id' => 27,
                    'name' => 'byk II kl. wieku',
                    'parent_id' => 1,
                ],
                [
                    'id' => 28,
                    'name' => 'byk III kl. wieku',
                    'parent_id' => 1,
                ],
                [
                    'id' => 29,
                    'name' => 'łania',
                    'parent_id' => 1,
                ],
                [
                    'id' => 30,
                    'name' => 'cielę',
                    'parent_id' => 1,
                ],
                [
                    'id' => 31,
                    'name' => 'byk selekcyjny',
                    'parent_id' => 2,
                ],
                [
                    'id' => 32,
                    'name' => 'byk łowny',
                    'parent_id' => 2,
                ],
                [
                    'id' => 33,
                    'name' => 'byk I kl. wieku',
                    'parent_id' => 2,
                ],
                [
                    'id' => 34,
                    'name' => 'byk II kl. wieku',
                    'parent_id' => 2,
                ],
                [
                    'id' => 35,
                    'name' => 'byk III kl. wieku',
                    'parent_id' => 2,
                ],
                [
                    'id' => 36,
                    'name' => 'łania',
                    'parent_id' => 2,
                ],
                [
                    'id' => 37,
                    'name' => 'cielę',
                    'parent_id' => 2,
                ],
                [
                    'id' => 38,
                    'name' => 'rogacz selekcyjny',
                    'parent_id' => 3,
                ],
                [
                    'id' => 39,
                    'name' => 'kozioł I kl. wieku',
                    'parent_id' => 3,
                ],
                [
                    'id' => 40,
                    'name' => 'kozioł II kl. wieku',
                    'parent_id' => 3,
                ],
                [
                    'id' => 41,
                    'name' => 'rogacz łowny',
                    'parent_id' => 3,
                ],
                [
                    'id' => 42,
                    'name' => 'koza',
                    'parent_id' => 3,
                ],
                [
                    'id' => 43,
                    'name' => 'koźlę',
                    'parent_id' => 3,
                ],
                [
                    'id' => 44,
                    'name' => 'tryk',
                    'parent_id' => 4,
                ],
                [
                    'id' => 45,
                    'name' => 'owca',
                    'parent_id' => 4,
                ],
                [
                    'id' => 46,
                    'name' => 'jagnię',
                    'parent_id' => 4,
                ],
                [
                    'id' => 47,
                    'name' => 'odyniec',
                    'parent_id' => 5,
                ],
                [
                    'id' => 48,
                    'name' => 'dzik',
                    'parent_id' => 5,
                ],
                [
                    'id' => 49,
                    'name' => 'locha',
                    'parent_id' => 5,
                ],
                [
                    'id' => 50,
                    'name' => 'wycinek',
                    'parent_id' => 5,
                ],
                [
                    'id' => 51,
                    'name' => 'przelatek',
                    'parent_id' => 5,
                ],
            )
        );
    }
}
