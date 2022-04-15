<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\fm_notification;
use App\Models\Mdl_token;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends BaseController {

       function GetNotification(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = User::get();

                     return view("Admin/Notification/Notification")->with(compact('data'));
              } else {
                     return Redirect::to('/');
              }
       }

       function SendNotification(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $title = $request->title;
                     $msg = $request->msg;
                     $wine_category = $request->wine_category;

                     if ($wine_category['0'] == 0) {

                            $users = User::get();
                            for ($i = 0; $i <= count($users) - 1; $i++) {
                                   $notification_id = rand(11111, 99999);
                                   $tbl_ = new fm_notification;
                                   $tbl_->notification_to = $wine_category[$i]->id;
                                   $tbl_->notification_header = $title;
                                   $tbl_->notification_text = $msg;
                                   $tbl_->notification_uniqe_id = $notification_id;
                                   $tbl_->notification_by = 1;
                                   $tbl_->notification_type = 1;
                                   $tbl_->for_send = 2;
                                   $tbl_->save();

                                   $type = 11;

                                   $data1 = Mdl_token::select(array(DB::raw('group_concat(fm_token.device_token) as device_token')))->where('user_id', $wine_category[$i]->id)->first();

                                   $this->send_push_all_users($data1['device_token'], $msg, $type, $title, $notification_id);
                            }
                     } else {
                            for ($i = 0; $i <= count($wine_category) - 1; $i++) {
                                   $notification_id = rand(11111, 99999);
                                   $tbl_ = new fm_notification;
                                   $tbl_->notification_to = $wine_category[$i];
                                   $tbl_->notification_header = $title;
                                   $tbl_->notification_text = $msg;
                                   $tbl_->notification_uniqe_id = $notification_id;
                                   $tbl_->notification_by = 1;
                                   $tbl_->notification_type = 1;
                                   $tbl_->for_send = 2;
                                   $tbl_->save();

                                   $type = 11;

                                   $data1 = Mdl_token::select(array(DB::raw('group_concat(fm_token.device_token) as device_token')))->where('user_id', $wine_category[$i])->first();

                                   $this->send_push_all_users($data1['device_token'], $msg, $type, $title, $notification_id);
                            }
                     }

                     exit;


                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

}
