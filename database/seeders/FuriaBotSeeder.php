<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FuriaBotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário bot com ID fixo 1
        User::updateOrCreate(
            ['id' => 1],
            [
                'username' => 'furia_bot',
                'name' => 'FURIA Bot',
                'email' => 'bot@furia.com',
                'password' => Hash::make(Str::random(64)),
                'avatar' => 'https://pbs.twimg.com/profile_images/1774820117538295808/xilo38_v_400x400.png',
                'profile_url' => '',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // Criar time FURIA com ID fixo 1
        Team::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'FURIA CS',
                'slug' => 'team_furia_cs',
                'owner_id' => 1, // Dono é o bot
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        DB::table('teams_users')->insert([
            'team_id' => 1,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::statement("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));");
    }
}
