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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            /* call seeder list */
            $this->call(FoldersTableSeeder::class)
        // 他のシーダーも必要に応じて追加
        ]);
    }
}
// runメソッド内に追加する
$this->call(TasksTableSeeder::class);
// runメソッド内に追加して順番を入れ替える
$this->call(UsersTableSeeder::class);
$this->call(FoldersTableSeeder::class);
$this->call(TasksTableSeeder::class);
