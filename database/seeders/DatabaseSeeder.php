<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use MoonShine\Models\MoonshineUser;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        MoonshineUser::query()->create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@mail.ru',
            'moonshine_user_role_id' => 1,
            'password' => Hash::make(12345)
        ]);
    }
}
