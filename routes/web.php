<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GamesController;

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

Route::get('/', function () {
       return view('Auth/Adminlogin');
});


Route::post('admin_login', [AdminController::class, 'admin_login']);
Route::get('dashboard', [AdminController::class, 'GetDashboard']);
Route::get('logout', [AdminController::class, 'GetLogout']);

//USERS ROUTE
Route::get('users', [UserController::class, 'GetUsers']);
Route::get('users/get-profile/{id}', [UserController::class, 'GetUsersProfile']);
Route::post('update-user', [UserController::class, 'UpdateUsersProfile']);
Route::post('delete-user', [UserController::class, 'DeleteUsersProfile']);
Route::post('block-user', [UserController::class, 'BUUsersProfile']);

//CATEGORY
Route::get('category', [CategoryController::class, 'GetCategory']);
Route::post('update-category', [CategoryController::class, 'UpdateCategory']);
Route::post('delete-category', [CategoryController::class, 'DeleteCategory']);
Route::post('add-category', [CategoryController::class, 'AddCategory']);

//CONTENT
Route::get('games', [GamesController::class, 'GetGames']);     
Route::post('update-games', [GamesController::class, 'UpdateGames']);
Route::post('delete-games', [GamesController::class, 'DeleteGames']);
Route::post('add-games', [GamesController::class, 'AddGames']);
Route::get('edit-games/{id}', [GamesController::class, 'EditGames']);
