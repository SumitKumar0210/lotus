<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ApiController::class, 'login']);


Route::group(['prefix' => 'branch', 'middleware' => ['auth:sanctum'],], function () {

    Route::post('branch-stock-list-search-term', [ApiController::class, 'getBranchStockListSearch']);
    Route::post('branch-stock-list-search-result', [ApiController::class, 'getBranchStockListBySearch']);
    Route::post('consolidate-list', [ApiController::class, 'getConsolidateList']);
});

 
// Route::post('login', 'Api\ApiController@login');
