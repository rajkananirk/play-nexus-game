
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
                            <h4 class="text-themecolor"><b>Event</b></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo url('/dashboard') ?>">Home</a></li>
                                    <li class="breadcrumb-item active" style="color: #2a4073"><b>Event</b></li>
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
                                                    <th>Event id</th>
                                                    <th>Event location</th>
                                                    <th>Event title</th>
                                                    <th>Event register link</th>
                                                    <th>Event date</th>
                                                    <th>Event about</th>
                                                    <th>Event category id</th>
                                                    <th>Action</th>
                                                </tr> 
                                            </thead> 
                                            <tbody>
                                                @foreach($event as $value => $data)
                                                <tr>
                                                    <td>{{$data->event_id}}</td>
                                                    <td>{{$data->event_location}}</td>
                                                    <td>{{$data->event_title}}</td>
                                                    <td>{{$data->event_register_link}}</td>
                                                    <td>{{$data->event_date}}</td>
                                                    <td>{{$data->event_about}}</td>
                                                    <td>{{$data->category_name}}</td>
                                                    <td>
                                                        <button type="button" style="background-color: #2a4073" class="btn btn-info waves-effect" alt="default" data-toggle="modal" data-target="#myModal{{$data->event_id}}" class="model_img img-responsive">Edit</button>
                                                        <button type="button" class="btn btn-danger waves-effect waves-light" alt="default" data-toggle="modal" data-target="#myCarousel{{$data->event_id}}" class="model_img img-responsive">Delete</button>
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
                    @foreach($event as $value => $data)
                    <div id="myCarousel{{$data->event_id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Event Delete</h4>
                                </div>
                                <form action="{{ url('delete-event') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{$data->event_id}}" class="form-control">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger waves-effect waves-light">Delete</button>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @foreach($event as $value => $data)
                    <div id="myModal{{$data->event_id}}" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Event Edit</h4>
                                </div>
                                <form action="{{ url('update-event') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{$data->event_id}}" class="form-control">

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Event location</label><br>
                                            <input type="text" name="event_location" value="{{$data->event_location}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Event title</label><br>
                                            <input type="text" name="event_title" value="{{$data->event_title}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Event register link</label><br>
                                            <input type="text" name="event_register_link" value="{{$data->event_register_link}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Event date</label><br>
                                            <input type="date" name="event_date" value="{{$data->event_date}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Event about</label><br>
                                            <input type="text" name="event_about" value="{{$data->event_about}}" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Event category id</label><br>
                                            <select class="form-control" name="event_category_id" id="event_category_id">
                                                @foreach($category as $value => $data)
                                                <option value="{{$data->event_category_id}}">{{$data->category_name}}</option>
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