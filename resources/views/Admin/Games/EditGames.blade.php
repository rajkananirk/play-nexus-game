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

       .badge-grid {
              display: grid;
              grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
              gap: 15px;
              padding: 15px;
              background-color: #f8f9fa;
              border-radius: 5px;
       }
       
       .badge-item {
              display: flex;
              align-items: center;
              padding: 8px 12px;
              background-color: white;
              border: 1px solid #dee2e6;
              border-radius: 4px;
              transition: all 0.3s ease;
       }
       
       .badge-item:hover {
              box-shadow: 0 2px 4px rgba(0,0,0,0.1);
       }
       
       .badge-item input[type="checkbox"] {
              margin-right: 8px;
       }
       
       .badge-item label {
              margin-bottom: 0;
              cursor: pointer;
              font-weight: normal;
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
                                                               <label class="col-md-2 control-label">Show in which page? <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <div id="badge-container" class="badge-grid">
                                                                            <input type="hidden" name="checkbox_validation" id="checkbox_validation" value="{{ $data->is_new == 1 ? 'checked' : '' }}">
                                                                            <div class="badge-item">
                                                                                <input type="checkbox" name="is_new" {{ $data->is_new == 1 ? 'checked' : '' }} value="1" id="is_new" class="badge-checkbox">
                                                                                <label for="is_new">New</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="checkbox" name="is_top" {{ $data->is_top == 1 ? 'checked' : '' }} value="1" id="is_top" class="badge-checkbox">
                                                                                <label for="is_top">Top</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="checkbox" name="is_trending" {{ $data->is_trending == 1 ? 'checked' : '' }} value="1" id="is_trending" class="badge-checkbox">
                                                                                <label for="is_trending">Trending</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="checkbox" name="is_popular" {{ $data->is_popular == 1 ? 'checked' : '' }}  value="1" id="is_popular" class="badge-checkbox">
                                                                                <label for="is_popular">Popular</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="checkbox" name="is_featured" {{ $data->is_featured == 1 ? 'checked' : '' }} value="1" id="is_featured" class="badge-checkbox">
                                                                                <label for="is_featured">Featured</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="checkbox" name="is_recommended" {{ $data->is_recommended == 1 ? 'checked' : '' }} value="1" id="is_recommended" class="badge-checkbox">
                                                                                <label for="is_recommended">Recommended</label>
                                                                            </div>
                                                                           
                                                                            <div class="badge-item">
                                                                                <input type="checkbox" name="is_latest" {{ $data->is_latest == 1 ? 'checked' : '' }} value="1" id="is_latest" class="badge-checkbox">
                                                                                <label for="is_latest">Latest</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="checkbox" name="is_all" {{ $data->is_all == 1 ? 'checked' : '' }} value="1" id="is_all" class="badge-checkbox">
                                                                                <label for="is_all">All</label>
                                                                            </div>
                                                                      </div>
                                                                      <div id="checkbox-error" class="text-danger" style="display: none;">Please select at least one option</div>
                                                               </div>
                                                        </div>
                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Badge <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <select name="badge" class="form-control" id="badge">
                                                                             <option value="none" {{ $data->badge == 'none' ? 'selected' : '' }}>Select Badge</option>
                                                                             <option value="new" {{ $data->badge == 'new' ? 'selected' : '' }}>New</option>
                                                                             <option value="hot" {{ $data->badge == 'hot' ? 'selected' : '' }}>Hot</option>
                                                                             <option value="latest" {{ $data->badge == 'latest' ? 'selected' : '' }}>Latest</option>
                                                                             <option value="trending" {{ $data->badge == 'trending' ? 'selected' : '' }}>Trending</option>
                                                                             <option value="popular" {{ $data->badge == 'popular' ? 'selected' : '' }}>Popular</option>
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
                                                 <a href="{{url('games')}}" class="btn btn-danger btn-sm w-sm waves-effect m-t-10 waves-light">Cancel</a>
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
