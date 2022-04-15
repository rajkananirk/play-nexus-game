<!DOCTYPE html>
<html lang="en">

       <head>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <meta name="author" content="winwin">
              <title>Fishmap - Admin Panel</title>

              <link href="<?php echo url('/'); ?>/public/web/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

              <!-- App css -->
              <link href="<?php echo url('/'); ?>/public/web/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/assets/css/core.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/assets/css/components.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/assets/css/icons.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/assets/css/pages.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/assets/css/menu.css" rel="stylesheet" type="text/css" />
              <link href="<?php echo url('/'); ?>/public/web/assets/css/responsive.css" rel="stylesheet" type="text/css" />
              <link rel="stylesheet" href="<?php echo url('/'); ?>/public/web/plugins/switchery/switchery.min.css">
              <script src="<?php echo url('/'); ?>/public/web/assets/js/modernizr.min.js"></script>


       </head>


       <body class="fixed-left">

              <!-- Begin page -->
              <div id="wrapper">

                     <!-- Top Bar Start -->
                     <div class="topbar">

                            <!-- LOGO -->
                            <div class="topbar-left">
                                   <a href="#" class="logo"><span>Fish<span style="color: #ff1616">Map</span></span><i class="mdi mdi-layers"></i></a>
                            </div>

                            <!-- Button mobile view to collapse sidebar menu -->
                            <div class="navbar navbar-default" role="navigation">
                                   <div class="container">

                                          <!-- Navbar-left -->
                                          <ul class="nav navbar-nav navbar-left">
                                                 <li>
                                                        <button class="button-menu-mobile open-left waves-effect">
                                                               <i class="mdi mdi-menu"></i>
                                                        </button>
                                                 </li>
                                          </ul>

                                          <!-- Right(Notification) -->
                                          <ul class="nav navbar-nav navbar-right">
                                                 <li class="dropdown user-box">
                                                        <a href="#" class="dropdown-toggle waves-effect user-link" data-toggle="dropdown" aria-expanded="true">
                                                               <p>Admin</p>
                                                        </a>

                                                        <ul class="dropdown-menu dropdown-menu-right arrow-dropdown-menu arrow-menu-right user-list notify-list">

                                                               <li><a href="<?php echo url('/logout'); ?>"><i class="ti-power-off m-r-5"></i> Logout</a></li>
                                                        </ul>
                                                 </li>

                                          </ul> <!-- end navbar-right -->

                                   </div><!-- end container -->
                            </div><!-- end navbar -->
                     </div>
                     <!-- Top Bar End -->