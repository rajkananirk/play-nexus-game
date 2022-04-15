<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\fm_report;
use App\Models\fm_contact_us;
use App\Models\User;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class UserController extends BaseController {

       function GetUsers(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = User::get();

                     return view("Admin/User/User")->with(compact('data'));
              } else {
                     return Redirect::to('/');
              }
       }

       function ReportUserList(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = fm_report::select(array('*',
                                 DB::raw("(SELECT user_name FROM users WHERE id = fm_report.report_by)as by_username"),
                                 DB::raw("(SELECT email FROM users WHERE id = fm_report.report_by)as by_email"),
                                 DB::raw("(SELECT user_name FROM users WHERE id = fm_report.report_to)as to_username"),
                                 DB::raw("(SELECT email FROM users WHERE id = fm_report.report_to)as to_email"),
                             ))->where('report_on', 2)->get();

//                     echo '<pre>';
//                     print_r($data->toArray());
//                     exit;

                     return view("Admin/User/ReportUser")->with(compact('data'));
              } else {
                     return Redirect::to('/');
              }
       }

       function ReportCommentList(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = fm_report::select(array('*',
                                 DB::raw("(SELECT t2.user_name FROM fm_comment t1 JOIN users t2 ON t1.user_id = t2.id WHERE t1.comment_id = fm_report.comment_id)as to_username"),
                                 DB::raw("(SELECT t2.email FROM fm_comment t1 JOIN users t2 ON t1.user_id = t2.id WHERE t1.comment_id = fm_report.comment_id)as to_email"),
                                 DB::raw("(SELECT t1.user_id FROM fm_comment t1 JOIN users t2 ON t1.user_id = t2.id WHERE t1.comment_id = fm_report.comment_id)as to_user_id"),
                                 DB::raw("(SELECT t1.comment_text FROM fm_comment t1 WHERE t1.comment_id = fm_report.comment_id)as comment_text"),
                                 DB::raw("(SELECT email FROM users WHERE id = fm_report.report_by)as by_email"),
                             ))->where('report_on', 1)->get();

//                     echo '<pre>';
//                     print_r($data->toArray());
//                     exit;

                     return view("Admin/User/ReportComment")->with(compact('data'));
              } else {
                     return Redirect::to('/');
              }
       }

       function ContactList(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = fm_contact_us::get();

//                     echo '<pre>';
//                     print_r($data->toArray());
//                     exit;

                     return view("Admin/User/ContactUs")->with(compact('data'));
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

       public function UpdateUsersProfile(Request $request) {
              if (Session::get('login')) {

                     $update_data = [];
                     $user_id = $request->user_id;
                     $email = $request->email;
                     $phone_number = $request->phone_number;
                     $username = $request->username;
//                     print_r($username);
//                     exit;
                     $country = $request->country;
                     $state = $request->state;
                     $prefecture = $request->prefecture;
                     $address = $request->address;

                     if ($address) {
                            $update_data['address'] = $address;
                     }

                     if ($prefecture) {
                            $update_data['prefecture'] = $prefecture;
                     }


                     if ($request->hasFile('profile_pic')) {

                            $profile_pic = time() . '.' . request()->profile_pic->getClientOriginalExtension();
                            request()->profile_pic->move(public_path('uploads'), $profile_pic);

                            $img_link = 'public/uploads/' . $profile_pic;
                            $update_data['profile_pic'] = $img_link;
                     }


                     if ($email) {
                            $q = "SELECT * FROM users where email = '$email' limit 1";
                            $check_mail = DB::select($q);


                            if (empty($check_mail)) {
                                   $update_data['email'] = $email;
                            } else {

                                   $login_email = "SELECT email FROM users where id = $user_id limit 1";
                                   $login_email_q = DB::select($login_email);

                                   if ($email != $login_email_q[0]->email) {
                                          return back()->with('block', 'Email Already Exits!');
                                   }
                            }
                     }

                     if ($username) {
                            $q = "SELECT * FROM users where user_name = '$username' limit 1";
                            $check_mail = DB::select($q);


                            if (empty($check_mail)) {
                                   $update_data['user_name'] = $username;
                            } else {

                                   $login_email = "SELECT user_name FROM users where id = $user_id limit 1";
                                   $login_email_q = DB::select($login_email);

                                   if ($username != $login_email_q[0]->user_name) {
                                          return back()->with('block', 'Username Already Exits!');
                                   }
                            }
                     }

                     if ($phone_number) {
                            $check_phone = "SELECT * FROM users where phone_number = $phone_number limit 1";
                            $check_phone_new = DB::select($check_phone);

                            if (empty($check_phone_new)) {
                                   $update_data['phone_number'] = $phone_number;
                            } else {

                                   $login_phone = "SELECT phone_number FROM users where id = $user_id limit 1";
                                   $login_phone_q = DB::select($login_phone);

                                   if ($phone_number != $login_phone_q[0]->phone_number) {
                                          return back()->with('block', 'Phone Number Already Exits!');
                                   }
                            }
                     }



                     if (!empty($update_data)) {
                            User::where('id', $user_id)->update($update_data);
                     }

                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteUsersProfile(Request $request) {
              if (Session::get('login')) {

                     $user_id = $request->user_id;

                     User::where('id', $user_id)->delete();
                     \App\Models\fm_all_feed::where('added_by', $user_id)->delete();
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

       public function ReportAction(Request $request) {
              if (Session::get('login')) {

                     $report_id = $request->report_id;
                     $report_status = $request->report_status;

                     $data = fm_report::where('report_id', $report_id)->first();

                     if ($report_status == 2) {
                            User::where('id', $data->report_to)->update(['is_blocked' => 1]);
                            fm_report::where('report_id', $report_id)->update(['report_status' => 2]);
                     } else {
                            User::where('id', $data->report_to)->update(['is_blocked' => 0]);
                            fm_report::where('report_id', $report_id)->update(['report_status' => 3]);
                     }
                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function ReportActionComment(Request $request) {
              if (Session::get('login')) {

                     $report_id = $request->report_id;
                     $report_status = $request->report_status;

                     $data = fm_report::select(array('*',
                                 DB::raw("(SELECT t1.user_id FROM fm_comment t1 JOIN users t2 ON t1.user_id = t2.id WHERE t1.comment_id = fm_report.comment_id)as to_user_id"),
                             ))->where('report_id', $report_id)->first();

                     if ($report_status == 2) {
                            User::where('id', $data->to_user_id)->update(['is_blocked' => 1]);
                            fm_report::where('report_id', $report_id)->update(['report_status' => 2]);
                     } else {
                            User::where('id', $data->to_user_id)->update(['is_blocked' => 0]);
                            fm_report::where('report_id', $report_id)->update(['report_status' => 3]);
                     }
                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

}
