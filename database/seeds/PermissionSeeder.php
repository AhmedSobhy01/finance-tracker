<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        foreach (config('permission.permissions') as $key => $value) {
            foreach ($value as $permission) {
                $data[] = ['name' => $permission . ' ' . $key];
            }
        }

        foreach ($data as $v) {
            Permission::create($v);
        }
    }
}