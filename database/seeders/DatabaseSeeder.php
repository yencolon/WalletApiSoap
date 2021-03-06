<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use LaravelDoctrine\ORM\Facades\EntityManager;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $user = new User('Jhon', 'Smith', '12345678', '22920784', 'jhon@email.com', '12345678');
        $wallet = new Wallet(0);
        $wallet->setUser($user);
        EntityManager::persist($user);
        EntityManager::persist($wallet);

        EntityManager::flush();
    }
}
