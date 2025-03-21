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
                                          <h4 class="page-title">Games List</h4>
                                          <div class="pull-right">
                                                 <a  class="btn btn-success pull-right" data-target="#_AddCategory" data-toggle="modal"><i class=" mdi mdi-playlist-plus"></i>&nbsp;Add Games</a>
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
                                                               <th>Category</th>
                                                               <th>Badge</th>
                                                               <th>Image</th>
                                                               <th>Action</th>
                                                        </tr>
                                                 </thead>

                                                 <tbody>
                                                        <?php $i = 1; ?>
                                                        @foreach ($data as $value => $user)
                                                        <tr>
                                                               <td><?= $i++; ?></td>
                                                               <td>{{$user->name}}</td>
                                                               <td>{{$user->category_name}}</td>
                                                               <td>{{$user->badge}}</td>
                                                               <td> @if($user->imgURL)
                                                                      <img src="{{$user->imgURL}}" class="img-thumbnail" alt="No Image" width="65" height="65">

                                                                      @else
                                                                      @endif</td>

                                                               <td>
                                                                      <a href="{{url('edit-games/'.$user->game_id)}}" class="btn btn-info">
                                                                             <i class="mdi mdi-pencil"></i>
                                                                      </a>
                                                                      <a href="{{url('delete-games/'.$user->game_id)}}" class="btn btn-danger">
                                                                             <i class="mdi mdi-delete"></i>
                                                                      </a>
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
       <div id="_AddCategory" class="modal fade modal-xxl" role="dialog" style="background-color: #ffffff87;">
              <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                            <div class="modal-header">
                                   <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                   <h4 class="modal-title text-black">Add Games</h4>
                            </div>
                            <form class="form-horizontal" action="{{ url('add-games') }}" method="post" enctype="multipart/form-data">
                                   @csrf

                                   <div class="modal-body">
                                          <div class="text-center">

                                                 <div class="member-card">

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Name</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="name" required="required" class="form-control" placeholder="Enter a Name">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">imgURL</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="imgURL" required="required" class="form-control" placeholder="Enter a imgURL">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">redirectURL</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="redirectURL" required="required" class="form-control" placeholder="Enter a redirectURL">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">iframe code</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="iframe" required="required" class="form-control" placeholder="Enter a iframe code">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Category type <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <select name="category_id" required="" class="form-control" id="category_id">
                                                                             @foreach ($category as $value => $data)
                                                                             <option value="{{$data->category_id}}">{{$data->category_name}}</option>
                                                                             @endforeach
                                                                      </select>
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Badge <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <select name="badge" class="form-control" id="badge">
                                                                             <option value="">Select Badge</option>
                                                                             <option value="new">New</option>
                                                                             <option value="hot">Hot</option>
                                                                             <option value="top">Top</option>
                                                                             <option value="trending">Trending</option>
                                                                             <option value="popular">Popular</option>
                                                                             <option value="featured">Featured</option>
                                                                             <option value="recommended">Recommended</option>
                                                                             <option value="upcoming">Upcoming</option>
                                                                             <option value="coming soon">Coming Soon</option>
                                                                      </select>
                                                               </div>
                                                        </div>

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Description</label>
                                                               <div class="col-md-10">
                                                                      <textarea name="description" id="html_editor" class="form-control">{{$data->description ?? ''}}</textarea>
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

       <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
       <script>
              ClassicEditor
                     .create(document.querySelector('#html_editor'), {
                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable'],
                            height: '300px'
                     })
                     .catch(error => {
                            console.error(error);
                     });
       </script>

@include('Admin.footer')
