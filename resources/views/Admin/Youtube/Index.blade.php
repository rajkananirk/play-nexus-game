@include('Admin.header')

<style>
       .card-box2 {
              padding: 20px;
              border: 2px solid #ececec;
              -webkit-border-radius: 5px;
              border-radius: 5px;
              -moz-border-radius: 5px;
              background-clip: padding-box;
              margin-bottom: 20px;
              background-color: #ffffff;
       }

       .table-responsive2 {
              min-height: .01%;
              overflow-x: auto;
       }

       table.dataTable {
              margin-top: 10px !important;
              margin-bottom: 18px !important;
       }
</style>

@include('Admin.sidebar')


<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
       <!-- Start content -->
       <div class="content">
              <div class="container">


                     <div class="row">
                            <div class="col-xs-12">
                                   <div class="page-title-box">
                                          <h4 class="page-title">Youtube</h4>
                                          <div class="clearfix"> </div>
                                   </div>
                            </div>
                     </div>
                     <!-- end row -->


                     <div class="row">
                            <div class="col-sm-12">

                                   <div class="card-box2 table-responsive2">


                                          <table id="clients" class="table table-striped table-bordered">
                                                 <thead>
                                                        <tr>
                                                               <th>Feed Id</th>
                                                               <th>Title</th>
                                                               <th>About</th>
                                                               <th>User name</th>
                                                               <th>Url</th>
                                                               <th>Location</th>
                                                               <th>Category</th>
                                                               <th>Date</th>
                                                               <th>Action</th>
                                                        </tr>
                                                 </thead>

                                                 <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($data as $value => $user)
                                                        <tr>
                                                               <td>{{$user->feed_id}}</td>
                                                               <td>{{$user->title}}</td>
                                                               <td>{{$user->about}}</td>
                                                               <td>{{$user->user_name}}</td>
                                                               <td>{{$user->youtube_url}}</td>
                                                               <td>{{$user->location}}</td>
                                                               <td>{{$user->category_name}}</td>
                                                               <td>{{$user->created_at}}</td>
                                                               <td>
                                                                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#_EditUser{{$user->feed_id}}">
                                                                             <i class="mdi mdi-pencil"></i>
                                                                      </button>
                                                                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#_DeleteUser{{$user->feed_id}}">
                                                                             <i class="mdi mdi-delete"></i>
                                                                      </button>
                                                               </td>



                                                        </tr>
                                                        @endforeach

                                                 </tbody>
                                          </table>
                                   </div>
                            </div>
                     </div>


              </div> <!-- container -->

       </div> <!-- content -->
</div> <!-- content -->

@foreach ($data as $value => $user)

<div id="_EditUser{{$user->feed_id}}" class="modal fade" role="dialog" style="background-color: #ffffff87;">
       <div class="modal-dialog modal-lg">

              <!-- Modal content-->
              <div class="modal-content">
                     <div class="modal-header">
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-black">Youtube Feed</h4>
                     </div>
                     <form class="form-horizontal" action="{{ url('update-feed') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="feed_id" class="form-control" value="{{$user->feed_id}}">

                            <div class="modal-body">
                                   <div class="text-center">

                                          <div class="member-card">

                                                 <div class="form-group">
                                                        <label class="col-md-2 control-label">Title</label>
                                                        <div class="col-md-10">
                                                               <input type="text" name="title" class="form-control" value="{{$user->title}}">
                                                        </div>
                                                 </div>

                                                 <div class="form-group">
                                                        <label class="col-md-2 control-label">About</label>
                                                        <div class="col-md-10">
                                                               <input type="text" name="about" class="form-control" value="{{$user->about}}">
                                                        </div>
                                                 </div>
                                                 <div class="form-group">
                                                        <label class="col-md-2 control-label">Youtube Url</label>
                                                        <div class="col-md-10">
                                                               <input type="text" name="youtube_url" class="form-control" value="{{$user->youtube_url}}">
                                                        </div>
                                                 </div>
                                                 <div class="form-group">
                                                        <label class="col-md-2 control-label">Location</label>
                                                        <div class="col-md-10">
                                                               <input type="text" name="location" class="form-control">
                                                        </div>
                                                 </div>

                                                 <div class="form-group">
                                                        <label class="col-md-2 control-label">Category </label>
                                                        <div class="col-md-10">
                                                               <select name="category_id" class="form-control">
                                                                      @foreach ($category as $value => $cat)
                                                                      @if($cat->category_id ==  $user->category_id)
                                                                      <option value="{{$cat->category_id}}" selected>{{$cat->category_name}}</option>
                                                                      @else
                                                                      <option value="{{$cat->category_id}}">{{$cat->category_name}}</option>
                                                                      @endif
                                                                      @endforeach
                                                               </select>
                                                        </div>
                                                 </div>

                                          </div>
                                          <button type="submit" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light">Save</button>
                                          <button type="button" class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light" data-dismiss="modal">Cancel</button>
                                   </div>
                            </div>
                     </form>
              </div>
       </div>
</div>
@endforeach

@foreach ($data as $value => $user)

<div id="_DeleteUser{{$user->feed_id}}" class="modal fade" role="dialog" style="background-color: #ffffff87;">
       <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                     <div class="modal-header">
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-black">Delete Feed</h4>
                     </div>
                     <form class="form-horizontal" action="{{ url('delete-feed') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="feed_id" class="form-control" value="{{$user->feed_id}}">

                            <div class="modal-body">
                                   <div class="text-center">

                                          <div class="member-card">


                                          </div>
                                          <button type="submit" class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light">Yes Delete it!</button>
                                          <button type="button" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light" data-dismiss="modal">Cancel</button>
                                   </div>
                            </div>
                     </form>
              </div>
       </div>
</div>
@endforeach

@include('Admin.footer')
