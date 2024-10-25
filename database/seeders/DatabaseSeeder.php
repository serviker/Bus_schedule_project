<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Убедитесь, что пользователь с таким email существует
        User::firstOrCreate([
            'email' => 'test@example.com'
        ], [
            'name' => 'Test User',
            'password' => bcrypt('password'), // Не забудьте установить пароль
        ]);

        // Вызов сидера для маршрутов и автобусов
        $this->call(BusStopSeeder::class);
    }
}
