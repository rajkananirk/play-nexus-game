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

Route::group(['middleware' => 'auth:api'], function () {
       Route::post('send_to_admin_contact', [UserController::class, 'send_to_admin_contact']);
       Route::post('logout', [UserController::class, 'logout']);
       Route::post('edit_profile', [UserController::class, 'edit_profile']);
       Route::post('change_password', [UserController::class, 'change_password']);
       Route::post('get_rank_update', [UserController::class, 'get_rank_update']);
       Route::post('GetLocationData', [FeedController::class, 'GetLocationData']);
       Route::post('FeedDetailsCategory', [FeedController::class, 'FeedDetailsCategory']);
       Route::post('Addimages', [FeedController::class, 'Addimages']);
       Route::post('EventList', [FeedController::class, 'EventList']);

       Route::post('AddPostYoutubeEvent', [FeedController::class, 'AddPostYoutubeEvent']);
       Route::post('GetCategoryScreenData', [FeedController::class, 'GetCategoryScreenData']);
       Route::post('EditPostYoutubeEvent', [FeedController::class, 'EditPostYoutubeEvent']);
       Route::post('LikeToComment', [FeedController::class, 'LikeToComment']);
       Route::post('get_comment', [FeedController::class, 'get_comment']);
       Route::post('JoinEvent', [FeedController::class, 'JoinEvent']);
       Route::post('GetNotification', [FeedController::class, 'GetNotification']);

       Route::post('delete_image', [FeedController::class, 'delete_image']);
       Route::post('GetCategory', [FeedController::class, 'GetCategory']);
       Route::post('GetHomeScreenData', [FeedController::class, 'GetHomeScreenData']);
       Route::post('AddToFavourite', [FeedController::class, 'AddToFavourite']);
       Route::post('CategoryIdToFeed', [FeedController::class, 'CategoryIdToFeed']);
       Route::post('AddNewParking', [FeedController::class, 'AddNewParking']);
       Route::post('FeedDetails', [FeedController::class, 'FeedDetails']);
       Route::post('AddFeed', [FeedController::class, 'AddFeed']);
       Route::post('AddEvent', [FeedController::class, 'AddEvent']);
       Route::post('GetPrefecture', [FeedController::class, 'GetPrefecture']);
       Route::post('EventDetails', [FeedController::class, 'EventDetails']);
       Route::post('AddComment', [FeedController::class, 'AddComment']);
       Route::post('YoutubeCategoryData', [FeedController::class, 'YoutubeCategoryData']);
       Route::post('GetEventComment', [FeedController::class, 'GetEventComment']);
       Route::post('AddReplyComment', [FeedController::class, 'AddReplyComment']);
       Route::post('CategoryByEvent', [FeedController::class, 'CategoryByEvent']);
       Route::post('DeleteFeed', [FeedController::class, 'DeleteFeed']);
       Route::post('EventDelete', [FeedController::class, 'EventDelete']);

       Route::post('AddToFavouriteImage', [FeedController::class, 'AddToFavouriteImage']);
       Route::post('filter_by_latlong', [FeedController::class, 'filter_by_latlong']);
       Route::post('get_profile', [FeedController::class, 'get_profile']);
       Route::post('date', [FeedController::class, 'date']);
       Route::post('GetRankSystem', [FeedController::class, 'GetRankSystem']);
       Route::post('add_rank_data', [FeedController::class, 'add_rank_data']);
       Route::post('get_my_rank_system', [FeedController::class, 'get_my_rank_system']);
       Route::post('GetPlay NexusUser', [FeedController::class, 'GetPlay NexusUser']);
       Route::post('update_rank_status', [FeedController::class, 'update_rank_status']);
       Route::post('get_unseen_count', [FeedController::class, 'get_unseen_count']);
       Route::post('OwnerEventList', [FeedController::class, 'OwnerEventList']);
       Route::post('block_post', [FeedController::class, 'block_post']);
       Route::post('user_block', [FeedController::class, 'user_block']);
       Route::post('get_fav_feeds', [FeedController::class, 'get_fav_feeds']);
       Route::post('report_action', [FeedController::class, 'report_action']);
       Route::post('get_user_details', [UserController::class, 'get_user_details']);
       Route::post('get_my_rank_system_by_user', [FeedController::class, 'get_my_rank_system_by_user']);
});

Route::get('_UPDATE_MY_RANKS', [FeedController::class, '_UPDATE_MY_RANKS']);
//UsersController
Route::post('login', [UserController::class, 'login']);
Route::post('sign_up', [UserController::class, 'sign_up']);
Route::post('forgot_password', [UserController::class, 'forgot_password']);
Route::post('reset_password', [UserController::class, 'reset_password']);
Route::post('login_by_thirdparty', [UserController::class, 'login_by_thirdparty']);
Route::post('send_notification', [UserController::class, 'send_notification']);

//FeedController
Route::post('filter', [FeedController::class, 'filter']);

