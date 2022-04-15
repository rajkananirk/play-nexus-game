
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" type="image/png" sizes="16x16" href="public/assets/images/d.png">
        <title>Elite Admin Template - The Ultimate Multipurpose admin template</title>

        <!-- page css -->
        <link href="public/assets/dist/css/pages/login-register-lock.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link href="public/assets/dist/css/style.min.css" rel="stylesheet">


        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-default card-no-border">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">Fish Map</p>
            </div>
        </div>
        <section id="wrapper">
            <div class="login-register" style="background-image:url(public/assets/images/background/login-register.jpg);">
                <div class="login-box card">
                    <div class="card-body"> 
                        <form action="dashboard" method="get">
                            <h3 class="text-center m-b-20">log In</h3> 
                            <div class="form-group"> 
                                <div class="col-xs-12">
                                    <input class="form-control" type="text" required="" placeholder="Username"> </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input class="form-control" type="password" required="" placeholder="Password"> </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12"> 
                                    <div class="d-flex no-block align-items-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Remember me</label>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="col-xs-12 p-b-20"> 
                                    <button class="btn btn-block btn-lg btn-info btn-rounded" type="submit" style="background-color: #2a4073">Log In</button>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                                </div> 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <script src="public/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="public/assets/node_modules/popper/popper.min.js"></script>
        <script src="public/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
        <!--Custom JavaScript -->
        <script type="text/javascript">
            $(function () {
                $(".preloader").fadeOut();
            });
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });
            $('#to-recover').on("click", function () {
                $("#loginform").slideUp();
                $("#recoverform").fadeIn();
            });
        </script>

    </body>

</html>