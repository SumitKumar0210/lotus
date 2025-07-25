<?php

namespace App\Http\Controllers\WareHouse;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {

//        Permission::create(['name' => 'create purchase','guard'=>'web']);
//        Permission::create(['name' => 'last purchase','guard'=>'web']);
//        Permission::create(['name' => 'all purchase','guard'=>'web']);
//        Role::create(['name' => 'purchase role','guard'=>'web']);
//
//        $permissions = Permission::get();
//        $role = Role::where('name', 'purchase role')->first();
//        $role->syncPermissions($permissions);

        return view('backend.warehouse.dashboard');
    }
}
