<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\User;
use App\Models\of_content;
use App\Models\of_activity;
use App\Models\of_top_trending;
use App\Models\of_user_contact_information;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class AdminController extends BaseController {

       // Admin Login
       function admin_login(Request $request) {

              $username = $request->email;
              $password = $request->password;

              $login = Admin::where(['email' => $username, 'password' => $password])->first();


              if ($login) {

                     //Store Session
                     $request->session()->put(['login' => 'adminlogin', 'adminid' => $login->admin_id, 'LoginData' => $login]);


//            return view("Admin/Dashboard");
                     return Redirect::to('dashboard');
              } else {
                     return back()->with('error', 'Username Or Password Wrong!');
              }
       }

       // Check Sessions
       function check_session(Request $request) {
              if (empty($request->session()->has('login'))) {
                     return Redirect::to('/Admin');
              }
       }

       // Get Dashboard
       function GetDashboard(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = User::select(DB::raw("count(id)as total_user"))->get()->toArray();
                     $category =   \App\Models\Mdl_category::select(DB::raw("count(category_id)as total_category"))->get()->toArray();

                     $games = \App\Models\Mdl_games::all();
                     return view("Admin/Dashboard")->with(compact('data', 'category', 'games'));
              } else {
                     return Redirect::to('/');
              }
       }

       // Logout
       function GetLogout(Request $request) {
              Session::forget('login');
              Session::forget('adminid');
              Session::forget('LoginData');
              if (!Session::has('login')) {
                     return Redirect::to('/');
              }
       }

}
