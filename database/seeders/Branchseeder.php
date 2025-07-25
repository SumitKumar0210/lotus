<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Branchseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches')->insert([
            'branch_name' => 'RKR',
            'address' => 'Patna',
            'phone' => '9264447063',
            'email' => 'bwcrkr@gmail.com',
            'type' => "WAREHOUSE",
            'print_slug' => "rkr",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
