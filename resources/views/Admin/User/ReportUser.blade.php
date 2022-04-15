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
                                          <h4 class="page-title">Report User List</h4>
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
                                                               <th>Report By</th>
                                                               <th>Report To</th>
                                                               <th>Report Status</th>
                                                               <th>Action</th>
                                                        </tr>
                                                 </thead>

                                                 <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($data as $value => $user)
                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td>
                                                                      UserId: {{$user->report_by}}<br>
                                                                      Username: {{$user->by_username}}<br>
                                                                      Email: {{$user->by_email}}
                                                               </td>
                                                               <td>

                                                                      UserId: {{$user->report_to}}<br>
                                                                      Username: {{$user->to_username}}<br>
                                                                      Email: {{$user->to_email}}
                                                               </td>
                                                               <td>
                                                                      @if($user->report_status == 1)
                                                                      <label class="label label-orange">Pending</label>
                                                                      @elseif($user->report_status == 2)
                                                                      <label class="label label-success">Accept</label>
                                                                      @else
                                                                      <label class="label label-danger">Reject</label>
                                                                      @endif
                                                               </td>

                                                               <td>
                                                                      <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#_ActionUser{{$user->report_id}}">
                                                                             Action
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



       @foreach ($data as $value => $user)

       <div id="_ActionUser{{$user->report_id}}" class="modal fade" role="dialog" style="background-color: #ffffff87;">
              <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                            <div class="modal-header">
                                   <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                   <h4 class="modal-title text-black">Action User</h4>
                            </div>
                            <form class="form-horizontal" action="{{ url('report-action') }}" method="post" enctype="multipart/form-data">
                                   @csrf
                                   <input type="hidden" name="report_id" class="form-control" value="{{$user->report_id}}">

                                   <div class="modal-body">
                                          <div class="text-center">
                                                 <div class="member-card">
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Status <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <select name="report_status" class="form-control" required="">
                                                                             <option value="2">Accept</option>
                                                                             <option value="3">Reject</option>
                                                                      </select>
                                                               </div>
                                                        </div>
                                                 </div>
                                                 <button type="submit" class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light">Yes Update it!</button>
                                                 <button type="button" class="btn btn-success btn-sm w-sm waves-effect m-t-10 waves-light" data-dismiss="modal">Cancel</button>
                                          </div>
                                   </div>
                            </form>
                     </div>
              </div>
       </div>
       @endforeach


       @include('Admin.footer')
