<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Mdl_games;
use App\Models\Mdl_category;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class GamesController extends BaseController {

       function GetGames(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = Mdl_games::select('*',DB::raw('(Select t1.category_name from categories t1 where t1.category_id = games.category_id) as category_name'))->get();
                  
                 
                     $category = Mdl_category::get();

                            return view("Admin/Games/Games")->with(compact('data','category'));
              } else {
                     return Redirect::to('/');
              }
       }
       function EditGames(Request $request, $id) {
              if (!empty($request->session()->has('login'))) {

                     $data = Mdl_games::select('*',DB::raw('(Select t1.category_name from categories t1 where t1.category_id = games.category_id) as category_name'))->where('game_id', $id)->first();
                 
                 
                     $category = Mdl_category::get();

                            return view("Admin/Games/EditGames")->with(compact('data','category'));
              } else {
                     return Redirect::to('/');
              }
       }

       function GetGamesProfile(Request $request, $id) {
              if (!empty($request->session()->has('login'))) {

                     $data = Mdl_games::where('game_id', $id)->first();

                     return view("Admin/Games/Games")->with(compact('data'));
              } else {
                     return Redirect::to('/');
              }
       }

       public function UpdateGames(Request $request) {
              if (Session::get('login')) {

                     $update_data = [];
                     $game_id = $request->game_id;
                     $name = $request->name;
                     $category_id = $request->category_id;
                     $imgURL = $request->imgURL;
                     $redirectURL = $request->redirectURL;
                     $iframe = $request->iframe;
                     $badge = $request->badge;
                     $description = $request->description;
                     $is_new = $request->is_new ? 1 : 0;
                     $is_top = $request->is_top ? 1 : 0;
                     $is_trending = $request->is_trending ? 1 : 0;
                     $is_popular = $request->is_popular ? 1 : 0;
                     $is_featured = $request->is_featured ? 1 : 0;
                     $is_recommended = $request->is_recommended ? 1 : 0;
                     $is_latest = $request->is_latest ? 1 : 0; 
                     $is_all = $request->is_all ? 1 : 0;



                   

                     if ($name) {
                            $update_data['name'] = $name;
                     }

                     if ($category_id) {
                            $update_data['category_id'] = $category_id;
                     }

                     if ($imgURL) {
                            $update_data['imgURL'] = $imgURL;
                     }

                     if ($badge) {
                            $update_data['badge'] = $badge;
                     }

                     if ($redirectURL) {
                            $update_data['redirectURL'] = $redirectURL;
                     }

                     if ($iframe) {
                            $update_data['iframe'] = $iframe;
                     }     

                     if ($description) {
                            $update_data['description'] = $description;
                     }

                            $update_data['is_new'] = $is_new;

                            $update_data['is_top'] = $is_top;

                            $update_data['is_trending'] = $is_trending;

                            $update_data['is_popular'] = $is_popular;

                            $update_data['is_featured'] = $is_featured;

                            $update_data['is_recommended'] = $is_recommended;

                            $update_data['is_latest'] = $is_latest;
                            $update_data['is_all'] = $is_all;

                     

                     if (!empty($update_data)) {
                            Mdl_games::where('game_id', $game_id)->update($update_data);
                     }



                     return Redirect::to('/games');
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteGames(Request $request) {
              if (Session::get('login')) {

                     $trending_id = $request->game_id;

                     Mdl_games::where('game_id', $game_id)->delete();
                     return redirect()->back()->with('success', "Games Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function AddGames(Request $request) {
              if (Session::get('login')) {
                     $name = $request->name;
                     $imgURL = $request->imgURL;
                     $badge = $request->badge;
                     $redirectURL = $request->redirectURL;
                     $iframe = $request->iframe;
                     $category_id = $request->category_id;
                     $description = $request->description;
                     $is_new = $request->is_new ? 1 : 0;
                     $is_top = $request->is_top ? 1 : 0;
                     $is_trending = $request->is_trending ? 1 : 0;
                     $is_popular = $request->is_popular ? 1 : 0;
                     $is_featured = $request->is_featured ? 1 : 0;
                     $is_recommended = $request->is_recommended ? 1 : 0;
                     $is_latest = $request->is_latest ? 1 : 0;
                     $is_all = $request->is_all ? 1 : 0;

                
                    
                     $of_activity = new Mdl_games();
                     $of_activity->name = $name;
                     $of_activity->imgURL = $imgURL;
                     $of_activity->redirectURL = $redirectURL;
                     $of_activity->iframe = $iframe;
                     $of_activity->category_id = $category_id;
                     $of_activity->description = $description;
                     $of_activity->badge = $badge;
                     $of_activity->is_new = $is_new;
                     $of_activity->is_top = $is_top;
                     $of_activity->is_trending = $is_trending;
                     $of_activity->is_popular = $is_popular;
                     $of_activity->is_featured = $is_featured;
                     $of_activity->is_recommended = $is_recommended;
                     $of_activity->is_latest = $is_latest;
                     $of_activity->is_all = $is_all;
                     $of_activity->save();
                     return redirect()->back()->with('success', "Games Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteGamesProfile(Request $request) {
              if (Session::get('login')) {

                     $game_id = $request->game_id;

                     Mdl_games::where('game_id', $game_id)->delete();


                     return redirect()->back()->with('success', "Games Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function BUGamesProfile(Request $request) {
              if (Session::get('login')) {

                     $game_id = $request->game_id;
                     $value_block = $request->value_block;

                     Mdl_games::where('game_id', $game_id)->update(['is_blocked' => $value_block]);


                     return redirect()->back()->with('success', "Games Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

}
