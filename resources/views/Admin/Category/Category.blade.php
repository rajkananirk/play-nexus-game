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
                                          <h4 class="page-title">Category List</h4>
                                          <div class="pull-right">
                                                 <a  class="btn btn-success pull-right" data-target="#_AddCategory" data-toggle="modal"><i class=" mdi mdi-playlist-plus"></i>&nbsp;Add Category</a>
                                          </div>
                                          <div class="clearfix"></div>
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
                                                               <th>S No.</th>
                                                               <th>Title</th>
                                                               <th>Type</th>
                                                               <th>Image</th>
                                                               <th>Action</th>
                                                        </tr>
                                                 </thead>

                                                 <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($data as $value => $user)
                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td>{{$user->category_name}}</td>
                                                               <td>

                                                                      @if($user->category_type == 1)
                                                                      <span class="label label-pink">Feed</span>
                                                                      @elseif($user->category_type == 2)
                                                                      <span class="label label-orange">Event</span>
                                                                      @else
                                                                      <span class="label label-success">Youtube</span>
                                                                      @endif
                                                               </td>
                                                               <td> @if($user->category_imaged)
                                                                      <img src="{{$user->category_image}}" class="img-thumbnail" alt="No Image" width="65" height="65">

                                                                      @else
                                                                      @endif</td>

                                                               <td>
                                                                      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#_EditUser{{$user->category_id}}">
                                                                             <i class="mdi mdi-pencil"></i>
                                                                      </button>
                                                                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#_DeleteUser{{$user->category_id}}">
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
       <div id="_AddCategory" class="modal fade" role="dialog" style="background-color: #ffffff87;">
              <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                            <div class="modal-header">
                                   <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                   <h4 class="modal-title text-black">Add Category</h4>
                            </div>
                            <form class="form-horizontal" action="{{ url('add-category') }}" method="post" enctype="multipart/form-data">
                                   @csrf

                                   <div class="modal-body">
                                          <div class="text-center">

                                                 <div class="member-card">

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Category Title</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="category_name" required="required" class="form-control" placeholder="Enter a Title">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Category Image</label>
                                                               <div class="col-md-10">
                                                                      <input type="file" name="category_image" class="form-control">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Category type <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <select name="category_type" required="" class="form-control" id="category_type">
                                                                             <option value="1" selected="">Feed</option>
                                                                             <option value="3">Youtube</option>
                                                                             <option value="2">Event</option>
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
       @foreach ($data as $value => $user)

       <div id="_EditUser{{$user->category_id}}" class="modal fade" role="dialog" style="background-color: #ffffff87;">
              <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                            <div class="modal-header">
                                   <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                   <h4 class="modal-title text-black">Edit Category</h4>
                            </div>
                            <form class="form-horizontal" action="{{ url('update-category') }}" method="post" enctype="multipart/form-data">
                                   @csrf
                                   <input type="hidden" name="category_id" class="form-control" value="{{$user->category_id}}">

                                   <div class="modal-body">
                                          <div class="text-center">

                                                 <div class="member-card">

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Category Title</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="category_name" required="required" class="form-control" value="{{$user->category_name}}">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Category Image</label>
                                                               <div class="col-md-10">
                                                                      <input type="file" name="category_image" required="required" class="form-control">
                                                               </div>
                                                        </div>

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Category type <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <select name="category_type" required="" class="form-control">
                                                                             @if($user->category_type == 1)
                                                                             <option value="1" selected>Feed</option>
                                                                             <option value="2">Youtube</option>
                                                                             <option value="3">Event</option>
                                                                             @elseif($user->category_type == 2)
                                                                             <option value="1">Feed</option>
                                                                             <option value="2" selected>Event</option>
                                                                             <option value="3">Youtube</option>
                                                                             @else
                                                                             <option value="1">Feed</option>
                                                                             <option value="2">Event</option>
                                                                             <option value="3" selected>Youtube</option>

                                                                             @endif
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
</div>
@endforeach

@foreach ($data as $value => $user)

<div id="_DeleteUser{{$user->category_id}}" class="modal fade" role="dialog" style="background-color: #ffffff87;">
       <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                     <div class="modal-header">
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-black">Delete Category</h4>
                     </div>
                     <form class="form-horizontal" action="{{ url('delete-category') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="category_id" class="form-control" value="{{$user->category_id}}">

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
