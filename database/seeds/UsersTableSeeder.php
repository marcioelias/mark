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
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'user@app.com',
                'password' => bcrypt('user'),
                'customer_code' => 'A12BC34',
                'plan_id' => Arr::random(Plan::pluck('id')->toArray()),
                'email_verified_at' => now(),
                'first_login_at' => now(),
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane@app.com',
                'password' => bcrypt('jane'),
                'customer_code' => 'A12BC35',
                'plan_id' => Arr::random(Plan::pluck('id')->toArray()),
                'email_verified_at' => now(),
                'first_login_at' => now(),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        factory(User::class, 100)->create();
    }
}
