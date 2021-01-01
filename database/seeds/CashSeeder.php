<?php

use App\Models\Cash;
use Illuminate\Database\Seeder;

class CashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Cash::class, 10)->create();
    }
}
