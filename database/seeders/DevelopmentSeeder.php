<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment('local')) {
            User::create([
                'name' => 'Marco González Luengo',
                'email' => 'dev@nqu.me',
                'password' => '$2y$12$MQ2N0te/qeYIJ03oLmmjvO0bWGta0UxPnpxAxDr8oC5AXvJxTzlAC',
                'remember_token' => 'WkqOmRgEKTtlV0h1FlM0aOPMIjIBmdTixDv1qLNjZyaDZUwsrOcjBWxD9uPT',
            ]);
        }
    }
}
