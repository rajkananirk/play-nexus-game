<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\User;
use App\Models\of_content;
use App\Models\of_top_trending;
use App\Models\of_content_trending;
use App\Models\of_coming_soon_streaming;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class ComingSoonController extends BaseController {

       public function GetContent(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $filter_type = $request->filter_type;

                     if ($filter_type == 1) {
                            $p_type = $request->p_type;
                            $cat_id = $request->cat_id;
//                            print_r($cat_id);
//                            exit;
                            if ($p_type == 1) {
                                   $whereis = 'is_new = 1';
                                   $type = 1;
                            } else if ($p_type == 2) {
                                   $whereis = 'is_popular = 1';
                                   $type = 2;
                            } else {
                                   $type = 0;
                                   $whereis = '1=1';
                            }
                            if ($cat_id) {
                                   $cat_id = $cat_id;
                                   $cateis = "content_category = $cat_id";
                            } else {
                                   $cat_id = $cat_id;
                                   $cateis = '1=1';
                            }
                     } else {
                            $whereis = '1=1';
                            $cateis = '1=1';
                            $type = 0;
                            $cat_id = 0;
                     }


                     $data = of_content::select(array('*',
                                 DB::raw("IFNULL((SELECT t1.title from of_top_trending t1 where t1.trending_id = of_content.content_category),'N/A')as category_title")))
                             ->whereraw($whereis)
                             ->whereraw($cateis)
//                             ->orderBy('content_order', 'desc')
                             ->get();


                     $of_top_trending = of_top_trending::get();

                     return view("Admin/Content/Content")->with(compact('data', 'of_top_trending', 'type', 'cat_id'));
              } else {
                     return Redirect::to('/');
              }
       }

       public function GetCommingSoonStreaming(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = of_coming_soon_streaming::select(array('*', Db::raw("(SELECT t1.title  FROM of_top_trending t1 WHERE t1.trending_id = of_coming_soon_streaming.category_id)as title")))
                             ->where('type', 1)
                             ->get();
                     $of_top_trending = of_top_trending::get();

                     $type = 1;
                     return view("Admin/CommingSoonStreaming/Content")->with(compact('data', 'of_top_trending', 'type'));
              } else {
                     return Redirect::to('/');
              }
       }

       public function GetArrivedSoonStreaming(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = of_coming_soon_streaming::select(array('*',
                                 DB::raw("(SELECT t1.title  FROM of_top_trending t1 WHERE t1.trending_id = of_coming_soon_streaming.category_id)as title")))
                             ->where('type', 2)
                             ->get();
                     $of_top_trending = of_top_trending::get();

                     $type = 2;

                     return view("Admin/CommingSoonStreaming/Content")->with(compact('data', 'of_top_trending', 'type'));
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteContent(Request $request) {
              if (Session::get('login')) {

                     $content_id = $request->content_id;

                     of_content::where('content_id', $content_id)->delete();
                     return redirect()->back()->with('success', "Content Deleted Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function AddCommingSoonStreaming(Request $request) {
              if (Session::get('login')) {
                     $youtube_key = $request->youtube_key;
                     $cover_photo = $request->cover_photo;
                     $content_category = $request->content_category;
                     $content_title = $request->content_title;
                     $type = $request->type;
                     if ($request->hasFile('cover_photo')) {

                            $imageName = time() . '.' . request()->cover_photo->getClientOriginalExtension();

                            request()->cover_photo->move(public_path('uploads'), $imageName);
                            $product_image = 'public/uploads/' . $imageName;
                            $cover_photo = $product_image;
                     } else {
                            $cover_photo = '';
                     }

                     $of_content = new of_coming_soon_streaming();
                     $of_content->cover_photo = $cover_photo;
                     $of_content->youtube_key = $youtube_key;
                     $of_content->category_id = $content_category;
                     $of_content->content_title = $content_title;
                     $of_content->cover_photo = $cover_photo;
                     $of_content->type = $type;
                     $of_content->save();

                     return redirect()->back()->with('success', "Coming soon Streaming Added Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function UpdateCommingSoonStreaming(Request $request) {
              if (Session::get('login')) {

                     $update_data = [];
                     $id = $request->id;
                     $youtube_key = $request->youtube_key;
                     $cover_photo = $request->cover_photo;
                     $content_category = $request->content_category;
                     $content_title = $request->content_title;
                     if ($request->hasFile('cover_photo')) {

                            $imageName = time() . '.' . request()->cover_photo->getClientOriginalExtension();

                            request()->cover_photo->move(public_path('uploads'), $imageName);
                            $cover_photo = 'public/uploads/' . $imageName;
                            $update_data['cover_photo'] = $cover_photo;
                     }

                     if ($content_title) {
                            $update_data['content_title'] = $content_title;
                     }
                     if ($youtube_key) {
                            $update_data['youtube_key'] = $youtube_key;
                     }
                     if ($content_category) {
                            $update_data['category_id'] = $content_category;
                     }
                     if ($cover_photo) {
                            $update_data['cover_photo'] = $cover_photo;
                     }

                     if (!empty($update_data)) {
                            of_coming_soon_streaming::where('id', $id)->update($update_data);
                     }

                     return redirect()->back()->with('success', "Coming soon Streaming Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       function GetTopTrendingContent(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $filter_type = $request->filter_type;

                     if ($filter_type == 1) {
                            $trending_id = $request->trending_id;
                            $country_name = $request->country_name;

                            if ($trending_id) {

                                   $trending = $trending_id;
                                   $trending_id1 = "content_category = $trending_id";
                            } else {
                                   $trending = $trending_id;
                                   $trending_id1 = '1=1';
                            }
                            if ($country_name) {
                                   $c_name = $country_name;
                                   $cateis = "content_country = '$country_name'";
                            } else {
                                   $c_name = $country_name;
                                   $cateis = '1=1';
                            }
                     } else {
                            $c_name = '';
                            $trending = 0;
                            $trending_id1 = '1=1';
                            $cateis = '1=1';
                     }


                     $data = of_content_trending::select(array('*', 'of_content_trending.content_id as c_id', 'of_content_trending.content_order as t_content_order',
                                 DB::raw("IFNULL((SELECT t1.title from of_top_trending t1 where t1.trending_id = of_content.content_category),'N/A')as category_title")))
                             ->where('is_custom', 0)
                             ->whereraw($trending_id1)
                             ->whereraw($cateis)
                             ->join('of_content', function ($join) {
                                    $join->on('of_content_trending.content_third_party_id', 'of_content.content_third_party_id');
                             })
                             ->orderBy('of_content_trending.content_order', 'ASC')
                             ->get();

                     $country = User::select('country')->whereNotNull('country')->groupBy('country')->get();

                     $of_top_trending = of_top_trending::get();

                     return view("Admin/Content/TrendingContent")->with(compact('data', 'of_top_trending', 'c_name', 'country', 'trending'));
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteCommingSoonStreaming(Request $request) {
              if (Session::get('login')) {

                     $content_id = $request->id;

                     of_coming_soon_streaming::where('id', $content_id)->delete();
                     return redirect()->back()->with('block', "Coming soon Streaming Deleted Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function GetTrendingContentDetails(Request $request, $content_id) {
              if (!empty($request->session()->has('login'))) {

                     $data = of_content_trending::where('content_id', $content_id)->first();
                     $of_top_trending = of_top_trending::get();

                     return view("Admin/Content/TrendingContentDetails")->with(compact('data', 'of_top_trending'));
              } else {
                     return Redirect::to('/');
              }
       }

}
