<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return User::create([
            'name' =>'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt(11111111),
            'image' => 'storage/man.png',
            'type' => '0',
            'phone' =>'113435',
            'address' => 'Mandalay',
            'dob' =>'1999.1.11',
            // 'create_user_id' => 1,
            // 'updated_user_id' => 1,
            // 'deleted_user_id' =>1
        ]);
    }
}
