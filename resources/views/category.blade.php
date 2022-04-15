<!DOCTYPE html>
<html lang="en">

       <head>
              <meta charset="utf-8">
              <meta http-equiv="X-UA-Compatible" content="IE=edge">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <meta name="description" content="">
              <meta name="author" content="">
              <link rel="icon" type="image/png" sizes="16x16" href="public/assets/images/d.png">
              <title>FishMap</title>
              <link rel="stylesheet" type="text/css"
                    href="public/assets/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css">
              <link rel="stylesheet" type="text/css"
                    href="public/assets/node_modules/datatables.net-bs4/css/responsive.dataTables.min.css">
              <link href="public/assets/dist/css/style.min.css" rel="stylesheet">
              <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
              <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
              <![endif]-->
       </head>

       <body class="skin-default fixed-layout">
              <div class="preloader">
                     <div class="loader">
                            <div class="loader__figure"></div>
                            <p class="loader__label">Fish Map</p>
                     </div>
              </div>
              <div id="main-wrapper">
                     @include('header');
                     @include('sidebar');
                     <div class="page-wrapper">
                            <div class="container-fluid">
                                   <div class="row page-titles">
                                          <div class="col-md-5 align-self-center">
                                                 <h4 class="text-themecolor"><b>Category</b></h4>
                                          </div>
                                          <div class="col-md-7 align-self-center text-right">
                                                 <div class="d-flex justify-content-end align-items-center">
                                                        <ol class="breadcrumb">
                                                               <li class="breadcrumb-item"><a href="<?php echo url('/dashboard') ?>">Home</a></li>
                                                               <li class="breadcrumb-item active"style="color: #2a4073" ><b>Category</b></li>
                                                        </ol>
                                                 </div>
                                          </div>
                                   </div>
                                   <div class="row">
                                          <div class="col-12">
                                                 <div class="card">
                                                        <div class="card-body">
                                                               <button onclick="document.getElementById('myCarousel').style.display = 'block'" style="background-color: #2a4073;float: right" class="btn btn-info waves-effect">Add Category</button>
                                                               <div class="table-responsive m-t-40">
                                                                      <table id="example23"
                                                                             class="display nowrap table table-hover table-striped table-bordered"0
                                                                             cellspacing="0" width="100%">
                                                                             <thead>
                                                                                    <tr>
                                                                                           <th>Category id</th>
                                                                                           <th>Category name</th>
                                                                                           <th>Category image</th>
                                                                                           <th>Category type</th>
                                                                                           <th>Action</th>
                                                                                    </tr>
                                                                             </thead>
                                                                             <tbody>
                                                                                    @foreach($category as $value => $data)
                                                                                    <tr>
                                                                                           <td>{{$data->category_id}}</td>
                                                                                           <td>{{$data->category_name}}</td>
                                                                                           <td>
                                                                                                  <img src="http://147.182.175.164//fishmap/{{$data->category_image}}"class="center" style="width: 5%;vertical-align: middle">
                                                                                           </td>
                                                                                           <td>
                                                                                                  @if($data->category_type == 1)
                                                                                                  feed
                                                                                                  @elseif($data->category_type == 2)
                                                                                                  event
                                                                                                  @elseif($data->category_type == 3)
                                                                                                  youtube
                                                                                                  @else
                                                                                                  @endif
                                                                                           </td>
                                                                                           <td>
                                                                                                  <button type="button" style="background-color: #2a4073" class="btn btn-info waves-effect" alt="default" data-toggle="modal" data-target="#myModal{{$data->category_id}}" class="model_img img-responsive">Edit</button>
                                                                                                  <button type="button" class="btn btn-danger waves-effect waves-light" alt="default" data-toggle="modal" data-target="#myModalLabel{{$data->category_id}}" class="model_img img-responsive">Delete</button>
                                                                                           </td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                             </tbody>
                                                                      </table>
                                                               </div>
                                                        </div>
                                                 </div>
                                          </div>
                                   </div>
                                   @foreach($category as $value => $data)
                                   <div id="myModalLabel{{$data->category_id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                          <div class="modal-dialog">
                                                 <div class="modal-content">
                                                        <div class="modal-header">
                                                               <h4 class="modal-title" id="myModalLabel">Category Delete</h4>
                                                        </div>
                                                        <form action="{{ url('delete-category') }}" method="POST">
                                                               @csrf
                                                               <input type="hidden" name="category_id" value="{{$data->category_id}}" class="form-control">
                                                               <div class="modal-footer">
                                                                      <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                                                      <button type="submit" class="btn btn-danger waves-effect waves-light">Delete</button>
                                                               </div>
                                                        </form>
                                                 </div>
                                          </div>
                                   </div>
                                   @endforeach
                                   @foreach($category as $value => $data)
                                   <div id="myModal{{$data->category_id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                          <div class="modal-dialog modal-lg">
                                                 <div class="modal-content">
                                                        <div class="modal-header">
                                                               <h4 class="modal-title" id="myModalLabel">Category Edit</h4>
                                                        </div>
                                                        <form action="{{ url('update-category') }}" method="POST" enctype="multipart/form-data">
                                                               @csrf
                                                               <input type="hidden" name="category_id" value="{{$data->category_id}}" class="form-control">
                                                               <div class="modal-body">
                                                                      <div class="form-group">
                                                                             <label>Category name</label><br>
                                                                             <input type="text" name="category_name" value="{{$data->category_name}}" class="form-control">
                                                                      </div>
                                                                      <div class="form-group">
                                                                             <label>Category image</label><br>
                                                                             <input type="file" name="category_image" value="{{$data->category_image}}" class="form-control">
                                                                      </div>
                                                                      <div class="form-group">
                                                                             <label>Category type</label><br>
                                                                             <select class="form-control" name="category_type" id="myModal">
                                                                                    @if($data->category_type == 1)
                                                                                    <option value="1" selected>Feed</option>
                                                                                    <option value="2">Event</option>
                                                                                    <option value="3">youtube</option>
                                                                                    @elseif($data->category_type == 2)
                                                                                    <option value="2" selected>Event</option>
                                                                                    <option value="1">Feed</option>
                                                                                    <option value="3">youtube</option>
                                                                                    @elseif($data->category_type == 3)
                                                                                    <option value="3" selected>youtube</option>
                                                                                    <option value="2">Event</option>
                                                                                    <option value="1">Feed</option>
                                                                                    @else
                                                                                    @endif
                                                                             </select>
                                                                      </div>
                                                                      <div class="modal-footer">
                                                                             <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                                                             <button type="submit" style="background-color: #2a4073" class="btn btn-danger waves-effect waves-light">Edit</button>
                                                                      </div>
                                                               </div>
                                                        </form>
                                                 </div>
                                          </div>
                                   </div>
                                   @endforeach
                                   <div id="myCarousel" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                          <div class="modal-dialog modal-lg">
                                                 <div class="modal-content">
                                                        <div class="modal-header">
                                                               <h4 class="modal-title" id="myModalLabel">Add Category</h4>
                                                        </div>
                                                        <form action="{{ url('add-category') }}" method="POST" enctype="multipart/form-data">
                                                               @csrf
                                                               <div class="modal-body">
                                                                      <div class="form-group">
                                                                             <label>Category name</label>
                                                                             <input type="text" name="category_name" class="form-control">
                                                                      </div>
                                                                      <div class="form-group">
                                                                             <label>Category image</label>
                                                                             <input alt="Category image" type="file" name="category_image" alt="category_image" class="form-control ">
                                                                      </div>
                                                                      <div class="form-group">
                                                                             <label>Category type</label>
                                                                             <select class="form-control" name="category_type" id="myModal">
                                                                                    <option value="1" selected>Feed</option>
                                                                                    <option value="2">Event</option>
                                                                                    <option value="3">youtube</option>
                                                                             </select>
                                                                      </div>
                                                                      <div class="modal-footer">
                                                                             <button type="button" class="btn btn-default waves-effect" onclick="document.getElementById('myCarousel').style.display = 'none'" class="close" data-dismiss="modal">Close</button>
                                                                             <button type="submit" style="background-color: #2a4073" class="btn btn-danger waves-effect waves-light">Add</button>
                                                                      </div>
                                                               </div>
                                                        </form>
                                                 </div>
                                          </div>
                                   </div>
                            </div>
                     </div>
              </div>
       </div>

</div>
</div>
</div>
<script src="public/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script src="public/assets/node_modules/popper/popper.min.js"></script>
<script src="public/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="public/assets/dist/js/perfect-scrollbar.jquery.min.js"></script>
<script src="public/assets/dist/js/waves.js"></script>
<script src="public/assets/dist/js/sidebarmenu.js"></script>
<script src="public/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="public/assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
<script src="public/assets/dist/js/custom.min.js"></script>
<script src="public/assets/node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="public/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js"></script>
<script>
                                                $(function () {
                                                    $('#myTable').DataTable();
                                                    var table = $('#example').DataTable({
                                                        "columnDefs": [{
                                                                "visible": false,
                                                                "targets": 2
                                                            }],
                                                        "order": [
                                                            [2, 'asc']
                                                        ],
                                                        "displayLength": 25,
                                                        "drawCallback": function (settings) {
                                                            var api = this.api();
                                                            var rows = api.rows({
                                                                page: 'current'
                                                            }).nodes();
                                                            var last = null;
                                                            api.column(2, {
                                                                page: 'current'
                                                            }).data().each(function (group, i) {
                                                                if (last !== group) {
                                                                    $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                                                    last = group;
                                                                }
                                                            });
                                                        }
                                                    });
                                                    $('#example tbody').on('click', 'tr.group', function () {
                                                        var currentOrder = table.order()[0];
                                                        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                                                            table.order([2, 'desc']).draw();
                                                        } else {
                                                            table.order([2, 'asc']).draw();
                                                        }
                                                    });
                                                    $('#config-table').DataTable({
                                                        responsive: true
                                                    });
                                                    $('#example23').DataTable({
                                                    });
                                                });

</script>
</body>

</html>



