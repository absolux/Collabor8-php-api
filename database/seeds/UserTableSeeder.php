<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserTableSeeder extends Seeder {


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        User::create([
            'name' => "absolux",
            'email' => 'foo@bar.com',
            'password' => Hash::make('secret')
        ]);
    }
}