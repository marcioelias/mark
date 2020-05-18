<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            'id' => 'e3a1758a-bd31-4ce1-91cb-49ca9a5bf276',
            'name' => 'Administrator',
            'email' => 'admin@app.com',
            'username' => 'admin',
            'password' => bcrypt('admin')
        ]);
    }
}
