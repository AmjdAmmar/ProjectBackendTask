<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\admin\productController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\UserController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Route::resource("/product", [\App\Http\Controllers\productsController::class]);
Route::get('/product', [productController::class, 'index']);
Route::get('/product/{id}', [productController::class, 'show']);
Route::get('/product/{id}/edit', [productController::class, 'edit']);
Route::post('/product/{id}', [productController::class, 'update']); 
Route::delete('/product/{id}', [productController::class, 'delete']);

Route::get('/Category', [CategoryController::class, 'index']);
Route::get('/Category/{id}', [CategoryController::class, 'show']);
Route::get('/Category/{id}/edit', [CategoryController::class, 'edit']);
Route::post('/Category/{id}', [CategoryController::class, 'update']); 
Route::delete('/Category/{id}', [CategoryController::class, 'delete']);


Route::get('/User', [UserController::class, 'index']);
Route::get('/User/{id}', [UserController::class, 'show']);
Route::get('/User/{id}/edit', [UserController::class, 'edit']);
Route::post('/User/{id}', [UserController::class, 'update']); 
Route::delete('/User/{id}', [UserController::class, 'delete']);

// Route::get('/product','productsController@index');