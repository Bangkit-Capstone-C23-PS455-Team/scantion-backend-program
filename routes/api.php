<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\skincontroller;

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
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/deleteuser', [\App\Http\Controllers\Api\AuthController::class, 'deleteuser']);
    Route::post('/update/{id?}', [\App\Http\Controllers\Api\AuthController::class, 'update']);
    Route::post('/addskin/{id?}', [\App\Http\Controllers\Api\AuthController::class, 'addskin']);
    Route::post('/store', [\App\Http\Controllers\Api\AuthController::class, 'store']);
    Route::get('/skin',[\App\Http\Controllers\Api\AuthController::class, 'skinmodel']);
});

// Route::get('/allusers',[usercontroller::class, 'alldata']);
// Route::get('/users',[usercontroller::class, 'usersonly']);
// Route::get('/users/{id?}',[usercontroller::class, 'show']);
// Route::get('/skin',[skincontroller::class, 'skinmodel']);

// Route::post('/addUsers',[usercontroller::class, 'add']);
// Route::post('/addSkin/{id?}',[skincontroller::class, 'addSkin']);


// Route::delete('/delete/{id?}',[usercontroller::class, 'deleteuser']);
// Route::delete('/deleteSkin/{id?}',[skincontroller::class, 'deleteskin']);

// Route::put('/editUsers/{id?}',[usercontroller::class, 'edit']);

Route::fallback(function () {
    return view('error');
});
