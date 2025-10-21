<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $statuses = ['to-do', 'in-progress', 'done'];
        $userIds = [1, 2];

        $tasks = [];

        for ($i = 0; $i < 20; $i++) {
            $tasks[] = [
                'title' => $faker->sentence(6, true),
                'description' => $faker->paragraph(2, true),
                'status' => $statuses[array_rand($statuses)],
                'user_id' => $userIds[array_rand($userIds)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tasks')->insert($tasks);
    }
}
