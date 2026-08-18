<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'stevanovic.viktor@gmail.com'],
            [
                'name' => 'Viktor Stevanovic',
                'nickname' => 'Vik',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ],
        );

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'job_title' => 'Backend Developer',
                'tagline' => 'Building things for the web',
                'bio' => 'Experienced developer specializing in Laravel and modern JavaScript frameworks. Passionate about clean code, thoughtful architecture, and shipping products that people enjoy using.',
                'github_url' => 'https://github.com/ViktorStevanovic',
                'linkedin_url' => 'https://www.linkedin.com/in/viktor-stevanovic-d28/',
                'email_public' => 'stevanovic.viktor@gmail.com',
            ],
        );
    }
}
