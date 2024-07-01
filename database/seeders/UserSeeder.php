<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'username' => 'test',
            'password' => Hash::make('test'),
            'name' => 'test',
            'token' => 'test', // token ini yang akan digunakan sebagai token akses Authorization di setiap endpoint
        ]);

        User::create([
            'username' => 'test2',
            'password' => Hash::make('test2'),
            'name' => 'test2',
            'token' => 'test2' // token ini yang akan digunakan sebagai token akses Authorization di setiap endpoint
        ]);
    }
}
