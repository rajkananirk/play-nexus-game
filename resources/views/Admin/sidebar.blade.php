
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="left side-menu">
       <div class="sidebar-inner slimscrollleft">

              <!--- Sidemenu -->
              <div id="sidebar-menu">
                     <ul>
                            <li class="menu-title"> NAVIGATION </li>

                            <li class="has_sub">
                                   <a href="{{ url('dashboard') }}" class="waves-effect"><i class="mdi mdi-view-dashboard"></i> <span> Dashboard </span>
                                   </a>
                            </li>

                          
                            <li class="has_sub">
                                   <a href="{{ url('category') }}" class="waves-effect"><i class="fa fa-list-ul"></i><span>Category</span> </a>
                            </li>


                            <li class="has_sub">
                                   <a href="{{ url('games') }}" class="waves-effect"><i class="fa fa-gamepad"></i><span>Games</span> </a>
                            </li>
                          


                            <li class="has_sub">
                                   <a href="{{ url('logout') }}" class=" "><i class="ti-power-off"></i><span>Logout</span> </a>
                            </li>

                     </ul>
              </div>
              <!-- Sidebar -->
              <div class="clearfix"></div>

       </div>
       <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End