<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API_TestingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

//Route::get('/test',[App\Http\Controllers\API_TestingController::class,'Testing']);

$uri = current(explode('?', $_SERVER['REQUEST_URI']));
    if ($uri != null) {
        $uri = explode('/', $uri);
        array_shift($uri);
        if (count($uri) >= 3) {
            if ($uri[0] == 'api') {
                define('APP_CONTROLLER_NAME', $uri[1]);
                define('APP_ACTION_NAME', $uri[2]);
            }
        }
    }
