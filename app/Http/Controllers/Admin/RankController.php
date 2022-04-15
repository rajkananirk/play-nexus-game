<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\fm_rank_limit_user;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class RankController extends BaseController {

       function GetRank(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = fm_rank_limit_user::get();

                     return view("Admin/Rank/RankSystem")->with(compact('data'));
              } else {
                     return Redirect::to('/');
              }
       }

       function GetUsersProfile(Request $request, $id) {
              if (!empty($request->session()->has('login'))) {

                     print_r($id);
                     exit;
                     $data = User::get();

                     return view("Admin/User/User")->with(compact('data'));
              } else {
                     return Redirect::to('/');
              }
       }

       public function UpdateRank(Request $request) {
              if (Session::get('login')) {

                     $update_data = [];
                     $rank_id = $request->rank_id;
                     $rank_name = $request->rank_name;
                     $rank_point = $request->rank_point;
                     $limit_per_day = $request->limit_per_day;

                     if ($rank_name) {
                            $update_data['rank_name'] = $rank_name;
                     }
                     if ($rank_point) {
                            $update_data['rank_point'] = $rank_point;
                     }
                     if ($limit_per_day) {
                            $update_data['limit_per_day'] = $limit_per_day;
                     }

                     if (!empty($update_data)) {
                            fm_rank_limit_user::where('rank_id', $rank_id)->update($update_data);
                     }

                     return redirect()->back()->with('success', "Rank Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteRank(Request $request) {
              if (Session::get('login')) {

                     $rank_id = $request->rank_id;

                     fm_rank_limit_user::where('rank_id', $rank_id)->delete();
                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function AddRank(Request $request) {
              if (Session::get('login')) {

                     $rank_name = $request->rank_name;
                     $rank_point = $request->rank_point;
                     $limit_per_day = $request->limit_per_day;

                     $of_activity = new fm_rank_limit_user();
                     $of_activity->rank_name = $rank_name;
                     $of_activity->rank_point = $rank_point;
                     $of_activity->limit_per_day = $limit_per_day;
                     $of_activity->save();
                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteUsersProfile(Request $request) {
              if (Session::get('login')) {

                     $user_id = $request->user_id;

                     User::where('id', $user_id)->delete();


                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function BUUsersProfile(Request $request) {
              if (Session::get('login')) {

                     $user_id = $request->user_id;
                     $value_block = $request->value_block;

                     User::where('id', $user_id)->update(['is_blocked' => $value_block]);


                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

}
