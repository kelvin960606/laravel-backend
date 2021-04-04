<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Route::get('/v1/test','App\Http\Controllers\API_TestingController@Testing');

$Return = DB::table('t_menus_api_url_controller')
    ->select('*')
    ->where('xStatus','1')
    ->get();
foreach ($Return as $Val){
    $ver = '/'.$Val->xVersion.'/';
    switch ($Val->xMethod){
        case 'get':
            Route::get($ver.$Val->xCode,"App\\Http\\Controllers\\".$Val->xToController);
            break;
        case 'post':
            Route::post($ver.$Val->xCode,"App\\Http\\Controllers\\".$Val->xToController);
            break;
    }
}
