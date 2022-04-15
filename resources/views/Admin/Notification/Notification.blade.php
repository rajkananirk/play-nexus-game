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
                                          <h4 class="page-title">Notification List</h4>
                                          <div class="pull-right">
                                          </div>
                                          <div class="clearfix"></div>
                                   </div>
                            </div>
                     </div>
                     <!-- end row -->



                     <style>
                            #myrow{
                                   margin: auto;
                                   display: flex;
                                   justify-content: space-between;
                            }
                            #myoptionstyle{
                                   border-bottom: solid 1px #00000073;
                                   padding: 5px 0px 5px 0px;
                            }

                     </style>
                     <div class="row" id="myrow">
                            <div class="col-md-6">
                                   <div class="card-box2 table-responsive2">
                                          <div class="m-t-40 account-pages">
                                                 <div class="text-center account-logo-box">
                                                        <h2 class="text-uppercase">
                                                               <a href="#" class="text-success">
                                                                      <h4 style="color: #ffffff" class="text-uppercase font-bold m-b-0">Send Notification</h4>
                                                               </a>
                                                        </h2>
                                                 </div>
                                                 @if ($message = Session::get('error'))
                                                 <div class="alert alert-danger alert-block">
                                                        <button type="button" class="close" data-dismiss="alert">×</button>
                                                        <strong>{{ $message }}</strong>
                                                 </div>
                                                 @endif
                                                 <div class="account-content" style="padding: 30px 30px 60px 30px;">
                                                        <form class="form-horizontal" action="<?php echo url('/') ?>/send_notification" method="post">
                                                               @csrf

                                                               <label class="text-black">Title</label>
                                                               <div class="form-group ">
                                                                      <div class="col-xs-12">
                                                                             <input class="form-control" type="text" name="title" required="" placeholder="Enter a notification title">
                                                                      </div>
                                                               </div>

                                                               <label class="text-black">Message</label>
                                                               <div class="form-group">
                                                                      <div class="col-xs-12">
                                                                             <input class="form-control" type="text" name="msg" required="" placeholder="Enter a notification message">
                                                                      </div>
                                                               </div>

                                                               <label class="text-black">App User </label>
                                                               <div class="form-group">
                                                                      <div class="col-xs-12">
                                                                             <select name="wine_category[]" style="height: 174px;" class="form-control" multiple>
                                                                                    <option value="0" id="myoptionstyle">All</option>
                                                                                    @foreach ($data as $value => $cat)
                                                                                    <option value="{{$cat->id}}" id="myoptionstyle">{{$cat->user_name}}</option>
                                                                                    @endforeach

                                                                             </select>
                                                                      </div>
                                                               </div>

                                                               <div class="form-group account-btn text-center m-t-10">
                                                                      <div class="col-xs-12">
                                                                             <button class="btn w-md btn-bordered btn-danger waves-effect waves-light" type="submit">Send</button>
                                                                      </div>
                                                               </div>

                                                        </form>

                                                        <div class="clearfix"></div>

                                                 </div>
                                          </div>
                                   </div>
                            </div>
                     </div>
              </div> <!-- container -->
       </div> <!-- content -->

       @include('Admin.footer')
