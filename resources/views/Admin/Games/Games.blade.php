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
<meta name="csrf-token" content="{{ csrf_token() }}">

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
                                                                      <button type="button" onclick="deleteGame({{$user->game_id}})" class="btn btn-danger">
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
                                                               <label class="col-md-2 control-label">Show in which page? <span class="text-danger">*</label>
                                                               <div class="col-md-10">
                                                                      <div id="badge-container" class="badge-grid">
                                                                            <input type="hidden" name="checkbox_validation" id="checkbox_validation" value="">
                                                                            <div class="badge-item">
                                                                                <input type="hidden" name="is_new" value="0">
                                                                                <input type="checkbox" name="is_new" value="1" id="is_new" class="badge-checkbox">
                                                                                <label for="is_new">New</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="hidden" name="is_top" value="0">
                                                                                <input type="checkbox" name="is_top" value="1" id="is_top" class="badge-checkbox">
                                                                                <label for="is_top">Top</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="hidden" name="is_trending" value="0">
                                                                                <input type="checkbox" name="is_trending" value="1" id="is_trending" class="badge-checkbox">
                                                                                <label for="is_trending">Trending</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="hidden" name="is_popular" value="0">
                                                                                <input type="checkbox" name="is_popular" value="1" id="is_popular" class="badge-checkbox">
                                                                                <label for="is_popular">Popular</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="hidden" name="is_featured" value="0">
                                                                                <input type="checkbox" name="is_featured" value="1" id="is_featured" class="badge-checkbox">
                                                                                <label for="is_featured">Featured</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="hidden" name="is_recommended" value="0">
                                                                                <input type="checkbox" name="is_recommended" value="1" id="is_recommended" class="badge-checkbox">
                                                                                <label for="is_recommended">Recommended</label>
                                                                            </div>
                                                                           
                                                                            <div class="badge-item">
                                                                                <input type="hidden" name="is_latest" value="0">
                                                                                <input type="checkbox" name="is_latest" value="1" id="is_latest" class="badge-checkbox">
                                                                                <label for="is_latest">Latest</label>
                                                                            </div>
                                                                            <div class="badge-item">
                                                                                <input type="hidden" name="is_all" value="0">
                                                                                <input type="checkbox" name="is_all" value="1" id="is_all" class="badge-checkbox">
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
                                                                             <option value="none" >Select Badge</option>
                                                                             <option value="new" >New</option>
                                                                             <option value="hot" >Hot</option>
                                                                             <option value="latest" >Latest</option>
                                                                             <option value="trending" >Trending</option>
                                                                             <option value="popular" >Popular</option>
                                                                      </select>
                                                               </div>
                                                        </div>

                                                        <div class="form-group">
                                                               <label class="col-md-2 control-label">Description <span class="text-danger">*</span></label>
                                                               <div class="col-md-10">
                                                                      <textarea name="description" id="html_editor" class="form-control">{{$data->description ?? ''}}</textarea>
                                                                      <div id="description-error" class="text-danger" style="display: none;">Please enter a description</div>
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
       <!-- Add SweetAlert2 CSS and JS -->
       <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       <script>
              // Delete game function
              function deleteGame(gameId) {
                     Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                     }).then((result) => {
                            if (result.isConfirmed) {

                                   // Send delete request
                                   fetch(`http://localhost/play-nexus-hub/api/delete-games/${gameId}`, {
                                          method: 'DELETE',
                                          headers: {
                                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                 'Accept': 'application/json',
                                                 'Content-Type': 'application/json'
                                          }
                                   })
                                   .then(response => response.json())
                                   .then(data => {
                                          console.log(data);
                                          if (data.status == 1) {
                                                 Swal.fire(
                                                        'Deleted!',
                                                        'Game has been deleted.',
                                                        'success'
                                                 ).then(() => {
                                                        // Reload the page or remove the row
                                                        location.reload();
                                                 });
                                          } else {
                                                 Swal.fire(
                                                        'Error!',
                                                        data.message || 'Something went wrong!',
                                                        'error'
                                                 );
                                          }
                                   })
                                   .catch(error => {
                                          Swal.fire(
                                                 'Error!',
                                                 'Something went wrong!',
                                                 'error'
                                          );
                                   });
                            }
                     });
              }

              let editor;
              ClassicEditor
                     .create(document.querySelector('#html_editor'), {
                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable'],
                            height: '300px'
                     })
                     .then(newEditor => {
                            editor = newEditor;
                     })
                     .catch(error => {
                            console.error(error);
                     });

              // Validation for checkboxes and form
              document.addEventListener('DOMContentLoaded', function() {
                     const form = document.querySelector('form');
                     const checkboxes = document.querySelectorAll('.badge-checkbox');
                     const errorDiv = document.getElementById('checkbox-error');
                     const descriptionError = document.getElementById('description-error');
                     const validationInput = document.getElementById('checkbox_validation');

                     // Function to update checkbox validation state
                     function updateValidationState() {
                            const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                            validationInput.value = isChecked ? 'valid' : '';
                            errorDiv.style.display = isChecked ? 'none' : 'block';
                            return isChecked;
                     }

                     // Add change event listener to all checkboxes
                     checkboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', updateValidationState);
                     });

                     // Add form submit validation
                     form.addEventListener('submit', function(event) {
                            let isValid = true;

                            // Validate checkboxes
                            if (!updateValidationState()) {
                                   isValid = false;
                                   errorDiv.style.display = 'block';
                                   document.getElementById('badge-container').scrollIntoView({ behavior: 'smooth' });
                            }

                            // Validate CKEditor content
                            const editorData = editor.getData().trim();
                            if (!editorData) {
                                   isValid = false;
                                   descriptionError.style.display = 'block';
                                   editor.editing.view.focus();
                            } else {
                                   descriptionError.style.display = 'none';
                            }

                            if (!isValid) {
                                   event.preventDefault();
                            }
                     });
              });
       </script>

@include('Admin.footer')
