
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
            @include('header')
            @include('sidebar');
            <div class="page-wrapper">
                <div class="container-fluid">
                    <div class="row page-titles">
                        <div class="col-md-5 align-self-center">
                            <h4 class="text-themecolor"><b>Dashboard</b></h4>
                        </div>
                        <div class="col-md-7 align-self-center text-right">
                            <div class="d-flex justify-content-end align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo url('/dashboard') ?>">Home</a></li>
                                    <li class="breadcrumb-item active" style="color: #2a4073"><b>Dashboard</b></li>
                                </ol> 
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex no-block">
                                        <div class="round align-self-center round-success" style="background-color: #2a4073"><a class="ti-bar-chart-alt" style="color:white" href="<?php echo url('/feed') ?>"></a></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{$feed->count()}}</h3>
                                            <h5 class="text-muted m-b-0">Feed</h5></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex no-block">
                                        <div class="round align-self-center round-info" style="background-color: #2a4073"><a class="ti-loop" style="color:white" href="<?php echo url('/event') ?>"></a></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{$event->count()}}</h3> 
                                            <h5 class="text-muted m-b-0">Event</h5></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex no-block">
                                        <div class="round align-self-center round-danger" style="background-color: #2a4073"><a class="ti-panel" style="color:white" href="<?php echo url('/youtube') ?>"></a></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{$youtube->count()}}</h3>
                                            <h5 class="text-muted m-b-0">Youtube</h5></div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex no-block">
                                        <div class="round align-self-center round-success" style="background-color : #2a4073"><a class="ti-bag" style="color:white" href="<?php echo url('/category') ?>"></a></div>
                                        <div class="m-l-10 align-self-center">
                                            <h3 class="m-b-0">{{$category->count()}}</h3>
                                            <h5 class="text-muted m-b-0">Catrgory</h5></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="right-sidebar">
                        <div class="slimscrollright">
                            <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toogle"></i></span> </div>
                            <div class="r-panel-body">
                                <ul id="themecolors" class="m-t-20">
                                    <li><b>With Light sidebar</b></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme working">1</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a><li>
                                    <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
                                    <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme ">7</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                                    <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
                                </ul>
                                <ul class="m-t-20 chatonline">
                                    <li><b>Chat option</b></li>
                                    <li>
                                        <a href="javascript:void(0)"><img src="public/assets/images/users/1.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><img src="public/assets/images/users/2.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><img src="public/assets/images/users/3.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><img src="public/assets/images/users/4.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><img src="public/assets/images/users/5.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><img src="public/assets/images/users/6.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><img src="public/assets/images/users/7.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)"><img src="public/assets/images/users/8.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                    </li>
                                </ul>
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
        <script src="public/assets/node_modules/knob/jquery.knob.js"></script>
        <script>
$(function () {
    $('[data-plugin="knob"]').knob();
});
        </script>
    </body>
</html>