<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Mdl_category;
use App\Models\fm_all_feed;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class FeedController extends BaseController {

       function GetFeed(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = fm_all_feed::select(array('fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                                 DB::raw("(select COUNT(id) from fm_feed_favrioute t5 where t5.feed_id = fm_all_feed.feed_id)as total_likes"),
                                 DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                                 DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_name"),))->where('feed_type', 1)
                             ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                             ->get();
                     $category = Mdl_category::where('category_type', 1)->get();

                     return view("Admin/Feed/Index")->with(compact('data', 'category'));
              } else {
                     return Redirect::to('/');
              }
       }

       function GetYoutube(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = fm_all_feed::select(array('fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                                 DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                                 DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_name"),))
                             ->where('feed_type', 2)
                             ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                             ->get();
                     $category = Mdl_category::where('category_type', 3)->get();

                     return view("Admin/Youtube/Index")->with(compact('data', 'category'));
              } else {
                     return Redirect::to('/');
              }
       }

       function GetEvent(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = fm_all_feed::select(array('fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                                 DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                                 DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_name"),))
                             ->where('feed_type', 3)
                             ->join('users', 'fm_all_feed.added_by', '=', 'users.id')
                             ->get();

                     $category = Mdl_category::where('category_type', 2)->get();

                     return view("Admin/Event/Index")->with(compact('data', 'category'));
              } else {
                     return Redirect::to('/');
              }
       }

       function GetTopEvent(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = fm_all_feed::select(array('fm_all_feed.*', 'users.user_name', 'users.profile_pic', 'users.address',
                                 DB::raw("(SELECT feed_image from fm_feed_image where fm_feed_image.feed_id = fm_all_feed.feed_id limit 1)as feed_image"),
                                 DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_all_feed.category_id limit 1)as category_name"),))
                             ->where('feed_type', 3)
                             ->where('is_top_event', 2)
                             ->leftjoin('users', 'fm_all_feed.added_by', '=', 'users.id')
                             ->get();

                     $category = Mdl_category::where('category_type', 2)->get();

                     return view("Admin/Event/TopIndex")->with(compact('data', 'category'));
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

       function AddTopEvent(Request $request) {
              if (!empty($request->session()->has('login'))) {


                     $of_activity = new fm_all_feed();
                     $of_activity->title = $request->title;
                     $of_activity->about = $request->about;
                     $of_activity->category_id = $request->category_id;
                     $of_activity->location = $request->location;
                     $of_activity->hotel_name = $request->hotel_name;
                     $of_activity->hotel_price = $request->hotel_price;
                     $of_activity->restaurant_name = $request->restaurant_name;
                     $of_activity->event_prefecture = $request->event_prefecture;
                     $of_activity->is_top_event = 2;
                     $of_activity->feed_type = 3;
                     $of_activity->added_by = Session::get('admin_id');
                     $of_activity->event_member = $request->event_member;
                     $of_activity->event_register_link = $request->event_register_link;
                     if ($request->hasFile('event_image')) {

                            $imageName = time() . '.' . request()->event_image->getClientOriginalExtension();

                            request()->event_image->move(public_path('uploads'), $imageName);
                            $product_image = 'public/uploads/' . $imageName;
                            $cover_photo = $product_image;
                     } else {
                            $cover_photo = '';
                     }
                     $of_activity->event_image = $cover_photo;
                     $of_activity->event_date = $request->event_date;
                     $of_activity->save();


                     return redirect()->back()->with('success', "Feed Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       function UpdateFeed(Request $request) {
              if (Session::get('login')) {

                     $update_data = [];
                     $feed_id = $request->feed_id;
                     $title = $request->title;

                     $about = $request->about;
                     $content_value = $request->is_spot;
                     $category_id = $request->category_id;
                     $location = $request->location;
                     $hotel_name = $request->hotel_name;
                     $hotel_price = $request->hotel_price;
                     $restaurant_name = $request->restaurant_name;
                     $event_prefecture = $request->event_prefecture;
                     $event_member = $request->event_member;
                     $event_register_link = $request->event_register_link;
                     $event_date = $request->event_date;
                     $youtube_url = $request->youtube_url;

                     if ($event_prefecture) {
                            $update_data['event_prefecture'] = $event_prefecture;
                     }
                     if ($event_member) {
                            $update_data['event_member'] = $event_member;
                     }
                     if ($event_date) {
                            $update_data['event_date'] = $event_date;
                     }
                     if ($youtube_url) {
                            $update_data['youtube_url'] = $youtube_url;
                     }
                     if ($event_register_link) {
                            $update_data['event_register_link'] = $event_register_link;
                     }

                     if ($title) {
                            $update_data['title'] = $title;
                     }
                     if ($about) {
                            $update_data['about'] = $about;
                     }
                     if ($content_value) {
                            $update_data['is_feed_spot'] = $content_value;
                     }
                     if ($category_id) {
                            $update_data['category_id'] = $category_id;
                     }
                     if ($location) {
                            $update_data['location'] = $category_id;
                     }
                     if ($hotel_name) {
                            $update_data['hotel_name'] = $hotel_name;
                     }
                     if ($hotel_price) {
                            $update_data['hotel_price'] = $hotel_price;
                     }
                     if ($restaurant_name) {
                            $update_data['restaurant_name'] = $restaurant_name;
                     }


                     if (!empty($update_data)) {
                            fm_all_feed::where('feed_id', $feed_id)->update($update_data);
                     }

                     return redirect()->back()->with('success', "Feed Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       function DeleteFeed(Request $request) {
              if (Session::get('login')) {

                     $trending_id = $request->feed_id;

                     fm_all_feed::where('feed_id', $trending_id)->delete();
                     \App\Models\Mdl_feed_image::where('feed_id', $trending_id)->delete();
                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       function AddCategory(Request $request) {
              if (Session::get('login')) {

                     if ($request->hasFile('category_image')) {

                            $imageName = time() . '.' . request()->category_image->getClientOriginalExtension();

                            request()->category_image->move(public_path('uploads'), $imageName);
                            $product_image = 'public/uploads/' . $imageName;
                            $cover_photo = $product_image;
                     } else {
                            $cover_photo = '';
                     }
                     $title = $request->category_name;
                     $of_activity = new Mdl_category();
                     $of_activity->category_name = $title;
                     $of_activity->category_image = $cover_photo;
                     $of_activity->save();
                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       function DeleteUsersProfile(Request $request) {
              if (Session::get('login')) {

                     $user_id = $request->user_id;

                     User::where('id', $user_id)->delete();


                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       function BUUsersProfile(Request $request) {
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
