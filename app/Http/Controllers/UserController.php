<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Mdl_token;
use App\Models\Mdl_feed_favrioute;
use App\Models\fm_contact_us;
use App\Models\Mdl_parking_image;
use App\Models\Mdl_feed;
use App\Models\fm_users_rank_arrived;
use App\Models\fm_all_feed;
use App\Models\Mdl_feed_image;
use Illuminate\Http\Request;
use Hash;
use Validator;
use Mail;
use DB;
use App\Http\Controllers\BaseController;

class UserController extends BaseController {

       public function login(Request $request) {
              $rule = [
                  'email' => 'required',
                  'password' => 'required',
                  'lattitude' => 'required',
                  'longitude' => 'required',
//                  'device_token' => 'required',
                  'device_type' => 'required',
                  'device_id' => 'required',
              ];

              $this->Required_params($request, $rule);

              if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                     $device_token = $request->input('device_token');
                     $device_type = $request->input('device_type');
                     $device_id = $request->input('device_id');

                     $data = array(
                         'device_token' => $device_token,
                         'device_type' => $device_type,
                         'device_id' => $device_id,
                         'user_id' => Auth::user()->id,
                     );

                     $this->add_device($data);
                     $auth_id = Auth::user()->id;

                     $user = User::where('id', $auth_id)->first();
                     $array_merge = array_merge($user->toArray(), $data);

                     $success = $array_merge;
                     $success['user_token'] = $user->createToken('Login')->accessToken;

                     return $this->sendResponse(1, 'Login Successfully', $success);
              } else {
                     return $this->sendResponse(0, 'Invalid Password or Email', null);
              }
       }

       public function sign_up(Request $request) {
              $rule = [
                  'user_name' => 'required',
                  'email' => 'required',
                  'password' => 'required',
                  'lattitude' => 'required',
                  'longitude' => 'required',
//                  'device_token' => 'required',
                  'device_type' => 'required',
                  'device_id' => 'required',
              ];

              $this->Required_params($request, $rule);

              $check_mail = User::where('email', $request->input('email'))->get()->first();

              if (!empty($check_mail)) {
                     return $this->sendResponse(0, 'Email Already Exists', null);
              }

              $check_mails = User::where('user_name', $request->input('user_name'))->get()->first();

              if (!empty($check_mails)) {
                     return $this->sendResponse(0, 'UserName Already Exists', null);
              }

              $user = new User;
              $user->user_name = $request->input('user_name');
              $user->email = $request->input('email');
              $user->password = bcrypt($request->input('password'));
              $user->lattitude = $request->input('lattitude');
              $user->longitude = $request->input('longitude');
              $user->created_at = date('Y-m-d H:i:s');
              $user->save();
              $user_id = $user->id;

              $device_token = $request->input('device_token');
              $device_type = $request->input('device_type');
              $device_id = $request->input('device_id');

              $data = array(
                  'device_token' => $device_token,
                  'device_type' => $device_type,
                  'device_id' => $device_id,
                  'user_id' => $user_id,
              );
              $this->add_device($data);

              $u_count = User::get()->count();

              User::where('id', $user_id)->update(['my_rank' => $u_count]);
              $user1 = User::where('id', $user_id)->first();
              $array_merge = array_merge($user1->toArray(), $data);
              $success = $array_merge;
              $success['user_token'] = $user->createToken('signup')->accessToken;

              return $this->sendResponse(1, 'Registration successfully', $success);
       }

       public function forgot_password(Request $request) {
              $rule = [
                  'email' => 'required|email',
              ];

              $this->Required_params($request, $rule);

              $checkemail = User::where('email', $request['email'])->first();

              if (empty($checkemail)) {
                     return $this->sendResponse(0, 'Email id Does not match over records', null);
              }

              $password = rand(100000, 999999);

              User::where('email', $checkemail['email'])->update(['temp_pass' => $password]);

              $data = array(
                  'title' => 'Reset Your Password.!',
                  'password' => $password,
                  'email' => $checkemail['email'],
                  'name' => $checkemail['username']
              );
              Mail::send('otppage', $data, function ($message) use ($data) {
                     $message->from('jwitter20215@gmail.com', "admin")->subject($data['title']);
                     $message->to($data['email']);
                     $message->cc('jwitter20215@gmail.com');
              });
              return $this->sendResponse(1, 'OTP sent your verified email', null);
       }

       public function reset_password(Request $request) {

              $rule = [
                  'email' => 'required|email',
                  'temp_pass' => 'required',
                  'new_pass' => 'required',
              ];

              $this->Required_params($request, $rule);

              $email = $request->input('email');
              $temp_pass = $request->input('temp_pass');
              $new_pass = bcrypt($request->input('new_pass'));

              $check_mail = User::where('email', $email)->get()->first();

              if (!empty($check_mail)) {

                     $update_pass = User::where(['email' => $email, 'temp_pass' => $temp_pass])->get()->first();

                     if ($update_pass) {

                            User::where('email', $email)->update(['temp_pass' => null, 'password' => $new_pass]);
                            return $this->sendResponse(1, 'Password changed successfully', null);
                     } else {
                            return $this->sendResponse(2, 'Wrong OTP', null);
                     }
              } else {
                     return $this->sendResponse(0, 'Email Not Exists', null);
              }
       }

       public function change_password(Request $request) {

              $rule = [
                  'current_password' => 'required',
                  'new_password' => 'required',
              ];

              $this->Required_params($request, $rule);


              $current_password = Auth::User()->password;
              $new_password = $request->input('new_password');
              $user_id = Auth::id();

              if (Hash::check($request['current_password'], $current_password)) {

                     if (Hash::check($new_password, $current_password)) {
                            return $this->sendResponse(0, "New Password can't be same as Old Password", null);
                     }
                     User::where('id', $user_id)->update(['password' => bcrypt($new_password)]);
                     $user_data = User::where('password', $user_id)->get()->first();
                     return $this->sendResponse(1, 'Password changed Successfully', $user_data);
              } else {
                     return $this->sendResponse(0, 'Please enter correct current password', null);
              }
       }

       public function logout(Request $request) {

              $rule = [
                  'device_id' => 'required',
              ];

              $this->Required_params($request, $rule);

              $device_id = $request->input('device_id');

              $accessToken = Auth::user()->token();
              DB::table('oauth_refresh_tokens')
                      ->where('access_token_id', $accessToken->id)
                      ->update([
                          'revoked' => true
              ]);

              $userId = Auth::id();

              Mdl_token::where(['device_id' => $device_id, 'user_id' => $userId])->delete();

              $accessToken->revoke();
              return $this->sendResponse(1, 'Logout Successfully', null);
       }

       public function login_by_thirdparty(Request $request) {
              $rule = [
                  'email' => 'required',
                  'thirdparty_id' => 'required',
                  'device_token' => 'required',
                  'device_id' => 'required',
                  'device_type' => 'required',
                  'login_type' => 'required',
                  'lattitude' => 'required',
                  'longitude' => 'required',
              ];

              $validate = Validator::make(request()->all(), $rule);

              if ($validate->fails()) {
                     return response()->json(['status' => '0', 'message' => 'validation fail', 'data' => ['errors' => $validate->errors()]], 401);
              }

              $check_fb_id = User::where('thirdparty_id', $request->get('thirdparty_id'))->first();

              if (empty($check_fb_id)) {

                     $email = $request->input('email');
                     $check_email = User::where('email', $request->input('email'))->get()->first();

                     if ($check_email) {
                            return response()->json(['status' => '0', 'message' => 'Email Already Exist',], 401);
                     }

                     $user = new User();
                     $user->thirdparty_id = $request->input('thirdparty_id');
                     $user->password = $request->input('password');
                     $user->email = $email;
                     $user->lattitude = $request->input('lattitude');
                     $user->user_name = $request->input('user_name');
                     $user->login_type = $request->input('login_type');
                     $user->longitude = $request->input('longitude');
                     $user->save();

                     $user_id = $user->id;

                     $device_token = $request->input('device_token');
                     $device_type = $request->input('device_type');
                     $device_id = $request->input('device_id');

                     $data = array(
                         'device_token' => $device_token,
                         'device_type' => $device_type,
                         'device_id' => $device_id,
                         'user_id' => $user_id,
                     );

                     $this->add_device($data);

//                          $user1 = User::select(array('*',
//                                 DB::raw("($device_token)as device_token"),
//                                 DB::raw("($device_type)as device_type"),
//                                 DB::raw("($device_id)as device_id"),
//                             ))->where('id', $user_id)->first();
                     $u_count = User::get()->count();

                     User::where('id', $user_id)->update(['my_rank' => $u_count]);
                     $user1 = User::where('id', $user_id)->first();

                     $array_merge = array_merge($user1->toArray(), $data);

                     $success = $array_merge;
                     $success['user_token'] = $user->createToken('signup')->accessToken;

                     return response()->json(['status' => 1, 'message' => 'Registration successfully..', 'data' => $success], 200);
              } else {

                     $success = $check_fb_id->createToken('signup')->accessToken;

                     $user = User:: where(['thirdparty_id' => $request->get('thirdparty_id')])->first()->toArray();

                     if ($user['is_blocked'] == 0) {


                            User::where('thirdparty_id', $request->get('thirdparty_id'));



                            $device_token = $request->input('device_token');
                            $device_type = $request->input('device_type');
                            $device_id = $request->input('device_id');


                            $tbl_token = new Mdl_token();
                            $tbl_token->user_id = $user['id'];
                            $tbl_token->device_token = $device_token;
                            $tbl_token->device_type = $device_type;
                            $tbl_token->device_id = $device_id;
                            $tbl_token->save();


                            $tbl_token1 = array(
                                'device_token' => $request->input('device_token'),
                                'device_type' => $request->input('device_type'),
                                'device_id' => $device_id,
                                'user_id' => $user['id'],
                            );

                            $res = array_merge($user, $tbl_token1);


                            $success1 = $res;
                            $success1['user_token'] = $success;

//                $datas = array('user' => $res, 'token' => $success);

                            return response()->json(['status' => 1, 'message' => 'Login successfully..', 'data' => $success1], 200);
                     } else {

                            return response()->json(['status' => 13, 'message' => "User Is Blocked By Admin"], 200);
                     }
              }
       }

       public function edit_profile(Request $request) {
              $user_id = Auth::id();

              if ($request->input('user_name')) {
                     $update_data['user_name'] = $request->input('user_name');
              }

              if ($request->input('address')) {
                     $update_data['address'] = $request->input('address');
              }
              if ($request->input('prefecture')) {
                     $update_data['prefecture'] = $request->input('prefecture');
              }


              if ($request->hasFile('profile_pic')) {

                     $imageName = time() . '.' . request()->profile_pic->getClientOriginalExtension();

                     request()->profile_pic->move(public_path('uploads'), $imageName);
                     $update_data['profile_pic'] = 'public/uploads/' . $imageName;
              }

              if ($request->input('email')) {

                     $check_mail2 = User::where('email', $request->input('email'))->get()->first();

                     if (empty($check_mail2)) {
                            $update_data['email'] = $request->input('email');
                     } else {

                            $login_email3 = User::where('id', $user_id)->pluck('email')->first();

                            if ($request->input('email') != $login_email3) {
                                   return $this->sendResponse(0, 'Email Already Exists', null);
                            }
                     }
              }
              if ($request->input('email')) {

                     $check_mail2 = User::where('email', $request->input('email'))->get()->first();

                     if (empty($check_mail2)) {
                            $update_data['email'] = $request->input('email');
                     } else {

                            $login_email3 = User::where('id', $user_id)->pluck('email')->first();

                            if ($request->input('email') != $login_email3) {
                                   return $this->sendResponse(0, 'Email Already Exists', null);
                            }
                     }
              }
              if ($request->input('phone_number')) {

                     $check_phone_number = User::where('phone_number', $request->input('phone_number'))->get()->first();

                     if (empty($check_phone_number)) {
                            $update_data['phone_number'] = $request->input('phone_number');
                     } else {

                            $login_phone_number = User::where('id', $user_id)->pluck('phone_number')->first();

                            if ($request->input('phone_number') != $login_phone_number) {
                                   return $this->sendResponse(0, 'Phone number Already Exists', null);
                            }
                     }
              }

              if (!empty($update_data)) {
                     User::where('id', $user_id)->update($update_data);
              }


              $user_data['my_details'] = User::select(array('*', 'my_rank as sum_of_rank',
                          DB::raw("(select COUNT(t5.id) from fm_feed_image t5 where t5.user_id = $user_id)as sum_of_my_images"),
                          DB::raw("(select COUNT(t5.feed_id) from fm_all_feed t5 where t5.added_by = $user_id)as sum_of_my_feed"),
                          DB::raw("(select COUNT(t5.id) from fm_feed_favrioute t5 where t5.user_id = $user_id)as sum_of_likke_feed"),
                          DB::raw("(select SUM(t5.rank_point) from fm_users_rank_arrived t5 where t5.user_id = $user_id)as sum_of_point"),
                      ))->where('id', $user_id)->get()->first();
              $user_data['my_images'] = Mdl_feed_image::where('user_id', $user_id)->get();
              $user_data['my_feeds'] = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $user_id)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                      ))
                      ->with('image')
                      ->where('added_by', $user_id)
                      ->orderBy('feed_id', 'desc')
//                      ->having('distance_new', '<=', 50)
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->get();

              return $this->sendResponse(1, 'Profile Edited Successfully', $user_data);
       }

       public function my_profile(Request $request) {
              $userId = Auth::id();

              $user = User::select('id', 'user_name', 'email', 'address', 'prefecture')->where('id', $userId)->get();

              return $this->sendResponse(1, 'My Profile List successfully', $user);
       }

       public function delete_feed(Request $request) {

              $userId = Auth::id();

              if ($request->hasFile('profile_pic')) {

                     $imageName = time() . '.' . request()->profile_pic->getClientOriginalExtension();

                     request()->profile_pic->move(public_path('uploads'), $imageName);
                     $update_data['profile_pic'] = 'public/uploads/' . $imageName;
              }
              if (!empty($update_data)) {
                     User::where('id', $userId)->update($update_data);
              }

              $user = Mdl_feed::where('feed_id', $update_data)->delete();
              return $this->sendResponse(1, 'profile data successfully', $user);
       }

       public function get_rank_update(Request $request) {

              $data = fm_users_rank_arrived::select(array('*',
                          DB::raw("(SELECT SUM(t2.rank_point) from fm_users_rank_arrived t2 WHERE t2.user_id = fm_users_rank_arrived.user_id)as t_point")))
                      ->groupBy('user_id')
                      ->orderBy('t_point', 'desc')
                      ->get();
//              for ($x = 0; $x <= count($data) - 1; $x++) {
//                     echo "The number is: $x <br>";
//
////                     if ($data[$x]->t_point == $data[$x]->t_point) {
////                            $i2 = $x;
////                            User::where('id', $data[$x]->user_id)->update(['my_rank' => $i2]);
////                     } else {
////                     }
//                     $i2 = $x + 1;
//                     User::where('id', $data[$x]->user_id)->update(['my_rank' => $i2]);
//              }

              $response = json_encode($data);
              echo $response;
       }

       public function send_to_admin_contact(Request $request) {
              $rule = [
                  'name' => 'required',
                  'email_id' => 'required',
                  'phone_number' => 'required',
                  'comments' => 'required',
              ];

              $this->Required_params($request, $rule);

              $user = new fm_contact_us;
              $user->email_id = $request->input('email_id');
              $user->name = $request->input('name');
              $user->phone_number = $request->input('phone_number');
              $user->comments = $request->input('comments');
              $user->created_at = date('Y-m-d H:i:s');
              $user->save();

              return $this->sendResponse(1, 'Conatct data add successfully', $user);
       }

       public function get_user_details(Request $request) {
              $rule = [
                  'user_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $user_id = $request->input('user_id');

              $user_data['my_details'] = User::select(array('*', 'my_rank as sum_of_rank',
                          DB::raw("(select COUNT(t5.id) from fm_feed_image t5 where t5.user_id = $user_id)as sum_of_my_images"),
                          DB::raw("(select COUNT(t5.feed_id) from fm_all_feed t5 where t5.added_by = $user_id)as sum_of_my_feed"),
                          DB::raw("(select COUNT(t5.id) from fm_feed_favrioute t5 where t5.user_id = $user_id)as sum_of_likke_feed"),
                          DB::raw("(select SUM(t5.rank_point) from fm_users_rank_arrived t5 where t5.user_id = $user_id)as sum_of_point"),
                      ))->where('id', $user_id)->get()->first();
              $user_data['my_images'] = Mdl_feed_image::where('user_id', $user_id)->get();
              $user_data['my_feeds'] = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $user_id)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                      ))
                      ->with('image')
                      ->where('added_by', $user_id)
                      ->orderBy('feed_id', 'desc')
//                      ->having('distance_new', '<=', 50)
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->get();

              return $this->sendResponse(1, 'Profile Edited Successfully', $user_data);
       }

       public function send_notification(Request $request) {
              $rule = [
                  'user_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = $request->input('user_id');
              $of_users = User::select(array('*', DB::raw("(SELECT GROUP_CONCAT(device_token)  FROM fm_token WHERE user_id = $userId)as tokens")))->where('id', $userId)
                      ->first();

              $type = 1;
              $feed_id = 1;
              $notification_id = rand(000000, 11111);

              $msg = "Push test successfully!";

              $firebaseToken = $of_users->tokens;
              $this->send_push($firebaseToken, $msg, $type, $notification_id, $feed_id, $userId);

              return $this->sendResponse(1, 'Notification send successfully', $of_users);
       }

}
