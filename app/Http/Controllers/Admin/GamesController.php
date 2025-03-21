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
                     $description = $request->description;

                     // echo "<pre>";
                     // print_r($request->all());
                     // echo "</pre>";
                     // exit;

                     if ($name) {
                            $update_data['name'] = $name;
                     }

                     if ($category_id) {
                            $update_data['category_id'] = $category_id;
                     }

                     if ($imgURL) {
                            $update_data['imgURL'] = $imgURL;
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
                     $redirectURL = $request->redirectURL;
                     $iframe = $request->iframe;
                     $category_id = $request->category_id;
                     $description = $request->description;
                     $of_activity = new Mdl_games();
                     $of_activity->name = $name;
                     $of_activity->imgURL = $imgURL;
                     $of_activity->redirectURL = $redirectURL;
                     $of_activity->iframe = $iframe;
                     $of_activity->category_id = $category_id;
                     $of_activity->description = $description;
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
