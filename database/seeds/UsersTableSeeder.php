<?php

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => '5b6247a4-d350-4fdc-bacd-2cd56c5db564',
            'name' => 'User',
            'email' => 'user@app.com',
            'password' => bcrypt('user'),
            'customer_code' => 'A12BC34',
            'plan_id' => Arr::random(Plan::pluck('id')->toArray()),
            'created_at' => now()
        ]);

        factory(User::class, 500)->create();
    }
}
