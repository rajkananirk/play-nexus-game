<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FeedController;
use App\Http\Controllers\Admin\RankController;
use App\Http\Controllers\Admin\NotificationController;

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
Route::any('feed', [FeedController::class, 'GetFeed']);
Route::any('youtube', [FeedController::class, 'GetYoutube']);
Route::any('event', [FeedController::class, 'GetEvent']);
Route::post('update-feed', [FeedController::class, 'UpdateFeed']);
Route::post('delete-feed', [FeedController::class, 'DeleteFeed']);

Route::any('top-event', [FeedController::class, 'GetTopEvent']);
Route::any('add-feed', [FeedController::class, 'AddTopEvent']);

//rank-system
Route::get('rank-system', [RankController::class, 'GetRank']);
Route::post('update-rank', [RankController::class, 'UpdateRank']);
Route::post('add-rank', [RankController::class, 'AddRank']);
Route::post('delete-rank', [RankController::class, 'DeleteRank']);


Route::get('conatct-me', [UserController::class, 'ContactList']);

Route::get('report-user', [UserController::class, 'ReportUserList']);
Route::post('report-action', [UserController::class, 'ReportAction']);

Route::get('report-comment', [UserController::class, 'ReportCommentList']);
Route::post('report-action-comment', [UserController::class, 'ReportActionComment']);

//NOTIFICATION
Route::get('notification', [NotificationController::class, 'GetNotification']);
Route::post('send_notification', [NotificationController::class, 'SendNotification']);
