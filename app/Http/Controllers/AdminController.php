<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Mdl_feed;
use App\Models\Mdl_event;
use App\Models\Mdl_category;
use App\Models\Mdl_feed_youtube;
use Illuminate\Http\Request;
use Hash;
use Validator;
use Session;
use DB;
use App\Http\Controllers\BaseController;

class AdminController extends BaseController {

       public function loginview(Request $request) {
              return view('login');
       }

       public function feedview(Request $request) {
              $feed = Mdl_feed::select(array('fm_feed.*',
                          DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_feed.feed_category_id)as category_name"),
                          DB::raw("(SELECT user_name from users where users.id = fm_feed.feed_by)as user_name")
                      ))->get();

              $category = Mdl_category::select(array('fm_category.category_name'))->where('category_type', 1)->get();

              return view('feed')->with(compact('feed', 'category'));
       }

       public function uncategoryview(Request $request) {
              $category = Mdl_category::select(array('fm_category.category_type',
                          DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_feed.feed_category_id)as category_name")))->where('category_type', 1)->get();
              return view('category')->with(compact('category'));
       }

       public function eventview(Request $request) {
              $event = Mdl_event::select(array('event_id', 'event_location', 'event_title', 'event_register_link', 'event_date', 'event_about', 'event_by', 'event_category_id',
                          DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_event.event_category_id)as category_name"),
                          DB::raw("(SELECT user_name from users where users.id = fm_event.event_by)as user_name")
                      ))->get();
              $category = Mdl_category::select(array('fm_category.category_name'))->where('category_type', 1)->get();
              return view('event')->with(compact('event', 'category'));
       }

       public function youtubeview(Request $request) {
              $youtube = Mdl_feed_youtube::select(array('youtube_id', 'youtube_location', 'youtube_about', 'youtube_url', 'category_id', 'user_id', 'feed_id',
                          DB::raw("(SELECT category_name from fm_category where fm_category.category_id = fm_feed_youtube.category_id)as category_name"),
                          DB::raw("(SELECT user_name from users where users.id = fm_feed_youtube.user_id)as user_name")
                      ))->get();
              $category = Mdl_category::select(array('fm_category.category_name'))->where('category_type', 1)->get();
              return view('youtube')->with(compact('youtube', 'category'));
       }

       public function categoryview(Request $request) {
              $category = Mdl_category::get();
              return view('category')->with(compact('category'));
       }

       public function dashboardview(Request $request) {
              $feed = Mdl_feed::get();
              $event = Mdl_event::get();
              $youtube = Mdl_feed_youtube::get();
              $category = Mdl_category::get();
              return view('dashboard')->with(compact('feed', 'event', 'youtube', 'category'));
       }

       public function destroy(Request $request) {
              $feed_id = $request->feed_id;
              $feed = Mdl_feed::where('feed_id', $feed_id)->delete();
              return redirect()->back()->with('status', 'Feed Deleted Successfully', $feed);
       }

       public function eventdelete(Request $request) {
              $event_id = $request->event_id;
              $event = Mdl_event::where('event_id', $event_id)->delete();
              return redirect()->back()->with('status', 'Event Deleted Successfully', $event);
       }

       public function categorydelete(Request $request) {
              $category_id = $request->category_id;
              $category = Mdl_category::where('category_id', $category_id)->delete();
              return redirect()->back()->with('status', 'Category Deleted Successfully', $category);
       }

       public function youtubedelete(Request $request) {
              $youtube_id = $request->youtube_id;
              $youtube = Mdl_feed_youtube::where('youtube_id', $youtube_id)->delete();
              return redirect()->back()->with('status', 'Youtube Deleted Successfully', $youtube);
       }

       public function updatefeed(Request $request) {

              $feed_id = $request->feed_id;
              $feed_location = $request->feed_location;
              $hotel_name = $request->hotel_name;
              $is_feed_spot = $request->is_feed_spot;
              $feed_price = $request->feed_price;
              $feed_title = $request->feed_title;
              $feed_category_id = $request->feed_category_id;

              if ($feed_location) {
                     $update['feed_location'] = $feed_location;
              }
              if ($is_feed_spot) {
                     $update['is_feed_spot'] = $is_feed_spot;
              }
              if ($hotel_name) {
                     $update['hotel_name'] = $hotel_name;
              }
              if ($feed_price) {
                     $update['feed_price'] = $feed_price;
              }
              if ($feed_title) {
                     $update['feed_title'] = $feed_title;
              }
              if ($feed_category_id) {
                     $update['feed_category_id'] = $feed_category_id;
              }
              if ($update) {
                     Mdl_feed::where('feed_id', $feed_id)->update($update);
              }
              return redirect()->back()->with('status', 'Feed Updated Successfully');
       }

       public function updateevent(Request $request) {
              $event_id = $request->event_id;
              $event_location = $request->event_location;
              $event_title = $request->event_title;
              $event_register_link = $request->event_register_link;
              $event_date = $request->event_date;
              $event_about = $request->event_about;
              $event_category_id = $request->event_category_id;

              if ($event_location) {
                     $update['event_location'] = $event_location;
              }
              if ($event_title) {
                     $update['event_title'] = $event_title;
              }
              if ($event_register_link) {
                     $update['event_register_link'] = $event_register_link;
              }
              if ($event_date) {
                     $update['event_date'] = $event_date;
              }
              if ($event_about) {
                     $update['event_about'] = $event_about;
              }
              if ($event_category_id) {
                     $update['event_category_id'] = $event_category_id;
              }
              if ($update) {
                     Mdl_event::where('event_id', $event_id)->update($update);
              }
              return redirect()->back()->with('status', 'Event Updated Successfully');
       }

       public function updateyoutube(Request $request) {
              $youtube_id = $request->youtube_id;
              $youtube_location = $request->youtube_location;
              $youtube_about = $request->youtube_about;
              $youtube_url = $request->youtube_url;
              $category_id = $request->category_id;
              if ($youtube_location) {
                     $update['youtube_location'] = $youtube_location;
              }
              if ($youtube_about) {
                     $update['youtube_about'] = $youtube_about;
              }
              if ($youtube_url) {
                     $update['youtube_url'] = $youtube_url;
              }
              if ($category_id) {
                     $update['category_id'] = $category_id;
              }
              if ($update) {
                     Mdl_feed_youtube::where('youtube_id', $youtube_id)->update($update);
              }
              return redirect()->back()->with('status', 'Youtube Updated Successfully');
       }

       public function updatecategory(Request $request) {

              $category_id = $request->category_id;
              $category_name = $request->category_name;
              $category_type = $request->category_type;
              if ($category_name) {
                     $update['category_name'] = $category_name;
              }
              if ($request->hasFile('category_image')) {

                     $imageName = time() . '.' . request()->category_image->getClientOriginalExtension();

                     request()->category_image->move(public_path('uploads'), $imageName);
                     $update['category_image'] = 'public/uploads/' . $imageName;
              }
              if ($category_type) {
                     $update['category_type'] = $category_type;
              }
              if ($update) {
                     Mdl_category::where('category_id', $category_id)->update($update);
              }

              return redirect()->back()->with('status', 'Category Updated Successfully');
       }

       public function addcategory(Request $request) {
              $category_name = $request->category_name;
              $category_type = $request->category_type;
              $category_image = $request->category_image;

              $User = new Mdl_category;
              $User->category_name = $category_name;
              $User->category_type = $category_type;
              if ($request->hasFile('category_image')) {

                     $imageName = time() . '.' . request()->category_image->getClientOriginalExtension();

                     request()->category_image->move(public_path('/uploads'), $imageName);
                     $category_image = 'public/uploads/' . $imageName;
              }
              $User->category_image = $category_image;
              $User->save();

              return redirect()->back()->with('status', 'Category Added Successfully', $User);
       }

}
