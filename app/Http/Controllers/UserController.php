<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Mdl_category;
use App\Models\Mdl_games;
use Illuminate\Http\Request;
use Hash;
use Validator;
use Mail;
use DB;
use App\Http\Controllers\BaseController;

class UserController extends BaseController {

    
       public function get_category_list(Request $request) {
   header("Access-Control-Allow-Origin: *");


              $user = Mdl_category::select('*', DB::raw('(SELECT COUNT(*) FROM games WHERE category_id = categories.category_id) as total_games'))->get();

              return $this->sendResponse(1, 'Category List successfully', $user);
       }

       public function get_all_game_list(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $user = Mdl_games::inRandomOrder()->orderBy('game_id', 'desc')->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }

       public function get_game_by_id(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $validator = Validator::make($request->all(), [
                     'game_id' => 'required',
              ]);

              if ($validator->fails()) {
                     return $this->sendResponse(0, 'Validation Error', $validator->errors());
              }

              $user = Mdl_games::where('game_id', $request->game_id)->first();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }

       public function get_game_by_category_id(Request $request) {

   header("Access-Control-Allow-Origin: *");


              $user = Mdl_games::where('category_id', $request->category_id)->inRandomOrder()->orderBy('game_id', 'desc')->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }

       public function get_game_by_search(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $validator = Validator::make($request->all(), [
                     'search' => 'required',
              ]);

              if ($validator->fails()) {
                     return $this->sendResponse(0, 'Validation Error', $validator->errors());
              }
              $user = Mdl_games::where('name', 'like', '%' . $request->search . '%')->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }      

       public function get_home_game_list(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $user = Mdl_games::inRandomOrder()->orderBy('game_id', 'desc')->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }

       public function get_recommended_game_list(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $user = Mdl_games::where('is_recommended', 1)->inRandomOrder()->orderBy('game_id', 'desc')->limit(10)->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }      

       public function get_popular_game_list(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $user = Mdl_games::where('is_popular', 1)->inRandomOrder()->orderBy('game_id', 'desc')->limit(10)->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }      

       public function get_trending_game_list(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $user = Mdl_games::where('is_trending', 1)->inRandomOrder()->orderBy('game_id', 'desc')->limit(10)->get();

              return $this->sendResponse(1, 'Game List successfully', $user); 
       }      

       public function get_new_game_list(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $user = Mdl_games::inRandomOrder()->orderBy('game_id', 'desc')->limit(10)->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }      

       public function get_recent_game_list(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $user = Mdl_games::orderBy('game_id', 'desc')->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }      

       public function get_latest_game_list(Request $request) {
   header("Access-Control-Allow-Origin: *");

              $user = Mdl_games::orderBy('game_id', 'desc')->limit(10)->get();

              return $this->sendResponse(1, 'Game List successfully', $user);
       }    
       
       public function deleteGame(Request $request, $id) {
          header("Access-Control-Allow-Origin: *");
       $game = Mdl_games::where('game_id', $id)->first();
              if ($game) {
              $game = Mdl_games::where('game_id', $id)->delete();

                  return $this->sendResponse(1, 'Game deleted successfully',$game );
              }
       }      

}
