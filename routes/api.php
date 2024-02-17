<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('/register', [\App\Http\Controllers\Api\v1\AuthController::class, 'register'])->name('register');
    Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login'])->name('login');
    Route::middleware('auth:api')
        ->get('/logout', [\App\Http\Controllers\Api\v1\AuthController::class, 'logout'])->name('logout');
});
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::group(['middleware' => ['CheckRole','auth:api']], function () {

        Route::get('/roles', [\App\Http\Controllers\Api\v1\RoleController::class, 'index'])->name('role_index');
        Route::post('/roles', [\App\Http\Controllers\Api\v1\RoleController::class, 'store'])->name('role_store');
        Route::get('/roles/{id}', [\App\Http\Controllers\Api\v1\RoleController::class, 'show'])->name('role_show');
        Route::put('/roles/{id}', [\App\Http\Controllers\Api\v1\RoleController::class, 'update'])->name('role_update');
        Route::delete('/roles/{id}', [\App\Http\Controllers\Api\v1\RoleController::class, 'destroy'])->name('role_destroy');
        Route::get('/roles/{id}/users', [\App\Http\Controllers\Api\v1\RoleController::class, 'allUser'])->name('role_allUser');
        Route::post('/roles/{id}/users', [\App\Http\Controllers\Api\v1\RoleController::class, 'attachRole'])->name('role_attachRole');
        Route::delete('/roles/{id}/users', [\App\Http\Controllers\Api\v1\RoleController::class, 'detachRole'])->name('role_detachRole');

        Route::get('/users', [\App\Http\Controllers\Api\v1\userController::class, 'index'])->name('user_index');
        Route::get('/users/{id}', [\App\Http\Controllers\Api\v1\userController::class, 'show'])->name('user_show');
        Route::delete('/users/{id}', [\App\Http\Controllers\Api\v1\userController::class, 'destroy'])->name('user_destroy');
        Route::get('/users/{id}/roles', [\App\Http\Controllers\Api\v1\userController::class, 'allRoles'])->name('user_allRoles');
        Route::post('/users/{id}/roles', [\App\Http\Controllers\Api\v1\userController::class, 'attachRole'])->name('user_attachRole');
        Route::delete('/users/{id}/roles', [\App\Http\Controllers\Api\v1\userController::class, 'detachRole'])->name('user_detachRole');

        Route::group(['prefix' => 'tasks'], function () {
            Route::get('/', [\App\Http\Controllers\Api\v1\TaskController::class, 'index'])->name('task_index');
            Route::post('/', [\App\Http\Controllers\Api\v1\TaskController::class, 'store'])->name('task_store');
            Route::get('/{id}', [\App\Http\Controllers\Api\v1\TaskController::class, 'show'])->name('task_show');
            Route::put('/{id}', [\App\Http\Controllers\Api\v1\TaskController::class, 'update'])->name('task_update');
            Route::delete('/{id}', [\App\Http\Controllers\Api\v1\TaskController::class, 'destroy'])->name('task_destroy');

        });

        Route::get('/test2', function (Request $request) {
//            dd($request);
            return $request;
        });
    });

});
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
});


Route::get('/test', function (Request $request) {
    return config('roles.models.role');
});
