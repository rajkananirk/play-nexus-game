<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\User;
use App\Models\of_content;
use App\Models\of_top_trending;
use App\Models\of_activity;
use App\Models\of_content_trending;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class ContentController extends BaseController {

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

       public function GetContentDetails(Request $request, $content_id) {
              if (!empty($request->session()->has('login'))) {

                     $data = of_content::where('content_id', $content_id)->first();
                     $of_top_trending = of_top_trending::get();

                     return view("Admin/Content/ContentDetails")->with(compact('data', 'of_top_trending'));
              } else {
                     return Redirect::to('/');
              }
       }

       public function UpdateContent(Request $request) {
              if (Session::get('login')) {

                     $update_data = [];
                     $content_id = $request->content_id;
                     $content_third_party_id = $request->content_third_party_id;
                     $watch_type = $request->content_type;
                     $content_value = $request->content_value;

                     if ($content_value) {
                            $content_val = $request->content_value;
                     } else {
                            $content_val = 1;
                     }

                     $content_order = $request->content_order;
                     $is_arrived_this_week = $request->is_arrived_this_week;
                     $production_type = $request->production_type;
                     $content_type_edited = $request->content_type_edited;
                     $update_data['content_type'] = $content_type_edited;

                     $content_title = $request->content_title;
                     $content_category = $request->content_category;
                     $trailer_url = $request->trailer_url;

                     //CONTENT DETAILS API CALLING THIRDPARTY

                     if ($content_type_edited == 0) {
                            $url1 = "https://api.themoviedb.org/3/movie/$content_third_party_id?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32&language=en-US";
                     } else {
                            $url1 = "https://api.themoviedb.org/3/tv/$content_third_party_id?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32&language=en-US";
                     }
                     $curl = curl_init($url1);
                     curl_setopt($curl, CURLOPT_URL, $url1);
                     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                     $resp11 = curl_exec($curl);
                     curl_close($curl);
                     $data = $resp11;
                     $watch = json_decode($data);

                     $backdrop_path1 = $watch->backdrop_path;
                     if ($backdrop_path1) {
                            $backdrop_path = $watch->backdrop_path;
                     }
                     $poster_path1 = $watch->poster_path;
                     if ($poster_path1) {
                            $poster_path = $watch->poster_path;
                     }
                     if ($content_type_edited == 0) {
                            $runtime = $watch->runtime;
                     } else {
                            $runtime = $watch->episode_run_time[0];
                     }
                     if ($content_type_edited == 0) {
                            $overview = $watch->overview;
                     } else {
                            $overview = $watch->overview;
                     }
                     if ($content_type_edited == 0) {
                            $release_date = $watch->release_date;
                     } else {
                            $release_date = $watch->first_air_date;
                     }

                     $vote_average1 = $watch->vote_average;
                     if ($vote_average1) {
                            $vote_average = $watch->vote_average;
                     } else {
                            $vote_average = 0;
                     }
                     $geners = $watch->genres;
                     foreach ($geners as $values) {
                            $genres1 = $values->name . ', ';
                     }

                     if ($backdrop_path) {
                            $update_data['content_cover_photo'] = $backdrop_path;
                     }
                     if ($content_title) {
                            $update_data['content_title'] = $content_title;
                     }
                     if ($content_type_edited) {
//                            print_r($content_type_edited);
//                            exit;
                            $update_data['content_type'] = $content_type_edited;
                     }
                     if ($overview) {
                            $update_data['content_discription'] = $overview;
                     }
                     if ($is_arrived_this_week) {
                            $update_data['is_arrived_this_week'] = $is_arrived_this_week;
                     }
                     if ($content_val) {
                            $update_data['content_value'] = $content_val;
                     }
                     if ($genres1) {
                            $update_data['content_tags'] = $genres1;
                     }

                     if ($release_date) {
                            $update_data['content_release_time'] = $release_date;
                     }
                     if ($poster_path) {
                            $update_data['content_photo'] = $poster_path;
                     }

                     if ($content_category) {
                            $update_data['content_category'] = $content_category;
                     }

                     if ($content_order) {
                            $update_data['content_order'] = $content_order;
                     }
                     if ($runtime) {
                            $update_data['content_length'] = $runtime;
                     }
                     if ($vote_average) {
                            $update_data['vote_average'] = $vote_average;
                     }
                     if ($trailer_url) {
                            $update_data['trailer_url'] = $trailer_url;
                     }

                     if ($production_type == 1) {
                            $update_data['is_popular'] = 0;
                            $update_data['is_new'] = 1;
                     } else {
                            $update_data['is_popular'] = 1;
                            $update_data['is_new'] = 0;
                     }

                     if (!empty($update_data)) {
                            of_content::where('content_id', $content_id)->update($update_data);
                     }

                     return redirect()->back()->with('success', "Content Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteContent(Request $request) {
              if (Session::get('login')) {

                     $content_id = $request->content_id;
                     $content_third_party_id = $request->content_third_party_id;
                     of_content::where('content_third_party_id', $content_third_party_id)->delete();
                     of_content_trending::where('content_third_party_id', $content_third_party_id)->delete();
//                     of_activity::where('content_third_party_id', $content_third_party_id)->delete();
//                     of_notification::where('content_third_party_id', $content_third_party_id)->delete();
//                     of_comments::where('content_thirdparty_id', $content_third_party_id)->delete();

                     return redirect()->back()->with('success', "Content Deleted Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function AddContent(Request $request) {
              if (Session::get('login')) {
                     $content_value = $request->content_value;
                     $watch_type = $request->content_type;
                     $production_type = $request->production_type;
                     $content_third_party_id = $request->content_third_party_id;
                     $is_arrived_this_week = $request->is_arrived_this_week;

                     if ($content_value) {
                            $content_val = $request->content_value;
                     } else {
                            $content_val = 1;
                     }

                     $adminid = Session::get('adminid');

                     $check = of_content::where('content_third_party_id', $content_third_party_id)->first();
                     if ($check) {
                            return redirect()->back()->with('block', "Content thirdparty id already exits. please enter a diffrent thirdparty id..");
                     }


                     if ($production_type == 1) {
                            $is_popular = 0;
                            $is_new = 1;
                     } else {
                            $is_popular = 1;
                            $is_new = 0;
                     }
//
                     if ($watch_type == 0) { //movies
                            $url = "https://api.watchmode.com/v1/search/?apiKey=MV4NSOqZUIcRaOwtale82abpuZ2jrHTS3kzzLmqH&search_field=tmdb_movie_id&search_value=$content_third_party_id";
                     } else { //tv
                            $url = "https://api.watchmode.com/v1/search/?apiKey=MV4NSOqZUIcRaOwtale82abpuZ2jrHTS3kzzLmqH&search_field=tmdb_tv_id&search_value=$content_third_party_id";
                     }
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, $url);
                     curl_setopt($ch, CURLOPT_POST, false);
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                     $resp = curl_exec($ch);
                     curl_close($ch);
                     $response = json_decode($resp);

                     $raj = $response->title_results;
                     if (empty($raj)) {
                            $watchmode_id = 0;
                     } else {
                            $watchmode_id = $response->title_results[0]->id;
                     }
                     $GetWatchMode = "https://api.watchmode.com/v1/title/$watchmode_id/sources/?apiKey=MV4NSOqZUIcRaOwtale82abpuZ2jrHTS3kzzLmqH";
                     $curl1 = curl_init($GetWatchMode);
                     curl_setopt($curl1, CURLOPT_URL, $GetWatchMode);
                     curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl1, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);

                     $resp1 = curl_exec($curl1);
                     curl_close($curl1);
                     $watchModeJson = $resp1;
//                     $watchModeJson = json_decode($watchModeJson1);
//                     for ($i = 0; $i < count($watchModeJson) - 1; $i++) {
//                            if ($watchModeJson[$i]->region == 'US') {
//                                   if ($watchModeJson[$i]->source_id == 203 ||
//                                           $watchModeJson[$i]->source_id == 26 ||
//                                           $watchModeJson[$i]->source_id == 372 ||
//                                           $watchModeJson[$i]->source_id == 157 ||
//                                           $watchModeJson[$i]->source_id == 387 ||
//                                           $watchModeJson[$i]->source_id == 444 ||
//                                           $watchModeJson[$i]->source_id == 388 ||
//                                           $watchModeJson[$i]->source_id == 371 ||
//                                           $watchModeJson[$i]->source_id == 445 ||
//                                           $watchModeJson[$i]->source_id == 445 ||
//                                           $watchModeJson[$i]->source_id == 248 ||
//                                           $watchModeJson[$i]->source_id == 232 ||
//                                           $watchModeJson[$i]->source_id == 296 ||
//                                           $watchModeJson[$i]->source_id == 391) {
//                                          $filter_watch[] = $watchModeJson[$i];
//                                   }
//                            }
//                     }
                     if (empty($filter_watch)) {
                            $filter_watch1 = '[]';
                     } else {
                            $filter_watch1 = json_encode($filter_watch);
                     }
                     //End
//                     //CONTENT DETAILS API CALLING THIRDPARTY
//
                     if ($watch_type == 0) {
                            $url1 = "https://api.themoviedb.org/3/movie/$content_third_party_id?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32&language=en-US";
                     } else {
                            $url1 = "https://api.themoviedb.org/3/tv/$content_third_party_id?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32&language=en-US";
                     }
                     $curl = curl_init($url1);
                     curl_setopt($curl, CURLOPT_URL, $url1);
                     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                     $resp11 = curl_exec($curl);
                     curl_close($curl);
                     $data = $resp11;
                     $watch = json_decode($data);
////

                     if (!empty($watch->status_code)) {
                            return redirect()->back()->with('block', "Invalid thirdparty id please check type or id..");
                     }

                     $backdrop_path1 = $watch->backdrop_path;
                     if ($backdrop_path1) {
                            $backdrop_path = $watch->backdrop_path;
                     } else {
                            $backdrop_path = '';
                     }
                     $poster_path1 = $watch->poster_path;
                     if ($poster_path1) {
                            $poster_path = $watch->poster_path;
                     } else {
                            $poster_path = '';
                     }
                     if ($watch_type == 0) {
                            $runtime = $watch->runtime;
                     } else {
                            $runtime = $watch->episode_run_time[0];
                     }
                     if ($watch_type == 0) {
                            $overview = $watch->overview;
                     } else {
                            $overview = $watch->overview;
                     }
                     if ($watch_type == 0) {
                            $release_date = $watch->release_date;
                     } else {
                            $release_date = $watch->first_air_date;
                     }
                     $vote_average1 = $watch->vote_average;
                     if ($vote_average1) {
                            $vote_average = $watch->vote_average;
                     } else {
                            $vote_average = 0;
                     }


                     $geners = $watch->genres;
                     foreach ($geners as $values) {
                            $genres1 = $values->name . ', ';
                     }

                     if ($content_value) {
                            $content_val = $request->content_value;
                     } else {
                            $content_val = 0;
                     }

                     $is_arrived_this_week = $request->is_arrived_this_week;
                     if ($content_value) {
                            $is_arrived_this_week = $request->is_arrived_this_week;
                     } else {
                            $is_arrived_this_week = 0;
                     }

                     //Start Trailer Url
                     if ($watch_type == 0) {
                            $url1 = "https://api.themoviedb.org/3/movie/$content_third_party_id/videos?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32";
                     } else {
                            $url1 = "https://api.themoviedb.org/3/tv/$content_third_party_id/videos?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32";
                     }

                     $curlTrailer = curl_init($url1);
                     curl_setopt($curlTrailer, CURLOPT_URL, $url1);
                     curl_setopt($curlTrailer, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curlTrailer, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curlTrailer, CURLOPT_SSL_VERIFYPEER, false);

                     $respcurlTrailer = curl_exec($curlTrailer);
                     curl_close($curlTrailer);
                     $Trailerdata = $respcurlTrailer;
                     $T_data = json_decode($Trailerdata);

                     if (empty($T_data->results)) {
                            $trailerKey = '';
                     } else {

                            for ($i = 0; $i < count($T_data->results); $i++) {
                                   $t_type = $T_data->results[$i]->type;
                                   $t_official = $T_data->results[$i]->official;
                                   $t_key = $T_data->results[$i]->key;

                                   if (($t_type == 'Trailer' && ($t_official == true || $t_official == 'true'))) {
                                          $trailerKey = $t_key;
                                   } else if ($t_type == 'Trailer' && $t_type == null) {
                                          $trailerKey = $t_key;
                                   } else {
                                          $trailerKey = '';
                                   }
                            }
                     }

                     //End Trailer Url



                     $of_content = new of_content();
                     $of_content->content_third_party_id = $request->content_third_party_id;
                     $of_content->content_cover_photo = $backdrop_path;
                     $of_content->content_title = $request->content_title;
                     $of_content->content_type = $request->content_type; //	0=moview, 1v=tv
                     $of_content->is_arrived_this_week = $is_arrived_this_week; //0-false, 1-true
                     $of_content->content_added_by = $adminid;
                     $of_content->content_discription = $overview;
                     $of_content->content_trailer_time = $release_date;
                     $of_content->content_release_time = $release_date;
                     $of_content->content_photo = $poster_path;
                     $of_content->content_tags = $genres1;
                     $of_content->content_category = $request->content_category;
                     $of_content->content_value = $content_val;
                     $of_content->content_length = $runtime;
                     $of_content->vote_average = $vote_average;
                     $of_content->trailer_url = $trailerKey;
                     $of_content->content_order = $request->content_order;
                     $of_content->is_popular = $is_popular;
                     $of_content->content_watchmode_id = $watchmode_id;
                     $of_content->watch_mode_json = $watchModeJson;
                     $of_content->is_new = $is_new;
                     $of_content->content_order = 0;
                     $of_content->save();

                     return redirect()->back()->with('success', "Content Added Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       //Top Trending Content
       public function AddTrendingContent(Request $request) {
              if (Session::get('login')) {
                     $content_third_party_id = $request->content_third_party_id;
                     $adminid = Session::get('adminid');

                     $check = of_content::where('content_third_party_id', $content_third_party_id)->first();
                     if ($check) {
                            $check_trend = of_content_trending::where('is_custom', 0)->where('content_third_party_id', $content_third_party_id)->first();

                            if ($check_trend) {
                                   return redirect()->back()->with('block', "Content Thirdparty Id Already Exits. Please Enter A Diffrent ThirdParty ID..");
                            } else {
                                   $of_content_trending = new of_content_trending();
                                   $of_content_trending->content_third_party_id = $request->content_third_party_id;
                                   $of_content_trending->content_order = $request->content_order;
                                   $of_content_trending->is_custom = 0;
                                   $of_content_trending->save();
                                   return redirect()->back()->with('success', "Trending Content Added Successfully");
                            }
                     }

                     $watch_type = $request->content_type;

                     if ($watch_type == 0) { //movies
                            $url = "https://api.watchmode.com/v1/search/?apiKey=MV4NSOqZUIcRaOwtale82abpuZ2jrHTS3kzzLmqH&search_field=tmdb_movie_id&search_value=$content_third_party_id";
                     } else { //tv
                            $url = "https://api.watchmode.com/v1/search/?apiKey=MV4NSOqZUIcRaOwtale82abpuZ2jrHTS3kzzLmqH&search_field=tmdb_tv_id&search_value=$content_third_party_id";
                     }
                     $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, $url);
                     curl_setopt($ch, CURLOPT_POST, false);
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                     $resp = curl_exec($ch);
                     curl_close($ch);
                     $response = json_decode($resp);

                     $raj = $response->title_results;
                     if (empty($raj)) {
                            $watchmode_id = 0;
                     } else {
                            $watchmode_id = $response->title_results[0]->id;
                     }
                     $GetWatchMode = "https://api.watchmode.com/v1/title/$watchmode_id/sources/?apiKey=MV4NSOqZUIcRaOwtale82abpuZ2jrHTS3kzzLmqH";

                     $curl1 = curl_init($GetWatchMode);
                     curl_setopt($curl1, CURLOPT_URL, $GetWatchMode);
                     curl_setopt($curl1, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl1, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);

                     $resp1 = curl_exec($curl1);
                     curl_close($curl1);
                     $watchModeJson = $resp1;
//                     $watchModeJson = json_decode($watchModeJson1);
//                     for ($i = 0; $i < count($watchModeJson) - 1; $i++) {
//                            if ($watchModeJson[$i]->region == 'US') {
//                                   if ($watchModeJson[$i]->source_id == 203 ||
//                                           $watchModeJson[$i]->source_id == 26 ||
//                                           $watchModeJson[$i]->source_id == 372 ||
//                                           $watchModeJson[$i]->source_id == 157 ||
//                                           $watchModeJson[$i]->source_id == 387 ||
//                                           $watchModeJson[$i]->source_id == 444 ||
//                                           $watchModeJson[$i]->source_id == 388 ||
//                                           $watchModeJson[$i]->source_id == 371 ||
//                                           $watchModeJson[$i]->source_id == 445 ||
//                                           $watchModeJson[$i]->source_id == 445 ||
//                                           $watchModeJson[$i]->source_id == 248 ||
//                                           $watchModeJson[$i]->source_id == 232 ||
//                                           $watchModeJson[$i]->source_id == 296 ||
//                                           $watchModeJson[$i]->source_id == 391) {
//                                          $filter_watch[] = $watchModeJson[$i];
//                                   }
//                            }
//                     }
//                     if (empty($filter_watch)) {
//                            $filter_watch1 = '[]';
//                     } else {
//                            $filter_watch1 = json_encode($filter_watch);
//                     }
                     //Start Trailer Url
                     if ($watch_type == 0) {
                            $url1 = "https://api.themoviedb.org/3/movie/$content_third_party_id/videos?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32";
                     } else {
                            $url1 = "https://api.themoviedb.org/3/tv/$content_third_party_id/videos?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32";
                     }

                     $curlTrailer = curl_init($url1);
                     curl_setopt($curlTrailer, CURLOPT_URL, $url1);
                     curl_setopt($curlTrailer, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curlTrailer, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curlTrailer, CURLOPT_SSL_VERIFYPEER, false);

                     $respcurlTrailer = curl_exec($curlTrailer);
                     curl_close($curlTrailer);
                     $Trailerdata = $respcurlTrailer;
                     $T_data = json_decode($Trailerdata);

                     if (empty($T_data->results)) {
                            $trailerKey = '';
                     } else {
                            for ($i = 0; $i < count($T_data->results); $i++) {
                                   $t_type = $T_data->results[$i]->type;
                                   $t_official = $T_data->results[$i]->official;
                                   $t_key = $T_data->results[$i]->key;

                                   if (($t_type == 'Trailer' && ($t_official == true || $t_official == 'true'))) {
                                          $trailerKey = $t_key;
                                   } else if ($t_type == 'Trailer') {
                                          $trailerKey = $t_key;
                                   } else {
                                          $trailerKey = '';
                                   }
                            }
                     }
                     //End Trailer Url
                     //
                     //CONTENT DETAILS API CALLING THIRDPARTY

                     if ($watch_type == 0) {
                            $url1 = "https://api.themoviedb.org/3/movie/$content_third_party_id?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32&language=en-US";
                     } else {
                            $url1 = "https://api.themoviedb.org/3/tv/$content_third_party_id?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32&language=en-US";
                     }
                     $curl = curl_init($url1);
                     curl_setopt($curl, CURLOPT_URL, $url1);
                     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                     $resp11 = curl_exec($curl);
                     curl_close($curl);
                     $data = $resp11;
                     $watch = json_decode($data);

                     if (!empty($watch->status_code)) {
                            return redirect()->back()->with('block', "Invalid Thirdparty Id Please Check Type Or ID..");
                     }

                     $backdrop_path1 = $watch->backdrop_path;
                     if ($backdrop_path1) {
                            $backdrop_path = $watch->backdrop_path;
                     }
                     $poster_path1 = $watch->poster_path;
                     if ($poster_path1) {
                            $poster_path = $watch->poster_path;
                     }
                     if ($watch_type == 0) {
                            $runtime = $watch->runtime;
                     } else {
                            $runtime = $watch->episode_run_time[0];
                     }
                     if ($watch_type == 0) {
                            $overview = $watch->overview;
                     } else {
                            $overview = $watch->overview;
                     }
                     if ($watch_type == 0) {
                            $release_date = $watch->release_date;
                     } else {
                            $release_date = $watch->first_air_date;
                     }

                     $vote_average1 = $watch->vote_average;
                     if ($vote_average1) {
                            $vote_average = $watch->vote_average;
                     }

                     $geners = $watch->genres;

                     $gen = json_encode($geners);

                     $of_content = new of_content();
                     $of_content->content_third_party_id = $request->content_third_party_id;
                     $of_content->content_cover_photo = $backdrop_path;
                     $of_content->content_title = $request->content_title;
                     $of_content->content_type = $request->content_type; //	0=moview, 1v=tv
                     $of_content->content_added_by = $adminid;
                     $of_content->content_discription = $overview;
                     $of_content->content_trailer_time = $release_date;
                     $of_content->content_release_time = $release_date;
                     $of_content->content_photo = $poster_path;
                     $of_content->content_tags = $gen;
                     $of_content->content_category = $request->content_category;
                     $of_content->content_length = $runtime;
                     $of_content->vote_average = $vote_average;
                     $of_content->trailer_url = $trailerKey;
                     $of_content->content_order = $request->content_order;
                     $of_content->streaming_provider = '';
                     $of_content->content_watchmode_id = $watchmode_id;
                     $of_content->watch_mode_json = $watchModeJson;
                     $of_content->save();

                     $check_trend = of_content_trending::where('content_third_party_id', $content_third_party_id)->first();

                     if ($check_trend) {

                     } else {
                            $of_content_trending = new of_content_trending();
                            $of_content_trending->content_third_party_id = $request->content_third_party_id;
                            $of_content_trending->is_custom = 0;
                            $of_content_trending->content_order = $request->content_order;
                            $of_content_trending->save();
                            return redirect()->back()->with('success', "Trending Content Added Successfully");
                     }



                     return redirect()->back()->with('success', "Trending Content Added Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function UpdateTrendingContent(Request $request) {
              if (Session::get('login')) {

                     $update_data = [];
                     $content_id = $request->content_id;
                     $content_third_party_id = $request->content_third_party_id;
                     $content_order = $request->t_content_order;
                     $production_type = $request->production_type;
                     $content_type_edited = $request->content_type_edited;
                     $update_data['content_type'] = $content_type_edited;

                     $content_title = $request->content_title;
                     $content_category = $request->content_category;
                     $trailer_url = $request->trailer_url;
                     $content_country = $request->content_country;

                     //CONTENT DETAILS API CALLING THIRDPARTY

                     if ($content_type_edited == 0) {
                            $url1 = "https://api.themoviedb.org/3/movie/$content_third_party_id?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32&language=en-US";
                     } else {
                            $url1 = "https://api.themoviedb.org/3/tv/$content_third_party_id?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32&language=en-US";
                     }
                     $curl = curl_init($url1);
                     curl_setopt($curl, CURLOPT_URL, $url1);
                     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                     $resp11 = curl_exec($curl);
                     curl_close($curl);
                     $data = $resp11;
                     $watch = json_decode($data);

                     $backdrop_path1 = $watch->backdrop_path;
                     if ($backdrop_path1) {
                            $backdrop_path = $watch->backdrop_path;
                     }
                     $poster_path1 = $watch->poster_path;
                     if ($poster_path1) {
                            $poster_path = $watch->poster_path;
                     }
                     if ($content_type_edited == 0) {
                            $runtime = $watch->runtime;
                     } else {
                            $runtime = $watch->episode_run_time[0];
                     }
                     if ($content_type_edited == 0) {
                            $overview = $watch->overview;
                     } else {
                            $overview = $watch->overview;
                     }
                     if ($content_type_edited == 0) {
                            $release_date = $watch->release_date;
                     } else {
                            $release_date = $watch->first_air_date;
                     }

                     $vote_average1 = $watch->vote_average;
                     if ($vote_average1) {
                            $vote_average = $watch->vote_average;
                     }


                     $geners = $watch->genres;

                     //Start Trailer Url
                     if ($content_type_edited == 0) {
                            $url1 = "https://api.themoviedb.org/3/movie/$content_third_party_id/videos?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32";
                     } else {
                            $url1 = "https://api.themoviedb.org/3/tv/$content_third_party_id/videos?api_key=38e671e0b2d5b0fe20aa5ba48adf8a32";
                     }

                     $curlTrailer = curl_init($url1);
                     curl_setopt($curlTrailer, CURLOPT_URL, $url1);
                     curl_setopt($curlTrailer, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curlTrailer, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($curlTrailer, CURLOPT_SSL_VERIFYPEER, false);

                     $respcurlTrailer = curl_exec($curlTrailer);
                     curl_close($curlTrailer);
                     $Trailerdata = $respcurlTrailer;
                     $T_data = json_decode($Trailerdata);
                     for ($i = 0; $i < count($T_data->results); $i++) {
                            $t_type = $T_data->results[$i]->type;
                            $t_official = $T_data->results[$i]->official;
                            $t_key = $T_data->results[$i]->key;

                            if (($t_type == 'Trailer' && ($t_official == true || $t_official == 'true'))) {
                                   $trailerKey = $t_key;
                            } else if ($t_type == 'Trailer') {
                                   $trailerKey = $t_key;
                            } else {
                                   $trailerKey = '';
                            }
                     }
                     //End Trailer Url


                     if ($trailerKey) {
                            $update_data['trailer_url'] = $trailerKey;
                     }
                     if ($backdrop_path) {
                            $update_data['content_cover_photo'] = $backdrop_path;
                     }
                     if ($content_title) {
                            $update_data['content_title'] = $content_title;
                     }
                     if ($content_type_edited) {
                            $update_data['content_type'] = $content_type_edited;
                     }

                     if ($overview) {
                            $update_data['content_discription'] = $overview;
                     }
                     if ($geners) {
                            $update_data['content_tags'] = $geners;
                     }

                     if ($release_date) {
                            $update_data['content_release_time'] = $release_date;
                     }
                     if ($poster_path) {
                            $update_data['content_photo'] = $poster_path;
                     }

                     if ($content_category) {
                            $update_data['content_category'] = $content_category;
                     }

                     if ($content_order) {
                            $update_t_data['content_order'] = $content_order;
                     }
                     if ($runtime) {
                            $update_data['content_length'] = $runtime;
                     }
                     if ($vote_average) {
                            $update_data['vote_average'] = $vote_average;
                     }
                     if ($trailer_url) {
                            $update_data['trailer_url'] = $trailer_url;
                     }

                     if ($production_type == 1) {
                            $update_data['is_popular'] = 0;
                            $update_data['is_new'] = 1;
                     } else {
                            $update_data['is_popular'] = 1;
                            $update_data['is_new'] = 0;
                     }

                     if (!empty($update_data)) {
                            of_content::where('content_third_party_id', $content_id)->update($update_data);
                     }

                     if (!empty($update_t_data)) {
                            of_content_trending::where('content_third_party_id', $content_id)->update($update_t_data);
                     }

                     return redirect()->back()->with('success', "Trending Content Updated Successfully");
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

       public function DeleteTrendingContent(Request $request) {
              if (Session::get('login')) {

                     $content_id = $request->content_third_party_id;



                     of_content_trending::where('content_third_party_id', $content_id)->delete();
                     return redirect()->back()->with('block', "Trending Content Deleted Successfully");
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
