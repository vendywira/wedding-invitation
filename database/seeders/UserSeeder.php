<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'I Wayan Vendy Wiranatha',
            'email' => 'vendywira@gmail.com',
            'password' =>  bcrypt('root'),
        ]);
        User::create([
            'name' => 'Margaretha Magdalena Br Nainggolan',
            'email' => 'xelenalatuconsina@gmail.com',
            'password' =>  bcrypt('root'),
        ]);
    }
}
