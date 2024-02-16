<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('/register', [\App\Http\Controllers\Api\v1\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login']);
    Route::middleware('auth:api')
        ->get('/logout', [\App\Http\Controllers\Api\v1\AuthController::class, 'logout']);
});
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
//    Route::middleware('auth:api')
//        ->get('/rols', [\App\Http\Controllers\Api\v1\AuthController::class, 'logout']);
//        ->get('/roles', function () {
    Route::get('/roles', [\App\Http\Controllers\Api\v1\RoleController::class, 'index']);
    Route::post('/roles', [\App\Http\Controllers\Api\v1\RoleController::class, 'store']);
    Route::get('/roles/{id}', [\App\Http\Controllers\Api\v1\RoleController::class, 'show']);
    Route::put('/roles/{id}', [\App\Http\Controllers\Api\v1\RoleController::class, 'update']);
    Route::delete('/roles/{id}', [\App\Http\Controllers\Api\v1\RoleController::class, 'destroy']);
    Route::get('/roles/{id}/users', [\App\Http\Controllers\Api\v1\RoleController::class, 'allUser']);
    Route::post('/roles/{id}/users', [\App\Http\Controllers\Api\v1\RoleController::class, 'attachRole']);
    Route::delete('/roles/{id}/users', [\App\Http\Controllers\Api\v1\RoleController::class, 'detachRole']);

    Route::get('/users', [\App\Http\Controllers\Api\v1\userController::class, 'index']);
    Route::get('/users/{id}', [\App\Http\Controllers\Api\v1\userController::class, 'show']);
    Route::delete('/users/{id}', [\App\Http\Controllers\Api\v1\userController::class, 'destroy']);
    Route::get('/users/{id}/roles', [\App\Http\Controllers\Api\v1\userController::class, 'allRoles']);
    Route::post('/users/{id}/roles', [\App\Http\Controllers\Api\v1\userController::class, 'attachRole']);
    Route::delete('/users/{id}/roles', [\App\Http\Controllers\Api\v1\userController::class, 'detachRole']);

});
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
//    Route::middleware('auth:api')
//        ->get('/rols', [\App\Http\Controllers\Api\v1\AuthController::class, 'logout']);
//        ->get('/roles', function () {

//    Route::get('/role_user', [\App\Http\Controllers\Api\v1\RoleUserController::class, 'index']);
//    Route::post('/role_user', [\App\Http\Controllers\Api\v1\RoleUserController::class, 'store']);
//    Route::get('/role_user/{id}', [\App\Http\Controllers\Api\v1\RoleUserController::class, 'show']);
//    Route::put('/role_user/{id}', [\App\Http\Controllers\Api\v1\RoleUserController::class, 'update']);
//    Route::delete('/role_user/{id}', [\App\Http\Controllers\Api\v1\RoleUserController::class, 'destroy']);
});


Route::get('/test', function (Request $request) {
//    return "000122";
    return config('roles.models.role');
});
