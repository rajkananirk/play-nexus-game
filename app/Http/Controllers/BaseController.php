<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Model\tbl_token;
use App\Model\admin;
use App\User;
use Validator;

class BaseController extends Controller {

       public function sendResponse($status, $message, $result) {

              if (empty($result)) {

                     $response = array('status' => $status, 'message' => $message);
                     echo json_encode($response);
              } else {
                     $response = array('status' => $status, 'message' => $message, 'data' => $result);
                     echo json_encode($response);
              }

              exit();
       }

       function Required_params(Request $request, $data) {

              $validate = Validator::make($request->all(), $data);

              if ($validate->fails()) {
                     echo json_encode(['status' => 0, 'message' => 'validation fail', 'data' => ['error' => $validate->errors()]]);
                     exit;
              }
       }

       // DataBase Query Function

       public function select($table, $select, $where) {

              $users = DB::table($table)
                      ->select($select)
                      ->where($where)
                      ->get();
              return $users;
       }

       function update($table, $where, $update) {
              $data = DB::table($table)
                      ->where($where)
                      ->update($update);

              return $data;
       }

       function insert($table, $values) {
              $last_id = DB::table($table)->insertGetId($values);
              return $last_id;
       }

       function delete($table, $where) {
              $last_id = DB::table($table)->where($where)->delete();
              return $last_id;
       }

       // Send Push Notification

       function get_user_token($user_id) {
              $results = \App\Models\Mdl_token::where('user_id', $user_id)->get();

              print($results);
              exit;
              return $results;
       }

       function Masked($user_id) {
              $results = tbl_token::where('user_id', $user_id)->get();

              return $results;
       }

       function send_push($firebaseToken, $msg, $type, $g_count, $notification_id, $feed_id, $user_id) {
              $firebaseToken = explode(',', $firebaseToken);


              $headers = array("Content-Type:" . "application/json",
                  "Authorization:" .
                  "key=" . "AAAA-5-UPo4:APA91bFOMwhiVzvXX30rrt6dm9E-qhyLnAHeQNDkpe70tVfpvVrK8Fub33CckKuVbge7hwknFgxaUpq11eYb-F5ezQ1amSXqFpNwY7GHcORkjHp8YXJsBIl-Y9_Wpp9DMDxt2p2N_pmI");

              $arrayToSendv1 = array(
                  'registration_ids' => $firebaseToken,
                  "notification" => [
                      "title" => 'Fishmap',
                      "body" => $msg,
                      "msg" => $msg,
                      "sound" => true,
                      'badge' => $g_count,
                  ],
                  "data" => [
                      "title" => 'Fishmap',
                      "text" => $msg,
                      'notification_id' => $notification_id,
                      'type' => $type,
                      "feed_id" => $feed_id,
                      "notification_type" => $type,
                      "user_id" => $user_id,
                      "sound" => true,
                      'badge' => $g_count,
                  ],
                  'sound' => 'default',
                  'badge' => $g_count,
                  'notification_id' => $notification_id
              );

              $json = json_encode($arrayToSendv1);


              $ch = curl_init();
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
              @$response = curl_exec($ch);
              curl_close($ch);
       }

       function send_push_all_users($firebaseToken, $msg, $title, $type, $notification_id) {

              $firebaseToken = explode(',', $firebaseToken);

              $headers = array("Content-Type:" . "application/json",
                  "Authorization:" .
                  "key=" . "AAAA-5-UPo4:APA91bFOMwhiVzvXX30rrt6dm9E-qhyLnAHeQNDkpe70tVfpvVrK8Fub33CckKuVbge7hwknFgxaUpq11eYb-F5ezQ1amSXqFpNwY7GHcORkjHp8YXJsBIl-Y9_Wpp9DMDxt2p2N_pmI");

              $arrayToSendv1 = array(
                  'registration_ids' => $firebaseToken,
                  "notification" => [
                      "title" => $title,
                      "body" => $msg,
                      "text" => 'sample text',
                      "msg" => $msg,
                  ],
                  "data" => [
                      "title" => $title,
                      "text" => $msg,
                      'notification_id' => $notification_id,
                      'type' => $type
                  ],
                  'notification_id' => $notification_id
              );

              $json = json_encode($arrayToSendv1);


              $ch = curl_init();
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
              @$response = curl_exec($ch);
//              print_r($response);
              curl_close($ch);
//              exit;
       }

//       function send_push_test($firebaseToken, $msg, $type, $notification_id, $feed_id, $user_id) {
//              $firebaseToken = explode(',', $firebaseToken);
//
//
//              $headers = array("Content-Type:" . "application/json",
//                  "Authorization:" .
//                  "key=" . "AAAA-5-UPo4:APA91bFOMwhiVzvXX30rrt6dm9E-qhyLnAHeQNDkpe70tVfpvVrK8Fub33CckKuVbge7hwknFgxaUpq11eYb-F5ezQ1amSXqFpNwY7GHcORkjHp8YXJsBIl-Y9_Wpp9DMDxt2p2N_pmI");
//
//              $arrayToSendv1 = array(
//                  'registration_ids' => $firebaseToken,
//                  "notification" => [
//                      "title" => 'Fishmap',
//                      "body" => $msg,
//                      "msg" => $msg,
//                      "sound" => true,
//                  ],
//                  "data" => [
//                      "title" => 'Fishmap',
//                      "text" => $msg,
//                      'notification_id' => $notification_id,
//                      'type' => $type,
//                      "feed_id" => $feed_id,
//                      "notification_type" => $type,
//                      "user_id" => $user_id,
//                  ],
//                  'sound' => 'default',
//                  'badge' => '1',
//                  'notification_id' => $notification_id
//              );
//
//              $json = json_encode($arrayToSendv1);
//
//
//              $ch = curl_init();
//              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//              curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
//              curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//              curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
//              @$response = curl_exec($ch);
//              curl_close($ch);
//              print_r($response);
//       }

       public static function sendPushNotificationServiceProvider($firebaseToken, $title, $msg, $type) {
              $api_key = "AAAA-5-UPo4:APA91bFOMwhiVzvXX30rrt6dm9E-qhyLnAHeQNDkpe70tVfpvVrK8Fub33CckKuVbge7hwknFgxaUpq11eYb-F5ezQ1amSXqFpNwY7GHcORkjHp8YXJsBIl-Y9_Wpp9DMDxt2p2N_pmI";
              $url = 'https://fcm.googleapis.com/fcm/send';
              $data = array
                  (
                  'title' => $title,
                  'body' => $msg,
                  'message' => $msg,
                  'type' => $type,
                  'sound' => 'default',
                  'image' => '$image',
                  'icon' => '$image',
                  'activity' => 'MainActivity'
              );

              $notification = array(
                  'title' => $title,
                  'body' => $msg,
                  'click_action' => '.MainActivity',
                  'sound' => 'default',
                  'image' => '$image',
                  'icon' => '$image',
                  'badge' => '1'
              );

              $fields = array(
                  'registration_ids' => $firebaseToken,
                  'priority' => "high",
                  'data' => $data,
                  'notification' => $data,
                  'click_action' => '.MainActivity',
                  'sound' => 'default',
                  'badge' => '1'
              );

              $headers = array(
                  'Authorization: key = ' . $api_key,
                  'Content-Type: application/json'
              );

              // Open connection
              $ch = curl_init();
              // Set the url, number of POST vars, POST data
              curl_setopt($ch, CURLOPT_URL, $url);
              curl_setopt($ch, CURLOPT_POST, true);
              curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              // Disabling SSL Certificate support temporarly
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
              curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

              // Execute post
              $result = curl_exec($ch);
              if ($result === FALSE) {
                     die('Curl failed: ' . curl_error($ch));
              }
              // Close connection
              curl_close($ch);
              print_r($result);
              return $result;
       }

       // Check Thirdparty Email in Users Table
       function check_thirdparty_email($email, $thirdparty_id) {
              $users = User::select('*')
                      ->where([
                          ['email', ' = ', $email],
                          ['thirdparty_id', ' != ', $thirdparty_id],
                      ])
                      ->get()
                      ->toArray();
              return $users;
       }

       // Check Email in Users Table
       function check_signup_email($email) {
              $users = User::select('*')
                              ->where([
                                  ['email', ' = ', $email],
                              ])
                              ->get()->toArray();
              return $users;
       }

       // device Token Insert And Update
       function add_device($arr) {

              $where = array(
                  'device_id' => $arr['device_id'],
                  'device_type' => $arr['device_type'],
              );
              $check_device = $this->select('fm_token', '*', $where)->first();

              if ($check_device) {
                     $update = array(
                         'device_token' => $arr['device_token'],
                     );

                     $this->update('fm_token', $where, $update);
              } else {

                     $inn = array(
                         "device_token" => $arr['device_token'],
                         "device_type" => $arr['device_type'],
                         "device_id" => $arr['device_id'],
                         "user_id" => $arr['user_id'],
                     );
                     $this->insert('fm_token', $inn);
//            tbl_token::insert($inn);
              }
       }

       //Basic Function
       public function check_email($email) {

              $users = DB::table('users')
                              ->select('email')
                              ->where('email', $email)
                              ->get()->first();
              return $users;
       }

       public function get_random_string($length = 10) {
              $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
              $token = "";
              $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
              for ($i = 0; $i < $length; $i++) {
                     $n = rand(0, $alphaLength);
                     $token .= $alphabet[$n];
              }
              return $token;
       }

       function get_user_tokenn($user_id) {
              $q = "SELECT device_token,device_type FROM tbl_token WHERE `user_id` = $user_id";
              $results = DB::select($q);

              return $results;
       }

       public function get_random_stringorder_id($length = 8) {
              $alphabet = "0123456789";
              $token = "";
              $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
              for ($i = 0; $i < $length; $i++) {
                     $n = rand(0, $alphaLength);
                     $token .= $alphabet[$n];
              }
              return $token;
       }

       public function get_random_mobile_otp($length = 6) {
              $alphabet = "0123456789";
              $token = "";
              $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
              for ($i = 0; $i < $length; $i++) {
                     $n = rand(0, $alphaLength);
                     $token .= $alphabet[$n];
              }
              return $token;
       }

}
