<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Mdl_category;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Session;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends BaseController {

       function GetCategory(Request $request) {
              if (!empty($request->session()->has('login'))) {

                     $data = Mdl_category::get();

                     return view("Admin/Category/Category")->with(compact('data'));
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

       public function UpdateCategory(Request $request) {
              if (Session::get('login')) {

                     $update_data = [];
                     $trending_id = $request->category_id;
                     $title = $request->category_name;
                     $category_type = $request->category_type;

                     if ($title) {
                            $update_data['category_name'] = $title;
                     }
                     if ($category_type) {
                            $update_data['category_type'] = $category_type;
                     }

                     if ($request->hasFile('category_image')) {

                            $imageName = time() . '.' . request()->category_image->getClientOriginalExtension();

                            request()->category_image->move(public_path('uploads'), $imageName);
                            $product_image = 'public/uploads/' . $imageName;
                            $update_data['category_image'] = $product_image;
                     }

                     if (!empty($update_data)) {
                            Mdl_category::where('category_id', $trending_id)->update($update_data);
                     }

                     return redirect()->back()->with('success', "Category Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteCategory(Request $request) {
              if (Session::get('login')) {

                     $trending_id = $request->category_id;

                     Mdl_category::where('category_id', $trending_id)->delete();
                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function AddCategory(Request $request) {
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
                     $of_activity->category_type = $request->category_type;
                     $of_activity->save();
                     return redirect()->back()->with('success', "User Updated Successfully");
              } else {
                     return Redirect::to('/');
              }
       }

       public function DeleteUsersProfile(Request $request) {
              if (Session::get('login')) {

                     $user_id = $request->user_id;

                     User::where('id', $user_id)->delete();


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

}
