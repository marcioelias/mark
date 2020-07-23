<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->truncateTables();
        Schema::enableForeignKeyConstraints();
        $this->call(PlansTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(PlataformSeeder::class);
        $this->call(VariablesTableSeeder::class);
        $this->call(ActionTypesTableSeeder::class);
        $this->call(LeadStatusesTableSeeder::class);
    }

    public function truncateTables() {
        DB::table('admins')->truncate();
        DB::table('users')->truncate();
        DB::table('plans')->truncate();
        DB::table('plataforms')->truncate();
        DB::table('variables')->truncate();
        DB::table('action_types')->truncate();
        DB::table('lead_statuses')->truncate();
    }
}
