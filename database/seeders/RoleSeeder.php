<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('roles')->insert([
        //     'name' => 'purchase role',
        //     'guard_name' => 'web',
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now(),
        // ]);


        DB::table('roles')->insert([
            'name' => 'product role',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
