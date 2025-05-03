<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * criar user furia_bot e team_furia_cs
     */
    public function run(): void
    {
        $this->call([
            FuriaBotSeeder::class,
        ]);
    }
}
