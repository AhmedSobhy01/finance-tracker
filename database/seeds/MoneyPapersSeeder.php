<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoneyPapersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('money_papers')->insert(
            [
                ['amount' => 0.50],
                ['amount' => 1.00],
                ['amount' => 5.00],
                ['amount' => 10.00],
                ['amount' => 20.00],
                ['amount' => 50.00],
                ['amount' => 100.00],
                ['amount' => 200.00],
            ]);
    }
}