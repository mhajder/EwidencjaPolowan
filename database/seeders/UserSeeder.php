<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            array(
                'username' => 'akowalski',
                'first_name' => 'Andrzej',
                'last_name' => 'Kowalski',
                'pesel' => '71010852424',
                'email' => 'akowalski@localhost',
                'street' => 'Kocia',
                'house_number' => '12/3',
                'zip_code' => '12-345',
                'city' => 'Warszawa',
                'phone' => '123 123 123',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'permission' => 9,
                'selected_district' => 1,
                'disabled' => false,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            )
        );
    }
}
