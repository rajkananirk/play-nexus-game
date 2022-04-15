<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\fm_report;
use App\Models\fm_rank_limit_user;
use App\Models\Mdl_feed_image;
use App\Models\Mdl_feed_favrioute;
use App\Models\fm_users_rank_arrived;
use App\Models\Mdl_event_favrioute;
use App\Models\fm_notification;
use App\Models\fm_feed_image_favourite;
use App\Models\fm_all_feed;
use App\Models\Mdl_comment;
use App\Models\Mdl_event;
use App\Models\Mdl_comment_favourite;
use App\Models\Mdl_event_image;
use App\Models\Mdl_parking;
use App\Models\fm_user_chat_message;
use App\Models\Mdl_feed;
use App\Models\Mdl_category;
use App\Models\Mdl_feed_youtube;
use App\Models\fm_event_joins;
use App\Models\fm_feed_block;
use App\Models\fm_user_block;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Carbon;
use Validator;
use File;
use DB;
use App\Http\Controllers\BaseController;

class FeedController extends BaseController {

       public function GetHomeScreenData(Request $request) {
              $userId = Auth::id();
//              print_r($userId);
              $rule = [
                  'feed_lattitude' => 'required',
                  'feed_longitude' => 'required',
                  'page_no' => 'required',
              ];
              $this->Required_params($request, $rule);


              $feed_lattitude = $request->input('feed_lattitude');
              $feed_longitude = $request->input('feed_longitude');
              $event_date = $request->input('event_date');
              $location = $request->input('location');
              $title = $request->input('title');
              if ($event_date) {
                     $whereraw = "WHERE event_date = $event_date AND location = $location AND title = LIKE '%%$title%%'";
              } else {
                     $whereraw = '1=1';
              }
              $limit = 10;

              $page_no2 = $request->input('page_no');
              $page_no = ($page_no2 * $limit) - $limit;

              $lat1 = $feed_lattitude;
              $lng1 = $feed_longitude;
              $lat2 = "fm_all_feed.lattitude";
              $lng2 = "fm_all_feed.longitude";

              $event_data = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address as user_address',
                          Db::raw("$page_no2 as page_no"),
                          DB::raw("(SELECT COUNT(block_id) from fm_user_block where block_by = $userId AND block_to = fm_all_feed.added_by limit 1)as is_block_by_me"),
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_name"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                          DB::raw("TRUNCATE((3959 * acos(
                cos( radians($lat2) )
              * cos( radians( $lat1 ) )
              * cos( radians( $lng1 ) - radians($lng2) )
              + sin( radians($lat2) )
              * sin( radians( $lat1 ) )
                ) ),0) as distance_new")))
//                      ->inRandomOrder()
                      ->whereraw($whereraw)
                      ->with('image')
                      ->orderBy('feed_id', 'desc')
//                      ->having('distance_new', '<=', 50)
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->Having('is_block_by_me', '!=', 1)
                      ->limit($limit)
                      ->offset($page_no)
                      ->get();


              return $this->sendResponse(1, 'Home details successfully', $event_data);
       }

       public function GetLocationData(Request $request) {
              $userId = Auth::id();
              $rule = [
                  'feed_lattitude' => 'required',
                  'feed_longitude' => 'required',
                  'category_id' => 'required',
              ];
              $this->Required_params($request, $rule);


              $feed_lattitude = $request->input('feed_lattitude');
              $feed_longitude = $request->input('feed_longitude');
              $category_id = $request->input('category_id');
              $location = $request->input('location');
              $date = $request->input('date');
              $search = $request->input('title');
              if ($search) {
                     $my_f = "AND title LIKE '%%$search%%'";
              } else {
                     $my_f = " AND 1=1";
              }

              if ($location && $date) {
                     $where = " location = '$location' $my_f AND DATE(fm_all_feed.created_at) = '$date'";
              } else if ($location) {
                     $where = " location = '$location' $my_f ";
              } else if ($date) {
                     $where = " DATE(fm_all_feed.created_at) = '$date' $my_f ";
              } else {
                     $where = "1=1";
              }

              if ($category_id == 1) {
                     $types = 1;
                     $i_types = 1;
              } else if ($category_id == 2) {
                     $types = 2;
                     $i_types = 2;
              } else if ($category_id == 3) {
                     $types = 3;
                     $i_types = 3;
              } else {
                     $i_types = $category_id;
                     $types = $category_id;
              }

              $lat1 = $feed_lattitude;
              $lng1 = $feed_longitude;
              $lat2 = "fm_all_feed.lattitude";
              $lng2 = "fm_all_feed.longitude";

              $event_data = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address as user_address',
                          DB::raw("(SELECT COUNT(block_id) from fm_user_block where block_by = $userId AND block_to = fm_all_feed.added_by limit 1)as is_block_by_me"),
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id order by fm_feed_image.id desc limit 1)as feed_image"),
                          DB::raw("0 as is_tap"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                          DB::raw("TRUNCATE((3959 * acos(
                cos( radians($lat2) )
              * cos( radians( $lat1 ) )
              * cos( radians( $lng1 ) - radians($lng2) )
              + sin( radians($lat2) )
              * sin( radians( $lat1 ) )
                ) ),0) as distance_new")))
                      ->where('category_id', $types)
                      ->whereRaw($where)
                      ->with(['comment' => function ($q) use ($userId) {
                                 $q->join('users', function ($join) {
                                                $join->on('fm_comment.user_id', 'users.id');
                                         });
                                 $q->select(array(
                                     'fm_comment.*', 'users.user_name', 'users.profile_pic', 'users.address',
                                     DB::raw('(SELECT COUNT(t1.id) from fm_comment_favourite t1 where t1.comment_id = fm_comment.comment_id and fm_comment.status = 1)as count_likes'),
                                     DB::raw("(SELECT COUNT(t1.id) from fm_comment_favourite t1 where t1.comment_id = fm_comment.comment_id and t1.user_id = $userId)as is_like_me"),
                                 ));
                                 $q->orderBy('count_likes', 'desc');
                                 $q->limit(1);
                          }])
//                      ->inRandomOrder()
                      ->with('image')
                      ->orderBy('feed_id', 'desc')
//                      ->having('distance_new', '<=', 50)
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->Having('is_block_by_me', '!=', 1)
                      ->get();


              return $this->sendResponse(1, 'Home details successfully', $event_data);
       }

       public function GetCategoryScreenData(Request $request) {
              $userId = Auth::id();
              $rule = [
                  'page_no' => 'required',
                  'category_id' => 'required',
                  'feed_type' => 'required',
              ];
              $this->Required_params($request, $rule);


              $feed_type = $request->input('feed_type');
              $category_id = $request->input('category_id');
              $limit = 10;

              $page_no2 = $request->input('page_no');
              $event_date = $request->input('event_date');
              $location = $request->input('location');
              $title = $request->input('title');
              $page_no = ($page_no2 * $limit) - $limit;


              if ($location) {
                     $wherelocation = "AND location = '$location'";
              } else {
                     $wherelocation = 'AND 1=1';
              }
              if ($title) {
                     $wheretitle = "AND title LIKE '%%$title%%'";
              } else {
                     $wheretitle = 'AND 1=1';
              }
              if ($event_date) {
                     $wheredate = "AND event_date = '$event_date'";
              } else {
                     $wheredate = 'AND 1=1';
              }

//              if ($event_date) {
              $whereraw = " category_id = $category_id AND feed_type = $feed_type $wheredate $wherelocation $wheretitle";
//              } else {
//                     $whereraw = " category_id = $category_id AND feed_type = $feed_type  $wheredate $wherelocation $wheretitle";
//              }

              $event_data = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          Db::raw("$page_no2 as page_no"),
                          DB::raw("(SELECT COUNT(block_id) from fm_user_block where block_by = $userId AND block_to = fm_all_feed.added_by limit 1)as is_block_by_me"),
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_name"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                      ))
//                      ->inRandomOrder()
                      ->whereraw($whereraw)
                      ->with('image')
                      ->orderBy('feed_id', 'desc')
//                      ->having('distance_new', '<=', 50)
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->Having('is_block_by_me', '!=', 1)
                      ->limit($limit)
                      ->offset($page_no)
                      ->get();


              return $this->sendResponse(1, 'Home details successfully', $event_data);
       }

       public function AddNewParking(Request $request) {
              $rule = [
                  'parking_location' => 'required',
                  'parking_price' => 'required',
                  'parking_quantity_of_parking_spot' => 'required',
                  'parking_lattitude' => 'required',
                  'parking_longitude' => 'required',
                  'is_parking_beach' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();
              $tbl_parking = new Mdl_parking;
              $tbl_parking->parking_location = $request->input('parking_location');
              $tbl_parking->parking_price = $request->input('parking_price');
              $tbl_parking->parking_quantity_of_parking_spot = $request->input('parking_quantity_of_parking_spot');
              $tbl_parking->parking_lattitude = $request->input('parking_lattitude');
              $tbl_parking->parking_longitude = $request->input('parking_longitude');
              $tbl_parking->is_parking_beach = $request->input('is_parking_beach');
              $tbl_parking->parking_by = $userId;
              $tbl_parking->save();
              $parking_id = $tbl_parking->parking_id;

              if ($request->hasfile('parking_image')) {

                     foreach ($request->file('parking_image') as $image) {
                            $string = $this->get_random_string();
                            $imageName = time() . $string . '.' . $image->getClientOriginalExtension();
                            $image->move(public_path('uploads'), $imageName);

                            $job_data['parking_image'] = 'public/uploads/' . $imageName;
                            $job_data['parking_id'] = $parking_id;
                            $this->insert('fm_parking_image', $job_data);
                     }
              }
              $get = Mdl_parking::with('image')->where('parking_id', $tbl_parking->parking_id)->first();
              return $this->sendResponse(1, 'Parking Added Successfully', $get);
       }

       public function Feed_filter(Request $request) {
              $rule = [
                  'location' => 'required',
                  'event_date' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();
              $event_date = $request->input('event_date');
              $location = $request->input('location');


              $fm_all_feed = fm_all_feed::where(['event_date' => $event_date, 'location' => $location])->get();
              return $this->sendResponse(0, 'Favrioute succesfully', $fm_all_feed);
       }

       public function AddToFavourite(Request $request) {
              $rule = [
                  'feed_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $user_id = Auth::id();
              $feed_id = $request->input('feed_id');

              if ($feed_id) {
                     $check_favourite_list = Mdl_feed_favrioute::where(['user_id' => $user_id, 'feed_id' => $feed_id])->get()->first();
                     if (empty($check_favourite_list)) {
                            $rank_type = 4;
                            $tbl_feed_favourite = new Mdl_feed_favrioute;
                            $tbl_feed_favourite->user_id = $user_id;
                            $tbl_feed_favourite->feed_id = $feed_id;
                            $tbl_feed_favourite->save();
                            $this->add_rank_data($user_id, $rank_type, $feed_id);
                            return $this->sendResponse(1, 'Added to Favourite Successfully', $tbl_feed_favourite);
                     } else {

                            fm_users_rank_arrived::where(['user_id' => $user_id, 'rank_type' => 4, 'feed_id' => $feed_id])->delete();
                            $tbl_feed_favourite = Mdl_feed_favrioute::where(['user_id' => $user_id, 'feed_id' => $feed_id])->delete();
                            return $this->sendResponse(0, 'Delete Favrioute Succesfully', $tbl_feed_favourite);
                     }
              }

              return $this->sendResponse(0, 'Favrioute succesfully', null);
       }

       public function AddToFavouriteImage(Request $request) {
              $rule = [
                  'feed_id' => 'required',
                  'image_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $user_id = Auth::id();
              $feed_id = $request->input('feed_id');
              $image_id = $request->input('image_id');

              if ($feed_id) {
                     $check_favourite_list = fm_feed_image_favourite::where(['user_id' => $user_id, 'image_id' => $image_id, 'feed_id' => $feed_id])->get()->first();
                     if (empty($check_favourite_list)) {

                            $tbl_feed_favourite = new fm_feed_image_favourite;
                            $tbl_feed_favourite->user_id = $user_id;
                            $tbl_feed_favourite->feed_id = $feed_id;
                            $tbl_feed_favourite->image_id = $image_id;
                            $tbl_feed_favourite->save();
//                            $this->add_rank_data($user_id, $rank_type, $feed_id);
                            return $this->sendResponse(1, 'Added to Favourite Image Successfully', $tbl_feed_favourite);
                     } else {
                            $tbl_feed_favourite = fm_feed_image_favourite::where(['user_id' => $user_id, 'image_id' => $image_id, 'feed_id' => $feed_id])->delete();

                            return $this->sendResponse(0, 'Delete Favrioute Image Succesfully', $tbl_feed_favourite);
                     }
              }

              return $this->sendResponse(0, 'Favrioute succesfully', null);
       }

       public function CategoryIdToFeed(Request $request) {
              $userId = Auth::id();
              $rule = [
                  'feed_category_id' => 'required',
              ];

              $this->Required_params($request, $rule);

              $feed_category_id = $request->input('feed_category_id');


              $feed_category = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT COUNT(block_id) from fm_user_block where block_by = $userId AND block_to = fm_all_feed.added_by limit 1)as is_block_by_me"),
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id AND comment_type = fm_all_feed.feed_type AND status = 0 )as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                      ))
                      ->with('image')
                      ->where('feed_category_id', $feed_category_id)
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->Having('is_block_by_me', '!=', 1)
                      ->get();
              return $this->sendResponse(1, 'Feed Category Data Successfully', $feed_category);
       }

       public function FeedDetails(Request $request) {
              $rule = [
                  'feed_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();

              $feed_id = $request->input('feed_id');
              $feed_data = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address as user_address', 'users.phone_number',
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(select COUNT(join_id) from fm_event_joins t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId and t5.status = 0)as is_join_me"),
                          DB::raw("(select COUNT(join_id) from fm_event_joins t5 where t5.feed_id = fm_all_feed.feed_id and t5.status = 0)as join_count"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id AND comment_type = fm_all_feed.feed_type AND status = 0 )as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                      ))
                      ->with(['image' => function ($q) use ($userId) {
                                 $q->select(array(
                                     'fm_feed_image.*',
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id and t5.user_id = $userId)as is_liked_me"),
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id)as liked_count")
                                 ));
                                 $q->orderBy('liked_count', 'desc');
                          }])
//                      ->with('image')
                      ->orderBy('feed_id', 'desc')
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->where('feed_id', $feed_id)
                      ->get();


              return $this->sendResponse(1, 'Feed Details Successfully', $feed_data);
       }

       public function FeedDetailsCategory(Request $request) {
              $rule = [
                  'feed_id' => 'required',
                  'status' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();

              $feed_id = $request->input('feed_id');
              $status = $request->input('status');
              if ($status == 1) { //feed
                     $feed_data['Fish'] = Mdl_feed_image::where('feed_id', $feed_id)
                             ->select('*',
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id and t5.user_id = $userId)as is_liked_me"),
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id)as liked_count")
                             )
                             ->where('image_type', 1)
                             ->orderBy('liked_count', 'desc')
                             ->get();

                     $feed_data['Terreain'] = Mdl_feed_image::where('feed_id', $feed_id)
                             ->select('*',
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id and t5.user_id = $userId)as is_liked_me"),
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id)as liked_count")
                             )
                             ->where('image_type', 2)
                             ->orderBy('liked_count', 'desc')
                             ->get();

                     $feed_data['Parking'] = Mdl_feed_image::where('feed_id', $feed_id)
                             ->select('*',
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id and t5.user_id = $userId)as is_liked_me"),
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id)as liked_count")
                             )
                             ->where('image_type', 3)
                             ->orderBy('liked_count', 'desc')
                             ->get();
              } else {

                     $feed_data['RestImage'] = Mdl_feed_image::where('feed_id', $feed_id)
                             ->select('*',
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id and t5.user_id = $userId)as is_liked_me"),
                                     DB::raw("(select COUNT(id) from fm_feed_image_favourite t5 where t5.image_id = fm_feed_image.id)as liked_count")
                             )
                             ->where('image_type', 4)
                             ->orderBy('liked_count', 'desc')
                             ->get();
              }


              return $this->sendResponse(1, 'Feed Details Successfully', $feed_data);
       }

       public function AddPostYoutubeEvent(Request $request) {
              $rule = [
                  'feed_type' => 'required',
//                  'about' => 'required',
//                  'lattitude' => 'required',
//                  'longitude' => 'required',
//                  'location' => 'required',
//                  'category_id' => 'required',
//                  'hotel_name' => 'required',
//                  'feed_price' => 'required',
//                  'event_register_link' => 'required',
//                  'youtube_url' => 'required',
//                  'is_feed_spot' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();

              $feed_type = $request->input('feed_type');
              $about = $request->input('about');
              $lattitude = $request->input('lattitude');
              $longitude = $request->input('longitude');
              $location = $request->input('location');
              $hotel_name = $request->input('hotel_name');
              $hotel_price = $request->input('hotel_price');
              $event_date = $request->input('event_date');
              $event_register_link = $request->input('event_register_link');
              $youtube_url = $request->input('youtube_url');
              $is_feed_spot = $request->input('is_feed_spot');
              $restaurant_name = $request->input('restaurant_name');
              $category_id = $request->input('category_id');
              $title = $request->input('title');
              $event_prefecture = $request->input('event_prefecture');
              $event_member = $request->input('event_member');
              $is_parking_beach = $request->input('is_parking_beach');
              $parking_quantity_of_parking_spot = $request->input('parking_quantity_of_parking_spot');
              $parking_price = $request->input('parking_price');
              $restaurant_price = $request->input('restaurant_price');
              $address = $request->input('address');

              $tbl_feed = new fm_all_feed;
              $tbl_feed->address = $address;
              $tbl_feed->feed_type = $feed_type;
              $tbl_feed->about = $about;
              $tbl_feed->title = $title;
              $tbl_feed->is_feed_spot = $is_feed_spot;
              $tbl_feed->lattitude = $lattitude;
              $tbl_feed->longitude = $longitude;
              $tbl_feed->location = $location;
              $tbl_feed->added_by = $userId;
              $tbl_feed->hotel_name = $hotel_name;
              $tbl_feed->hotel_price = $hotel_price;
              $tbl_feed->event_date = $event_date;
              $tbl_feed->is_feed_spot = $is_feed_spot;
              $tbl_feed->category_id = $category_id;
              $tbl_feed->youtube_url = $youtube_url;
              $tbl_feed->restaurant_name = $restaurant_name;
              $tbl_feed->event_register_link = $event_register_link;
              $tbl_feed->event_prefecture = $event_prefecture;
              $tbl_feed->event_member = $event_member;
              $tbl_feed->is_parking_beach = $is_parking_beach;
              $tbl_feed->parking_quantity_of_parking_spot = $parking_quantity_of_parking_spot;
              $tbl_feed->restaurant_price = $restaurant_price;
              $tbl_feed->parking_price = $parking_price;
              $tbl_feed->save();
              $feed_id = $tbl_feed->feed_id;

              if ($request->hasfile('feed_image')) {

                     foreach ($request->file('feed_image') as $image) {
                            $string = $this->get_random_string();
                            $imageName = time() . $string . '.' . $image->getClientOriginalExtension();
                            $image->move(public_path('uploads'), $imageName);

                            $job_data['feed_image'] = 'public/uploads/' . $imageName;
                            $job_data['feed_id'] = $feed_id;
                            $job_data['image_type'] = 1;
                            $job_data['user_id'] = $userId;
                            $this->insert('fm_feed_image', $job_data);
                     }
              }




              $of_users = User::where('id', '!=', $userId)
                      ->join('fm_token', 'users.id', '=', 'fm_token.user_id')
                      ->get();
              foreach ($of_users as $values) {
                     $type = 1;
                     $activity_to = $values->id;

                     $of_notification_check = fm_notification::where([
                                 'notification_by' => $userId,
                                 'notification_to' => $activity_to,
                                 'feed_id' => $feed_id,
                                 'notification_type' => 1
                             ])
                             ->first();

                     if (empty($of_notification_check)) {
                            if ($feed_type == 3) {
                                   $msg2 = "New event has been added recently!";
                            } else if ($feed_type == 2) {
                                   $msg2 = "New youtube has been added recently!";
                            } else if ($feed_type == 4) {
                                   $msg2 = "New parking has been added recently!";
                            } else if ($feed_type == 1) {
                                   $msg2 = "New feed has been added recently!";
                            }

                            $notification_id = rand(000000, 11111);

                            $user_id = 0;

                            $of_notification = new fm_notification();
                            $of_notification->notification_by = $userId;
                            $of_notification->notification_to = $activity_to;
                            $of_notification->notification_text = $msg2;
                            $of_notification->notification_type = 1;
                            $of_notification->feed_id = $feed_id;
                            $of_notification->notification_uniqe_id = rand(000000, 11111);
                            $of_notification->save();

                            if ($feed_type == 3) {
                                   $msg = "New event has been added recently!";
                            } else if ($feed_type == 2) {
                                   $msg = "New youtube has been added recently!";
                            } else if ($feed_type == 4) {
                                   $msg = "New parking has been added recently!";
                            } else if ($feed_type == 1) {
                                   $msg = "New feed has been added recently!";
                            }

//                                   $data22 = $this->get_user_token($activity_to);
                            $g_count = $this->get_unseen_count_user($user_id);
                            $tokens = $values->device_token;

                            $this->send_push($tokens, $msg, $type, $g_count, $notification_id, $feed_id, $user_id);
                     } else {
                            fm_notification::where(['notification_by' => $userId, 'notification_to' => $activity_to, 'feed_id' => $feed_id, 'notification_type' => 1])->delete();
                     }
              }

              if ($feed_type == 1) { //feed
                     $rank_type = 1;
              } else if ($feed_type == 2) { //2-youtube
                     $rank_type = 8;
              } else if ($feed_type == 3) { //3-event
                     $rank_type = 9;
              } else if ($feed_type == 4) { //4-parking
                     $rank_type = 2;
              } else if ($category_id == 2) { //Terrain
                     $rank_type = 6;
              } else if ($category_id == 3) { //Restaurant
                     $rank_type = 5;
              } else if ($category_id == 4) { //hotel
                     $rank_type = 7;
              } else if ($is_feed_spot == 1) {
                     $rank_type = 3;
              }

              $this->add_rank_data($userId, $rank_type, $feed_id);
              return $this->sendResponse(1, 'Feed Added Successfully', null);
       }

       public function EditPostYoutubeEvent(Request $request) {
              $rule = [
                  'feed_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();

              $feed_id = $request->input('feed_id');
              $about = $request->input('about');
              $lattitude = $request->input('lattitude');
              $longitude = $request->input('longitude');
              $location = $request->input('location');
              $hotel_name = $request->input('hotel_name');
              $hotel_price = $request->input('hotel_price');
              $event_date = $request->input('event_date');
              $event_register_link = $request->input('event_register_link');
              $youtube_url = $request->input('youtube_url');
              $is_feed_spot = $request->input('is_feed_spot');
              $restaurant_name = $request->input('restaurant_name');
              $category_id = $request->input('category_id');
              $title = $request->input('title');
              $event_prefecture = $request->input('event_prefecture');
              $event_member = $request->input('event_member');
              $is_parking_beach = $request->input('is_parking_beach');
              $parking_quantity_of_parking_spot = $request->input('parking_quantity_of_parking_spot');
              $parking_price = $request->input('parking_price');
              $restaurant_price = $request->input('restaurant_price');
              $address = $request->input('address');

              if ($is_parking_beach) {
                     $update_data['is_parking_beach'] = $is_parking_beach;
              }
              if ($parking_quantity_of_parking_spot) {
                     $update_data['parking_quantity_of_parking_spot'] = $parking_quantity_of_parking_spot;
              }
              if ($parking_price) {
                     $update_data['parking_price'] = $parking_price;
              }
              if ($restaurant_price) {
                     $update_data['restaurant_price'] = $restaurant_price;
              }
              if ($about) {
                     $update_data['about'] = $about;
              }
              if ($address) {
                     $update_data['address'] = $address;
              }
              if ($title) {
                     $update_data['title'] = $title;
              }
              if ($is_feed_spot) {
                     $update_data['is_feed_spot'] = $is_feed_spot;
              }
              if ($lattitude) {
                     $update_data['lattitude'] = $lattitude;
              }
              if ($longitude) {
                     $update_data['longitude'] = $longitude;
              }
              if ($location) {
                     $update_data['location'] = $location;
              }
              if ($hotel_name) {
                     $update_data['hotel_name'] = $hotel_name;
              }
              if ($hotel_price) {
                     $update_data['hotel_price'] = $hotel_price;
              }
              if ($event_date) {
                     $update_data['event_date'] = $event_date;
              }
              if ($category_id) {
                     $update_data['category_id'] = $category_id;
              }
              if ($restaurant_name) {
                     $update_data['restaurant_name'] = $restaurant_name;
              }
              if ($youtube_url) {
                     $update_data['youtube_url'] = $youtube_url;
              }
              if ($event_prefecture) {
                     $update_data['event_prefecture'] = $event_prefecture;
              }
              if ($event_register_link) {
                     $update_data['event_register_link'] = $event_register_link;
              }
              if ($event_member) {
                     $update_data['event_member'] = $event_member;
              }


              if ($request->hasfile('feed_image')) {

                     foreach ($request->file('feed_image') as $image) {
                            $string = $this->get_random_string();
                            $imageName = time() . $string . '.' . $image->getClientOriginalExtension();
                            $image->move(public_path('uploads'), $imageName);

                            $job_data['feed_image'] = 'public/uploads/' . $imageName;
                            $job_data['feed_id'] = $feed_id;
                            $this->insert('fm_feed_image', $job_data);
                     }
              }
              if (!empty($update_data)) {
                     fm_all_feed::where('feed_id', $feed_id)->with('image')->update($update_data);
              }


              $tbl_feed = fm_all_feed::where('feed_id', $feed_id)->with('image')->get();
              return $this->sendResponse(1, 'Feed Edited Successfully', $tbl_feed);
       }

       public function Addimages(Request $request) {
              $rule = [
                  'feed_id' => 'required',
                  'image_type' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();

              $feed_id = $request->input('feed_id');
              $image_type = $request->input('image_type');

              if ($request->hasfile('feed_image')) {

                     foreach ($request->file('feed_image') as $image) {
                            $string = $this->get_random_string();
                            $imageName = time() . $string . '.' . $image->getClientOriginalExtension();
                            $image->move(public_path('uploads'), $imageName);

                            $job_data['feed_image'] = 'public/uploads/' . $imageName;
                            $job_data['feed_id'] = $feed_id;
                            $job_data['image_type'] = $image_type;
                            $job_data['user_id'] = $userId;
                            $this->insert('fm_feed_image', $job_data);
                     }
              }

              $tbl_feed = fm_all_feed::where('feed_id', $feed_id)->with('image')->get();
              return $this->sendResponse(1, 'Feed Images Add Successfully', $tbl_feed);
       }

       public function GetPrefecture(Request $request) {
              $user = User::select('prefecture', 'id')->get();
              return $this->sendResponse(1, 'Prefecture List Successfully', $user);
       }

       public function EventDetails(Request $request) {
              $rule = [
                  'event_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();

              $event_id = $request->input('event_id');
              $event_data = Mdl_event::with('image')->select(array('fm_event.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT event_image from fm_event_image where fm_event_image.event_id = fm_event.event_id limit 1)as event_image"),
                          DB::raw("(SELECT COUNT(user_id) from fm_event_favrioute t5 where t5.event_id = fm_event.event_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.event_id = fm_event.event_id and t5.comment_type = 2)as total_comment"),
                          DB::raw("(select COUNT(user_id) from fm_event_favrioute t5 where t5.event_id = fm_event.event_id)as total_likes"),
                          DB::raw("(select COUNT(user_id) from fm_comment t5 where t5.event_id = fm_event.event_id)as total_comment")))
                      ->join('users', 'fm_event.event_by', '=', 'users.id')
                      ->where('event_id', $event_id)
                      ->first();
              return $this->sendResponse(1, 'Event Details Successfully', $event_data);
       }

       public function AddComment(Request $request) {
              $rule = [
                  'comment_text' => 'required',
                  'comment_type' => 'required',
                  'feed_id' => 'required',
                  'comment_id' => 'required',
              ];
              $this->Required_params($request, $rule);
              $userId = Auth::id();
              $feed_id = $request->input('feed_id');
              $comment = $request->input('comment_id');

              if ($comment == 0) {
                     $comment_id = 0;
                     $status = 0;
              } else {
                     $comment_id = $comment;
                     $status = 1;
              }

              $tbl_comment = new Mdl_comment;
              $tbl_comment->comment_text = $request->input('comment_text');
              $tbl_comment->comment_parent_id = $comment_id;
              $tbl_comment->feed_id = $feed_id;
              $tbl_comment->status = $status;
              $tbl_comment->comment_type = $request->input('comment_type');
              $tbl_comment->user_id = $userId;
              $tbl_comment->save();

              $Mdl_comment = Mdl_comment::select(array('fm_comment.*', 'users.user_name', 'users.profile_pic',
                          DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                          DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id)as total_likes"),
                          DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")))
                      ->with(['replies' => function ($q) use ($userId) {
                                 $q->join('users', function ($join) {
                                                $join->on('fm_comment.user_id', 'users.id');
                                         });
                                 $q->select(array(
                                     'fm_comment.*', 'users.user_name', 'users.profile_pic',
                                     DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                                     DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id and t5.user_id = $userId)as is_liked_me"),
                                     DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id)as total_likes"),
                                     DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")
                                 ));
                                 $q->orderBy('total_likes', 'desc');
                          }])
                      ->join('users', 'fm_comment.user_id', '=', 'users.id')
                      ->where('comment_id', $tbl_comment->comment_id)
                      ->get();
              return $this->sendResponse(1, 'Comment Added Successfully', $Mdl_comment);
       }

       public function get_comment(Request $request) {
              $rule = [
                  'feed_id' => 'required',
                  'page_no' => 'required',
              ];
              $this->Required_params($request, $rule);
              $userId = Auth::id();
              $feed_id = $request->input('feed_id');
              $limit = 10;

              $page_no2 = $request->input('page_no');
              $page_no = ($page_no2 * $limit) - $limit;

              $Mdl_comment = Mdl_comment::select(array('fm_comment.*', 'users.user_name', 'users.profile_pic',
                          DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                          DB::raw("($page_no2)as page_no"),
                          DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id)as total_likes"),
                          DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")))
                      ->with(['replies' => function ($q) use ($userId) {
                                 $q->join('users', function ($join) {
                                                $join->on('fm_comment.user_id', 'users.id');
                                         });
                                 $q->select(array(
                                     'fm_comment.*', 'users.user_name', 'users.profile_pic',
                                     DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                                     DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id and t5.user_id = $userId)as is_liked_me"),
                                     DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id)as total_likes"),
                                     DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")
                                 ));
                                 $q->orderBy('total_likes', 'desc');
                          }])
                      ->join('users', 'fm_comment.user_id', '=', 'users.id')
                      ->where('feed_id', $feed_id)
                      ->where('status', 0)
                      ->orderBy('comment_id', 'desc')
                      ->orderBy('total_likes', 'desc')
                      ->limit($limit)
                      ->offset($page_no)
                      ->get();
              return $this->sendResponse(1, 'Comment List Successfully', $Mdl_comment);
       }

       public function LikeToComment(Request $request) {
              $rule = [
                  'comment_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $user_id = Auth::id();
              $comment_id = $request->input('comment_id');

              $check_favourite_list = Mdl_comment_favourite::where(['user_id' => $user_id, 'comment_id' => $comment_id])->get()->first();
              if (empty($check_favourite_list)) {
                     $tbl_feed_favourite = new Mdl_comment_favourite;
                     $tbl_feed_favourite->user_id = $user_id;
                     $tbl_feed_favourite->comment_id = $comment_id;
                     $tbl_feed_favourite->save();
                     $Mdl_comment = Mdl_comment::select(array('fm_comment.*', 'users.user_name', 'users.profile_pic',
                                 DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                                 DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id and t5.user_id = $user_id)as is_liked_me"),
                                 DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id)as total_likes"),
                                 DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")))
                             ->with(['replies' => function ($q) use ($user_id) {
                                        $q->join('users', function ($join) {
                                                       $join->on('fm_comment.user_id', 'users.id');
                                                });
                                        $q->select(array(
                                            'fm_comment.*', 'users.user_name', 'users.profile_pic',
                                            DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                                            DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id and t5.user_id = $user_id)as is_liked_me"),
                                            DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id)as total_likes"),
                                            DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")
                                        ));
                                        $q->orderBy('total_likes', 'desc');
                                 }])
                             ->join('users', 'fm_comment.user_id', '=', 'users.id')
                             ->where('comment_id', $comment_id)
                             ->where('status', 0)
                             ->get();
                     return $this->sendResponse(1, 'Added to Favourite Successfully', $Mdl_comment);
              } else {
                     $tbl_feed_favourite = Mdl_comment_favourite::where(['user_id' => $user_id, 'comment_id' => $comment_id])->delete();
                     $Mdl_comment = Mdl_comment::select(array('fm_comment.*', 'users.user_name', 'users.profile_pic',
                                 DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                                 DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id and t5.user_id = $user_id)as is_liked_me"),
                                 DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id)as total_likes"),
                                 DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")))
                             ->with(['replies' => function ($q) use ($user_id) {
                                        $q->join('users', function ($join) {
                                                       $join->on('fm_comment.user_id', 'users.id');
                                                });
                                        $q->select(array(
                                            'fm_comment.*', 'users.user_name', 'users.profile_pic',
                                            DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                                            DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id and t5.user_id = $user_id)as is_liked_me"),
                                            DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id)as total_likes"),
                                            DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")
                                        ));
                                        $q->orderBy('total_likes', 'desc');
                                 }])
                             ->join('users', 'fm_comment.user_id', '=', 'users.id')
                             ->where('comment_id', $comment_id)
                             ->where('status', 0)
                             ->get();
                     return $this->sendResponse(0, 'Delete Favrioute Succesfully', $Mdl_comment);
              }

              return $this->sendResponse(0, 'Favrioute succesfully', null);
       }

       public function YoutubeCategoryData(Request $request) {
              $rule = [
                  'category_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();
              $category_id = $request->input('category_id');
              $youtube_data = Mdl_feed_youtube::select(array('fm_feed_youtube.*', 'users.user_name',
                          DB::raw("(SELECT COUNT(id) from fm_youtube_favrioute t5 where t5.youtube_id = fm_feed_youtube.youtube_id)as total_likes"),
                          DB::raw("(SELECT COUNT(id) from fm_youtube_favrioute t5 where t5.youtube_id = fm_feed_youtube.youtube_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.event_id = fm_feed_youtube.youtube_id AND t5.comment_type = 3)as total_comment")))
                      ->join('users', 'fm_feed_youtube.user_id', '=', 'users.id')
                      ->where('category_id', $category_id)
                      ->get();
              return $this->sendResponse(1, 'Youtube details successfully', $youtube_data);
       }

       public function GetEventComment(Request $request) {
              $rule = [
                  'event_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = Auth::id();
              $event_id = $request->input('event_id');
              $comment_data = Mdl_comment::with('replies')->select(array('fm_comment.*', 'users.user_name', 'users.profile_pic',
                          DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                          DB::raw("(select COUNT(user_id) from fm_event_favrioute t5 where t5.event_id = fm_comment.comment_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id AND t5.favourite_type = 3)as total_likes"),
                          DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")))
                      ->with(['replies' => function ($q) use ($userId) {
                                 $q->join('users', function ($join) {
                                                $join->on('of_comments.user_id', 'users.id');
                                         });
                                 $q->select(array(
                                     'Mdl_comment.*', 'users.user_name', 'users.profile_pic',
                                     DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date"),
                                     DB::raw("(select COUNT(user_id) from fm_event_favrioute t5 where t5.event_id = fm_comment.comment_id and t5.user_id = $userId)as is_liked_me"),
                                     DB::raw("(select COUNT(t5.id) from fm_comment_favourite t5 where t5.comment_id = fm_comment.comment_id AND t5.favourite_type = 3)as total_likes"),
                                     DB::raw("DATE_FORMAT(fm_comment.created_at,'%d %M, %Y') as comment_date")
                                 ));
                                 $q->orderBy('count_likes', 'desc');
                          }])
                      ->join('users', 'fm_comment.user_id', '=', 'users.id')
                      ->where('event_id', $event_id)
                      ->where('status', 0)
                      ->get();

              $this->sendResponse(1, 'Comment details successfully', $comment_data);
       }

       public function AddReplyComment(Request $request) {
              $rule = [
                  'comment_text' => 'required',
                  'comment_id' => 'required',
              ];
              $this->Required_params($request, $rule);
              $userId = Auth::id();
              $feed_id = $request->input('feed_id');
              $event_id = $request->input('event_id');

              $tbl_comment = new Mdl_comment;
              $tbl_comment->event_id = $event_id;
              $tbl_comment->feed_id = $feed_id;
              $tbl_comment->comment_text = $request->input('comment_text');
              $tbl_comment->event_id = $request->input('event_id');
              $tbl_comment->reply_id = $request->input('comment_id');
              $tbl_comment->status = 1;
              $tbl_comment->user_id = $userId;
              $tbl_comment->save();
              return $this->sendResponse(1, 'Reply Added Successfully', $tbl_comment);
       }

       public function CategoryByEvent(Request $request) {
              $userId = Auth::id();
              $rule = [
                  'event_category_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $event_category_id = $request->input('event_category_id');
              $event_category = Mdl_event::select(array('fm_event.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT COUNT(id) from fm_event_favrioute t5 where t5.event_id = fm_event.event_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(select COUNT(id) from fm_event_favrioute t5 where t5.event_id = fm_event.event_id)as total_likes"),
                          DB::raw("(select COUNT(t5.comment_id) from fm_comment t5 where t5.event_id = fm_event.event_id and t5.comment_type = 2)as total_comment")))
                      ->join('users', 'fm_event.event_by', '=', 'users.id')
                      ->where('event_category_id', $event_category_id)
                      ->get();
              return $this->sendResponse(1, 'Data Event Category Successfully', $event_category);
       }

       public function GetCategory(Request $request) {
              $rule = [
                  'category_type' => 'required',
              ];

              $this->Required_params($request, $rule);
              $category_type = $request->input('category_type');
              $tbl_category = Mdl_category::where('category_type', $category_type)->get();
              return $this->sendResponse(1, 'Category data successfully', $tbl_category);
       }

       public function delete_image(Request $request) {
              $rule = [
                  'id' => 'required',
              ];
              $this->Required_params($request, $rule);

              $id = $request->input('id');
              $tbl_category = Mdl_feed_image::where('id', $id)->delete();
              return $this->sendResponse(1, 'Image Delete successfully', $tbl_category);
       }

       public function DeleteFeed(Request $request) {
              $rule = [
                  'feed_id' => 'required',
              ];
              $this->Required_params($request, $rule);
              $feed_id = $request->input('feed_id');

              fm_all_feed::where('feed_id', $feed_id)->delete();
              Mdl_feed_image::where('feed_id', $feed_id)->delete();
              Mdl_feed_favrioute::where('feed_id', $feed_id)->delete();

              return $this->sendResponse(1, 'Feed delete successfully', null);
       }

       public function EventDelete(Request $request) {
              $rule = [
                  'event_id' => 'required',
              ];
              $this->Required_params($request, $rule);
              $event_id = $request->input('event_id');
              Mdl_event_image::where('event_id', $event_id)->delete();
              Mdl_event::where('event_id', $event_id)->delete();
              Mdl_event_favrioute::where('event_id', $event_id)->delete();
              return $this->sendResponse(1, 'Event delete successfully', null);
       }

       public function JoinEvent(Request $request) {
              $rule = [
                  'feed_id' => 'required',
              ];
              $this->Required_params($request, $rule);
              $feed_id = $request->input('feed_id');
              $userId = Auth::id();
              $check = fm_event_joins::where(['feed_id' => $feed_id, 'user_id' => $userId])->first();

              if (empty($check)) {

                     $tbl_comment = new fm_event_joins;
                     $tbl_comment->feed_id = $feed_id;
                     $tbl_comment->user_id = $userId;
                     $tbl_comment->status = 0;
                     $tbl_comment->save();
                     return $this->sendResponse(1, 'Event Join Successfully', null);
              } else {
                     if ($check->status == 1) {
                            $check = fm_event_joins::where(['feed_id' => $feed_id, 'user_id' => $userId])->update(['status' => 0]);
                            return $this->sendResponse(1, 'Event Join Successfully', null);
                     } else {

                            $check = fm_event_joins::where(['feed_id' => $feed_id, 'user_id' => $userId])->update(['status' => 1]);
                            return $this->sendResponse(0, 'Event Cancel Successfully', null);
                     }
              }
       }

       public function EventList(Request $request) {
              $userId = Auth::id();

//              print_r($userId);
              $event_data['top_data'] = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                      ))
                      ->where('feed_type', 3)
                      ->where('is_top_event', 2)
                      ->orderBy('feed_id', 'desc')
                      ->leftjoin('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->get();

              $event_data['Event_list'] = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                      ))
//                      ->join('fm_event_joins', 'fm_all_feed.feed_id', '=', 'fm_event_joins.feed_id')
//                      ->inRandomOrder()
                      ->where('feed_type', 3)
                      ->with('image')
                      ->join('fm_event_joins', 'fm_all_feed.feed_id', '=', 'fm_event_joins.feed_id')
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->where('fm_event_joins.user_id', $userId)
                      ->orderBy('feed_id', 'desc')
                      ->get();


              return $this->sendResponse(1, 'Event List Successfully', $event_data);
       }

       public function OwnerEventList(Request $request) {
              $userId = Auth::id();


              $event_data = fm_all_feed::select(array(
                          'fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(SELECT category_image from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_image"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(t5.comment_id) from fm_comment t5 where t5.feed_id = fm_all_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                      ))
//                      ->join('fm_event_joins', 'fm_all_feed.feed_id', '=', 'fm_event_joins.feed_id')
//                      ->inRandomOrder()
                      ->with('image')
                      ->where('feed_type', 3)
                      ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                      ->where('added_by', $userId)
                      ->orderBy('feed_id', 'desc')
                      ->get();


              return $this->sendResponse(1, 'Event List Successfully', $event_data);
       }

       public function GetNotification(Request $request) {
              $rule = [
                  'for_send' => 'required',
              ];
              $this->Required_params($request, $rule);
              $for_send = $request->input('for_send');

              $userId = Auth::id();
              $check = \App\Models\fm_notification::select(array('fm_notification.*', 'users.user_name', 'users.profile_pic'))
                      ->join('users', 'fm_notification.notification_by', '=', 'users.id')
                      ->where(['notification_to' => $userId])
                      ->where(['for_send' => $for_send])
                      ->get();

              return $this->sendResponse(1, 'Notification List Successfully', $check);
       }

       public function GetFishmapUser(Request $request) {

              $rule = [
                  'user_name' => 'required',
              ];
              $this->Required_params($request, $rule);
              $user_name = $request->input('user_name');
              $userId = Auth::id();

              $users = User::whereRaw("user_name LIKE '%$user_name%' OR '$user_name' LIKE CONCAT('%', user_name, '%')")
                      ->Having('id', '!=', $userId)
                      ->get();

              return $this->sendResponse(1, 'Users List Successfully', $users);
       }

       public function filter_by_latlong(Request $request) {
              $userId = Auth::id();
              $rule = [
                  'feed_lattitude' => 'required',
                  'feed_longitude' => 'required',
                  'feed_category_id' => 'required',
              ];
              $this->Required_params($request, $rule);


              $feed_lattitude = $request->input('feed_lattitude');
              $feed_longitude = $request->input('feed_longitude');
              $lat1 = $feed_lattitude;
              $lng1 = $feed_longitude;
              $lat2 = "fm_feed.feed_lattitude";
              $lng2 = "fm_feed.feed_longitude";
              $feed_category_id = $request->input('feed_category_id');
              $feed_list = Mdl_feed::select(array('*',
                          DB::raw("(select COUNT(t5.user_id) from fm_feed_favrioute t5 where t5.feed_id = fm_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("TRUNCATE((3959 * acos(
                cos( radians($lat2) )
              * cos( radians( $lat1 ) )
              * cos( radians( $lng1 ) - radians($lng2) )
              + sin( radians($lat2) )
              * sin( radians( $lat1 ) )
                ) ),0) as distance_new")))
                      ->orderBy('distance_new', 'asc')
                      ->having('distance_new', '<=', 50)
                      ->where('feed_category_id', $feed_category_id)
                      ->get();

              return $this->sendResponse(1, 'Filter latlong Successfully', $feed_list);
       }

       public function feed_home(Request $request) {
              $userId = Auth::id();
              $rule = [
                  'feed_lattitude' => 'required',
                  'feed_longitude' => 'required',
              ];
              $this->Required_params($request, $rule);

              $feed_lattitude = $request->input('feed_lattitude');
              $feed_longitude = $request->input('feed_longitude');
              $lat1 = $feed_lattitude;
              $lng1 = $feed_longitude;
              $lat2 = "fm_feed.feed_lattitude";
              $lng2 = "fm_feed.feed_longitude";
              $feed_data['details'] = Mdl_feed::select(array('fm_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                          DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_feed.feed_id limit 1)as feed_image"),
                          DB::raw("(select COUNT(user_id) from fm_feed_favrioute t5 where t5.feed_id = fm_feed.feed_id and t5.user_id = $userId)as is_liked_me"),
                          DB::raw("(SELECT COUNT(user_id) from fm_comment t5 where t5.event_id = fm_feed.feed_id and t5.user_id = $userId)as is_comment_me"),
                          DB::raw("(SELECT COUNT(user_id) from fm_comment t5 where t5.event_id = fm_feed.feed_id)as total_comment"),
                          DB::raw("(select COUNT(user_id) from fm_feed_favrioute t5 where t5.feed_id = fm_feed.feed_id)as total_likes"),
                          DB::raw("TRUNCATE((3959 * acos(
                cos( radians($lat2) )
              * cos( radians( $lat1 ) )
              * cos( radians( $lng1 ) - radians($lng2) )
              + sin( radians($lat2) )
              * sin( radians( $lat1 ) )
                ) ),0) as distance_new")))
                      ->orderBy('distance_new', 'asc')
                      ->having('distance_new', '<=', 50)
                      ->join('users', 'fm_feed.feed_by', '=', 'users.id')
                      ->get();

              return $this->sendResponse(1, 'Filter latlong Successfully', $feed_data);
       }

       public function get_profile(Request $request) {
              $userId = Auth::id();
              $feed_id = $request->input('feed_id');
              $user['profile_list'] = User::select('users.user_name', 'users.profile_pic', 'users.address', 'users.id')
                      ->where('id', $userId)
                      ->get();
              $user['Total_post'] = Mdl_feed::where('feed_by', $userId)->get()->count();
              $user['Total_image'] = Mdl_feed_image::where('feed_id', $feed_id)->get()->count();
              $user['Total_likes'] = Mdl_feed_favrioute::where('user_id', $userId)->get()->count();
              $user['Total_posts'] = Mdl_feed::with('image')->select(array('fm_feed.feed_title', 'fm_feed.feed_id',
                          DB::raw("(select COUNT(user_id) from fm_feed_favrioute t5 where t5.feed_id = fm_feed.feed_id)as total_likes")))
                      ->join('users', 'fm_feed.feed_by', '=', 'users.id')
                      ->where('feed_by', $userId)
                      ->get();
              return $this->sendResponse(1, 'Profile data successfully', $user);
       }

       public function GetRankSystem(Request $request) {
              $rule = [
                  'user_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = $request->input('user_id');
              $data = fm_rank_limit_user::select(array('*',
                          DB::raw("IFNULL((SELECT SUM(rank_point) FROM fm_users_rank_arrived WHERE user_id = $userId AND rank_id = fm_rank_limit_user.rank_id AND created_at = now()),0)as arrvied_rank")))
                      ->get();
              return $this->sendResponse(1, 'Ranking data successfully', $data);
       }

       public function get_my_rank_system(Request $request) {
              $userId = Auth::id();

              $data = fm_users_rank_arrived::select(array('created_at', 'rank_id', 'dates',
                          DB::raw("(SELECT rank_name FROM fm_rank_limit_user WHERE rank_id = fm_users_rank_arrived.rank_id)as rank_name"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 1 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_feed"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 2 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_parking"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 3 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_add_spot"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 4 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_likes"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 5 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_restaurant"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 6 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_terrain"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 7 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_hotel"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 8 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_youtube"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 9 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_event"),
                      ))
//                      ->with('rank_data')
//                      ->with(['rank_data' => function ($q) {
////                                 $q->join('users', function ($join) {
////                                                $join->on('of_comments.user_id', 'users.id');
////                                         });
//                                 $q->select(array(
//                                     'fm_users_rank_arrived.*',
//                                     DB::raw("(SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.rank_id = fm_users_rank_arrived.rank_id AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.created_at))as sum_point"),
//                                     DB::raw("(SELECT rank_name FROM fm_rank_limit_user WHERE rank_id = fm_users_rank_arrived.rank_id)as rank_name"),
//                                 ));
//                                 $q->groupBy('rank_type');
//                          }])
                      ->where('user_id', $userId)
                      ->groupBy('fm_users_rank_arrived.dates')
                      ->orderBy('created_at', 'desc')
                      ->get();
              return $this->sendResponse(1, 'Ranking data successfully', $data);
       }

       public function get_my_rank_system_by_user(Request $request) {
              $rule = [
                  'user_id' => 'required',
              ];

              $this->Required_params($request, $rule);
              $userId = $request->input('user_id');

              $data = fm_users_rank_arrived::select(array('created_at', 'rank_id', 'dates',
                          DB::raw("(SELECT rank_name FROM fm_rank_limit_user WHERE rank_id = fm_users_rank_arrived.rank_id)as rank_name"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 1 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_feed"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 2 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_parking"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 3 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_add_spot"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 4 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_likes"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 5 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_restaurant"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 6 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_terrain"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 7 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_hotel"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 8 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_youtube"),
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = $userId AND t1.rank_type = 9 AND DATE(t1.created_at)= DATE(fm_users_rank_arrived.dates)),0)as sum_of_event"),
                      ))
                      ->where('user_id', $userId)
                      ->groupBy('fm_users_rank_arrived.dates')
                      ->orderBy('created_at', 'desc')
                      ->get();
              return $this->sendResponse(1, 'Ranking data successfully', $data);
       }

       public function add_rank_data($userId, $rank_type, $feed_id) {

              $data = fm_users_rank_arrived::where(['user_id' => $userId, 'rank_type' => $rank_type])
                      ->whereDate('created_at', date('Y-m-d'))
                      ->get()
                      ->count();

              $get_point = fm_rank_limit_user::where('rank_type', $rank_type)->first();
              if ($get_point) {
                     $point = $get_point->rank_point;
                     $rank_id = $get_point->rank_id;
              }
              if ($get_point->limit_per_day <= $data) {
                     $tbl_comment = new fm_users_rank_arrived;
                     $tbl_comment->rank_id = $rank_id;
                     $tbl_comment->rank_point = 0;
                     $tbl_comment->rank_type = $rank_type;
                     $tbl_comment->user_id = $userId;
                     $tbl_comment->feed_id = $feed_id;
                     $tbl_comment->dates = date('Y-m-d');
                     $tbl_comment->save();
//                     return $this->sendResponse(1, 'Ranking data successfully', $data);
              } else {
                     $tbl_comment = new fm_users_rank_arrived;
                     $tbl_comment->rank_id = $rank_id;
                     $tbl_comment->rank_point = $point;
                     $tbl_comment->rank_type = $rank_type;
                     $tbl_comment->user_id = $userId;
                     $tbl_comment->feed_id = $feed_id;
                     $tbl_comment->dates = date('Y-m-d');
                     $tbl_comment->save();
              }

//              return $this->sendResponse(1, 'Ranking data successfully', $data);
       }

       public function update_rank_status(Request $request) {

              $data = fm_users_rank_arrived::groupBy('user_id')->get();

              return $this->sendResponse(1, 'Ranking data successfully', $data);
       }

       public function get_unseen_count(Request $request) {

              $userId = Auth::id();
              $data = fm_user_chat_message::where(['message_to' => $userId, 'is_read' => 0])->count('is_read');

              if ($data != 0) {
                     $c = $data;
              } else {
                     $c = 0;
              }
              $response = array('status' => 1, 'message' => 'Unseen count successfully', 'data' => $c);
              echo json_encode($response);
       }

       public function get_unseen_count_user($userId) {

              $data = fm_user_chat_message::where(['message_to' => $userId, 'is_read' => 0])->count('is_read');

              if ($data != 0) {
                     $c = $data;
              } else {
                     $c = 0;
              }
              return $c;
       }

       public function user_block(Request $request) {
              $userId = Auth::id();
              $rule = [
                  'block_to' => 'required',
              ];
              $this->Required_params($request, $rule);

              $feed_id = $request->input('block_to');

              $fm_feed_flag = new fm_user_block;
              $fm_feed_flag->block_to = $feed_id;
              $fm_feed_flag->block_by = $userId;
              $fm_feed_flag->save();

              return $this->sendResponse(1, 'User Blocked successfully', null);
       }

       public function block_post(Request $request) {
              $userId = Auth::id();
              $rule = [
                  'feed_id' => 'required',
              ];
              $this->Required_params($request, $rule);

              $feed_id = $request->input('feed_id');

              $fm_feed_block = new fm_feed_block;
              $fm_feed_block->feed_id = $feed_id;
              $fm_feed_block->user_id = $userId;
              $fm_feed_block->status = 0;
              $fm_feed_block->save();

              $get_data = fm_all_feed::where('feed_id', $feed_id)->first();
              User::where('id', $get_data->added_by)->update(['is_blocked' => 1]);
              return $this->sendResponse(1, 'Post block successfully', null);
       }

       public function _UPDATE_MY_RANKS(Request $request) {

              $data = User::select(array('id',
                          DB::raw("IFNULL((SELECT SUM(t1.rank_point) FROM fm_users_rank_arrived t1 WHERE t1.user_id = users.id),0)as MY_POINT"),
                      ))
                      ->orderBy('MY_POINT', 'DESC')
                      ->get();

              $u_count = User::get()->count();
              $j = 1;
              for ($i = 0; $i < count($data); $i++) {

                     $value = ($data[$i]->MY_POINT == 0) ? $u_count : $j;

                     $array[] = array(
                         'SUM_ALL_POINT' => $data[$i]->MY_POINT,
                         'id' => $data[$i]->id,
                         'AUTO' => $value,
                     );
                     if ($i > 0) {
                            $VALUE_ELSE = ($data[$i]->MY_POINT == 0) ? $u_count : $j - 1;

                            if ($data[$i - 1]->MY_POINT == $data[$i]->MY_POINT) {
                                   $array[$i]['AUTO'] = $VALUE_ELSE;
                            } else if ($data[$i - 1]->MY_POINT > $data[$i]->MY_POINT) {
                                   $j++;
                            }
                     } else {

                            $j++;
                     }
              }
              for ($k = 0; $k < count($array); $k++) {

                     $u_count = User::where('id', $array[$k]['id'])->update(['my_rank' => $array[$k]['AUTO']]);
              }
//              echo count($array);
              echo 'DONE BROO';
              exit;
       }

       public function get_fav_feeds(Request $request) {
              $rule = [
                  'feed_id' => 'required',
              ];
              $this->Required_params($request, $rule);

              $feed_id = $request->input('feed_id');

              $data = Mdl_feed_favrioute::select(array('fm_feed_favrioute.*', 'users.user_name', 'users.profile_pic'))
                      ->where('feed_id', $feed_id)
                      ->join('users', 'fm_feed_favrioute.user_id', '=', 'users.id')
                      ->get();

              return $this->sendResponse(1, 'Favourite Feed List successfully', $data);
       }

       public function report_action(Request $request) {
              $rule = [
//                  'report_to' => 'required',
                  'report_on' => 'required',
              ];
              $this->Required_params($request, $rule);

              $userId = Auth::id();

              $report_to = $request->input('report_to');
              if (empty($report_to)) {
                     $report_to = 0;
              }
              $comment_id = $request->input('comment_id');
              if (empty($comment_id)) {
                     $comment_id = 0;
              }
              $report_on = $request->input('report_on'); //1-feed , 2-user

              $fm_report = new fm_report;
              $fm_report->report_to = $report_to;
              $fm_report->report_by = $userId;
              $fm_report->report_on = $report_on;
              $fm_report->comment_id = $comment_id;
              $fm_report->report_status = 1;
              $fm_report->save();

              return $this->sendResponse(1, 'Report added successfully', $fm_report);
       }

}
