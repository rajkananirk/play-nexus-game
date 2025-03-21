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
                                          <h4 class="page-title">Edit Games</h4>
                                          <div class="pull-right">
                                                 <a href="{{url('games')}}" class="btn btn-success pull-right"><i class=" mdi mdi-arrow-left"></i>&nbsp;Back</a>
                                          </div>
                                          <div class="clearfix"></div>
                                   </div>
                            </div>
                     </div>
                     <!-- end row -->


                     <div class="row">
                            <div class="col-sm-12">

                                   <div class="card-box2 table-responsive2">
                                   <form class="form-horizontal" action="{{ url('update-games') }}" method="post" enctype="multipart/form-data">
                                   @csrf

                                   <div class="modal-body">
                                          <div class="text-center">
                                                 <input type="hidden" name="game_id" value="{{$data->game_id}}">
                                                 <div class="member-card">

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Name</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="name" required="required" class="form-control" placeholder="Enter a Name" value="{{$data->name}}">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">imgURL</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="imgURL" required="required" class="form-control" placeholder="Enter a imgURL" value="{{$data->imgURL}}">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">redirectURL</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="redirectURL" required="required" class="form-control" placeholder="Enter a redirectURL" value="{{$data->redirectURL}}">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">iframe code</label>
                                                               <div class="col-md-10">
                                                                      <input type="text" name="iframe" required="required" class="form-control" placeholder="Enter a iframe code" value="{{$data->iframe}}">
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Category <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <select name="category_id" required="" class="form-control" id="category_id">
                                                                             @foreach ($category as $value => $category)
                                                                             <option value="{{$category->category_id}}" {{ $data->category_id == $category->category_id ? 'selected' : '' }}>{{$category->category_name}}</option>
                                                                             @endforeach
                                                                      </select>
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Badge <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <select name="badge" class="form-control" id="badge">
                                                                             <option value="none" {{ $data->badge == 'none' ? 'selected' : '' }}>Select Badge</option>
                                                                             <option value="new" {{ $data->badge == 'new' ? 'selected' : '' }}>New</option>
                                                                             <option value="hot" {{ $data->badge == 'hot' ? 'selected' : '' }}>Hot</option>
                                                                             <option value="top" {{ $data->badge == 'top' ? 'selected' : '' }}>Top</option>
                                                                             <option value="trending" {{ $data->badge == 'trending' ? 'selected' : '' }}>Trending</option>
                                                                             <option value="popular" {{ $data->badge == 'popular' ? 'selected' : '' }}>Popular</option>
                                                                             <option value="featured" {{ $data->badge == 'featured' ? 'selected' : '' }}>Featured</option>
                                                                             <option value="recommended" {{ $data->badge == 'recommended' ? 'selected' : '' }}>Recommended</option>
                                                                             <option value="upcoming" {{ $data->badge == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                                                             <option value="coming soon" {{ $data->badge == 'coming soon' ? 'selected' : '' }}>Coming Soon</option>
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


              </div> <!-- container -->

       </div> <!-- content -->
       
       <!-- Remove TinyMCE and add CKEditor -->
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
