<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('optimize:clear');
        Artisan::call('permission:cache-reset');
        // \App\Models\User::factory(10)->create();
        //$this->call(Branchseeder::class);
        //$this->call(Userseeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RoleHasPermissionSeeder::class);
    }
}
