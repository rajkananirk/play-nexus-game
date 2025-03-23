<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FeedController;

//use App\Http\Controllers\UserController;

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

 Route::middleware(['cors'])->group(function () {
  // API Route for deleting a game


 Route::post('get_category_list', [UserController::class, 'get_category_list']);
 Route::post('get_all_game_list', [UserController::class, 'get_all_game_list']);
 Route::post('get_game_by_id', [UserController::class, 'get_game_by_id']);
 Route::post('get_game_by_category_id', [UserController::class, 'get_game_by_category_id']);

 Route::post('get_game_by_search', [UserController::class, 'get_game_by_search']);

 //HOME GAME API
 Route::post('get_home_game_list', [UserController::class, 'get_home_game_list']);
 Route::post('get_recommended_game_list', [UserController::class, 'get_recommended_game_list']);
 Route::post('get_popular_game_list', [UserController::class, 'get_popular_game_list']);
 Route::post('get_trending_game_list', [UserController::class, 'get_trending_game_list']);
 Route::post('get_recent_game_list', [UserController::class, 'get_recent_game_list']);
 Route::post('get_latest_game_list', [UserController::class, 'get_latest_game_list']);


 //API FOR ADMIN PANEL
 Route::delete('delete-games/{id}',  [UserController::class, 'deleteGame']);
  



});

