
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
                            <h4 class="text-themecolor"><b>Feed</b></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo url('/dashboard') ?>">Home</a></li>
                                    <li class="breadcrumb-item active"><b>Feed</b></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive m-t-40">
                                        <table id="example23"
                                               class="display nowrap table table-hover table-striped table-bordered"0
                                               cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Feed id</th>
                                                    <th>Feed about</th>
                                                    <th>Is feed spot</th>
                                                    <th>Feed location</th>
                                                    <th>Feed category id</th>
                                                    <th>Feed by</th>
                                                    <th>Hotel name</th>
                                                    <th>Feed price</th>
                                                    <th>Feed title</th>
                                                    <th>Action</th>
                                                </tr> 
                                            </thead> 
                                            <tbody>
                                                @foreach($feed as $value => $data)
                                                <tr>
                                                    <td>{{$data->feed_id}}</td>
                                                    <td>{{$data->feed_about}}</td>
                                                    <td>
                                                        @if($data->is_feed_spot == 1)
                                                        spot
                                                        @else
                                                        Not Spot
                                                        @endif

                                                    </td>
                                                    <td>{{$data->feed_location}}</td>
                                                    <td>{{$data->category_name}}</td>
                                                    <td>{{$data->user_name}}</td>
                                                    <td>{{$data->hotel_name}}</td>
                                                    <td>{{$data->feed_price}}</td>
                                                    <td>{{$data->feed_title}}</td>
                                                    <td>
                                                        <button type="button" style="background-color: #2a4073" class="btn btn-info waves-effect" alt="default" data-toggle="modal" data-target="#myModal{{$data->feed_id}}" class="model_img img-responsive">Edit</button>
                                                        <button type="button" class="btn btn-danger waves-effect waves-light" alt="default" data-toggle="modal" data-target="#myCarousel{{$data->feed_id}}" class="model_img img-responsive">Delete</button>
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
                    @foreach($feed as $value => $data)
                    <div id="myCarousel{{$data->feed_id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Feed Delete</h4>
                                </div>
                                <form action="{{ url('delete-feed') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="feed_id" value="{{$data->feed_id}}" class="form-control">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @foreach($feed as $value => $data)
                    <div id="myModal{{$data->feed_id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Feed Edit</h4>
                                </div>
                                <form action="{{ url('update-feed') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="feed_id" value="{{$data->feed_id}}" class="form-control">

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Feed about</label><br>
                                            <input type="text" name="feed_about" value="{{$data->feed_about}}" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label>Is feed spot</label><br>
                                            <select class="form-control" name="is_feed_spot" id="myModal">
                                                <option value="1">Spot</option>
                                                <option value="2">Not spot</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Feed location</label><br>
                                            <input type="text" name="feed_location" value="{{$data->feed_location}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Hotel name</label><br>
                                            <input type="text" name="hotel_name" value="{{$data->hotel_name}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Feed title</label><br>
                                            <input type="text" name="feed_title" value="{{$data->feed_title}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Feed price</label><br>
                                            <input type="text" name="feed_price" value="{{$data->feed_price}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Feed category id</label><br>
                                            <select class="form-control" name="feed_category_id" id="myModal">
                                                @foreach($category as $feed => $data)
                                                <option class="form-control" value="{{$data->feed_category_id}}">{{$data->category_name}}</option>
                                                @endforeach
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
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
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


<!--
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
                                            </select>-->
