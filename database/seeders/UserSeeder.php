<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'vendywira@gmail.com'],
            [
                'name' => 'I Wayan Vendy Wiranatha',
                'password' => bcrypt('root'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'xelenalatuconsina@gmail.com'],
            [
                'name' => 'Margaretha Magdalena Br Nainggolan',
                'password' => bcrypt('root'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@weddingstory.com'],
            [
                'name' => 'Admin Wedding Story',
                'password' => bcrypt('root'),
            ]
        );
    }
}
