<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ApplicationSettingsSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PersonSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(CashSeeder::class);
        $this->call(MoneyPapersSeeder::class);
    }
}
