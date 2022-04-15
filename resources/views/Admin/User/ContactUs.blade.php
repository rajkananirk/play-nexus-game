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
                                          <h4 class="page-title">Contact Us List</h4>
                                          <div class="pull-right">
                                              <!--<a  class="btn btn-success pull-right" data-target="#_add" data-toggle="modal"><i class=" mdi mdi-playlist-plus"></i>&nbsp;Add Category</a>-->
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
                                                               <th>Name</th>
                                                               <th>Email</th>
                                                               <th>Phone number</th>
                                                               <th>Comments</th>
                                                        </tr>
                                                 </thead>

                                                 <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($data as $value => $user)
                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td>{{$user->name}}</td>
                                                               <td>{{$user->email_id}}</td>
                                                               <td>{{$user->phone_number}}</td>
                                                               <td>{{$user->comments}}</td>
                                                        </tr>
                                                        @endforeach

                                                 </tbody>
                                          </table>
                                   </div>
                            </div>
                     </div>


              </div> <!-- container -->

       </div> <!-- content -->

       @foreach ($data as $value => $user)

       <div id="_EditUser{{$user->id}}" class="modal fade" role="dialog" style="background-color: #ffffff87;">
              <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                            <div class="modal-header">
                                   <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                   <h4 class="modal-title text-black">Edit User</h4>
                            </div>
                            <form class="form-horizontal" action="{{ url('update-user') }}" method="post" enctype="multipart/form-data">
                                   @csrf
                                   <input type="hidden" name="user_id" class="form-control" value="{{$user->id}}">

                                   <div class="modal-body">
                                          <div class="text-center">

                                                 <div class="member-card">

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">User name</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="username" required="required" class="form-control" value="{{$user->user_name}}">
                                                               </div>
                                                        </div>


                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Phone Number</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="phone_number" class="form-control" value="{{$user->phone_number}}">
                                                               </div>
                                                        </div>

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Email Id</label>
                                                               <div class="col-md-10">
                                                                      <input type="email" name="email" class="form-control" value="{{$user->email}}">
                                                               </div>
                                                        </div>

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Address</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="address" class="form-control" value="{{$user->address}}">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Prefecture</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="prefecture" class="form-control" value="{{$user->prefecture}}">
                                                               </div>
                                                        </div>

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Profile</label>
                                                               <div class="col-md-10">
                                                                      <input type="file" name="profile_pic" class="form-control">
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

       <div id="_DeleteUser{{$user->id}}" class="modal fade" role="dialog" style="background-color: #ffffff87;">
              <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                            <div class="modal-header">
                                   <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                   <h4 class="modal-title text-black">Delete User</h4>
                            </div>
                            <form class="form-horizontal" action="{{ url('delete-user') }}" method="post" enctype="multipart/form-data">
                                   @csrf
                                   <input type="hidden" name="user_id" class="form-control" value="{{$user->id}}">

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

       @foreach ($data as $value => $user)

       <div id="_BlockUser{{$user->id}}" class="modal fade" role="dialog" style="background-color: #ffffff87;">
              <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                            <div class="modal-header">
                                   <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                   @if($user->is_blocked == 1)
                                   <h4 class="modal-title text-black">Unblock User</h4>
                                   @else
                                   <h4 class="modal-title text-black">Block User</h4>
                                   @endif
                            </div>
                            <form class="form-horizontal" action="{{ url('block-user') }}" method="post" enctype="multipart/form-data">
                                   @csrf
                                   @if($user->is_blocked == 1)
                                   <input type="hidden" name="value_block" class="form-control" value="0">
                                   @else
                                   <input type="hidden" name="value_block" class="form-control" value="1">
                                   @endif

                                   <input type="hidden" name="user_id" class="form-control" value="{{$user->id}}">

                                   <div class="modal-body">
                                          <div class="text-center">

                                                 <div class="member-card">


                                                 </div>
                                                 @if($user->is_blocked == 1)
                                                 <button type="submit" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light">Yes UnBlock it!</button>

                                                 <button type="button" class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light" data-dismiss="modal">Cancel</button>
                                                 @else
                                                 <button type="submit" class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light">Yes Block it!</button>
                                                 <button type="button" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light" data-dismiss="modal">Cancel</button>
                                                 @endif
                                          </div>
                                   </div>
                            </form>
                     </div>
              </div>
       </div>
       @endforeach

       @include('Admin.footer')
