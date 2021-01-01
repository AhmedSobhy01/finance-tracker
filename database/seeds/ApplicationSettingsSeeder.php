<?php

use App\Models\ApplicationSettings;
use Illuminate\Database\Seeder;

class ApplicationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicationSettings::insert([
            ['key' => 'owner', 'value' => env('OWNER', '')],
            ['key' => 'pagination_count', 'value' => env('PAGINATION_COUNT', 50)],
            ['key' => 'currency', 'value' => env('CURRENCY', '$')],
            ['key' => 'start_date', 'value' => Carbon\Carbon::now()],
        ]);
    }
}
