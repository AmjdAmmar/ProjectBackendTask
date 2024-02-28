<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\productsController;
use App\Http\Controllers\api\CategoriesController;
use App\Http\Controllers\api\UsersController;


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
Route::prefix('v1/')->group(function () {
    Route::apiResource('product', productsController::class);
    Route::apiResource('Category', CategoriesController::class);
    Route::apiResource('User', UsersController::class);
    // Route::get('product', [productsController::class,'index']);
    // Route::get('product/{id}', [productsController::class,'show']);
    // Route::post('product', [productsController::class,'store']);
    // Route::post('product/{id}/delete', [productsController::class,'destroy']);




    // Route::get('Category', [CategoriesController::class,'index']);
    // Route::get('Category/{id}', [CategoriesController::class,'show']);
    // Route::post('Category/{id}', [CategoriesController::class,'update']);
    // Route::post('Category', [CategoriesController::class,'store']);
    // Route::post('Category/{id}/delete', [CategoriesController::class,'destroy']);


    // Route::get('User', [UsersController::class,'index']);
    // Route::get('User/{id}', [UsersController::class,'show']);
    // Route::post('User/{id}', [UsersController::class,'update']);
    // Route::post('User', [UsersController::class,'store']);
    // Route::post('User/{id}/delete', [UsersController::class,'destroy']);



});
