<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
        [
            'first_name' => 'Ben',
            'last_name' => 'Borla',
            'salutation' => 'Mr.',
            'email' => 'ben@wml-solutions.com',
            'plain' => 'admin123',
            'password' => bcrypt('admin123'),
            'role_id' => 1,
            'category_id' => 18,
            'company_id' => 1,
            'avatar' => null,
            'reload_credentials' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'disabled' => 0,
            'is_employee' => 1,
            'overhead' => 0
        ]);
    }
}
