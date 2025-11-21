@extends('admin.layout.table_master')

@section('title', 'Dashboard')

@section('sidebar')
    @parent

@endsection

@section('content')

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Dashboard</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{env('APP_NAME')}}</a>
                                </li>

                                <li class="active">
                                    Dashboard
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row text-center">

                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <a href="{{ route('admin.customers.list') }}">
                            <div class="card-box widget-box-one bg-danger   ">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">Total Customers</p>
                                    <h1 class="text-dark"><span data-plugin="counterup">{{$totcustomer ?? ''}}</span></h1>
                                    <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                </div>
                            </div>
                        </a>
                    </div><!-- end col -->
                    <div class="col-lg-3 col-md-3 col-sm-6" >
                        <a href="{{ route('admin.quotation.list') }}">
                            <div class="card-box widget-box-one bg-success">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">Total  Quotation</p>

                                    <h1 class="text-dark"><span data-plugin="counterup">{{$totquotation ?? ''}}</span></h1>

                                    <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <a href="{{ route('admin.product.list') }}">
                            <div class="card-box widget-box-one bg-primary">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">Total Product</p>
                                    <h1 class="text-dark"><span data-plugin="counterup">{{$totproduct}}</span></h1>
                                    <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <a href="{{ route('admin.quotation.list',['status' => 'Y']) }}">
                            <div class="card-box widget-box-one bg-danger">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">Pending Quotation</p>
                                    <h1 class="text-dark"><span data-plugin="counterup">{{$pendingQuotation}}</span></h1>
                                    <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                </div>
                            </div>
                        </a>
                    </div>

                </div>

                <!-- end row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">

                            <h4 class="header-title m-t-0">Quotation Remainder</h4>

                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Quot No</th>
                                    <th>Quot Date</th>

                                    <th>Client Name</th>
                                    <th>Subject</th>
                                    <th>Amount</th>
                                    <th>Stage</th>

                                    <th></th>
                                </tr>
                                </thead>


                                <tbody>


                                <?php $srno=0; ?>
                                @foreach($quot as $data)
                                    @if($data->quot_stage=="Accepted" || $data->quot_stage=="Invoiced" || $data->quot_stage=="Canceled")
                                    @else
                                        <?php $srno++; ?>
                                        <tr>
                                            <td style="width: 2%;vertical-align: top;text-align: center;">
                                                {{$srno}}
                                            </td>
                                            <td   style="vertical-align: top;width: 10%">
                                                <a href="{{url('client/quot/view/'.$data->id)}}">{{$data->quotation_no}}</a></td>

                                            <td   style="vertical-align: top;width: 5%">
                                                {{date('d-m-Y',strtotime($data->quot_date))}}
                                            </td>

                                            <!--      <td  style="vertical-align: top;"></td>
                                             <td  style="vertical-align: top;"></td> -->
                                            <td  style="vertical-align: top;width: 25%">{{$data->customer_name}}</td>
                                            <td  style="vertical-align: top;">{{$data->subject}}</td>
                                            <td  style="vertical-align: top;width:2%;text-align: left;">{{number_format($data->grand_total)}}
                                            </td>
                                            <td  style="vertical-align: top;text-align: center;width: 10%">
                                                {{$data->quot_stage}}
                                            </td>

                                            <td  style="vertical-align: top;width: 11%">

                                                <a class="table-btn" title="edit"  href="{{url('admin/quotation_edit/'.$data->id)}}"title="edit"><i class="fa fa-pencil" style="margin:5px;font-size: 8px;"></i></a>

                                                <a class="table-btn" title="preview" target="_blank" href="{{url('admin/quot_preview/'.$data->id)}}"><i class="fa fa-eye" style="margin:5px;font-size: 8px;"></i></a>

                                                <input type="hidden" name="primary_email" id="primary_email{{$srno}}" value="{{$data->primary_email}}">
                                                <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}" value="{{$data->secondary_email}}">

                                                <input type="hidden" name="subject_mail" id="subject_mail{{$srno}}" value="{{$data->subject}}">

                                                <input type="hidden" name="quot_id" id="quot_id{{$srno}}" value="{{$data->id}}">

                                                <a class="table-btn" title="pdf"  href="{{url('admin/quot_print/'.$data->id)}}"><i class="fa fa-file-pdf-o" style="margin:5px;font-size: 8px;"></i></a>


                                            <!-- <a class="table-btn" title="mail" href="{{url('admin/quot_mail/'.$data->id)}}"><i class="fa fa-envelope" style="margin:10px;font-size: 10px;"></i></a> -->
                                            <!--  <a class="table-btn modalopen" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"  data-id="{{$srno}}" title="mail" href="#">
                                <i class="fa fa-envelope" style="margin:5px;font-size: 8px;"></i>
                              </a> -->

                                            @if($data->so_status=="Y")
                                            @else
                                                <!-- <button class=""> <a class="table-btn" title="sales order" href="{{url('client/sales_order/create/'.$data->id)}}" >Create SO.</a></button> -->
                                                @endif


                                                <a class="table-btn" title="delete"   href="{{url('admin/quotation_delete/'.$data->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash" style="font-size: 8px;"></i></a>

                                                {{--                                                    <a class="table-btn" title="Followup" href="{{url('admin/quotation/followup/'.$data->id)}}" ><i class="fa fa-comment" style="margin:5px;font-size: 8px;"></i></a>--}}


                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>

                <!-- end row -->


                <!-- <div class="row">
                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Recent Users</h4>

                            <div class="table-responsive">
                                <table class="table table table-hover m-0">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>User Name</th>
                                            <th>Phone</th>
                                            <th>Location</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>
                                                <img src="assets/images/users/avatar-1.jpg" alt="user" class="thumb-sm img-circle" />
                                            </th>
                                            <td>
                                                <h5 class="m-0">Louis Hansen</h5>
                                                <p class="m-0 text-muted font-13"><small>Web designer</small></p>
                                            </td>
                                            <td>+12 3456 789</td>
                                            <td>USA</td>
                                            <td>07/08/2016</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <img src="assets/images/users/avatar-2.jpg" alt="user" class="thumb-sm img-circle" />
                                            </th>
                                            <td>
                                                <h5 class="m-0">Craig Hause</h5>
                                                <p class="m-0 text-muted font-13"><small>Programmer</small></p>
                                            </td>
                                            <td>+89 345 6789</td>
                                            <td>Canada</td>
                                            <td>29/07/2016</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <img src="assets/images/users/avatar-3.jpg" alt="user" class="thumb-sm img-circle" />
                                            </th>
                                            <td>
                                                <h5 class="m-0">Edward Grimes</h5>
                                                <p class="m-0 text-muted font-13"><small>Founder</small></p>
                                            </td>
                                            <td>+12 29856 256</td>
                                            <td>Brazil</td>
                                            <td>22/07/2016</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <img src="assets/images/users/avatar-4.jpg" alt="user" class="thumb-sm img-circle" />
                                            </th>
                                            <td>
                                                <h5 class="m-0">Bret Weaver</h5>
                                                <p class="m-0 text-muted font-13"><small>Web designer</small></p>
                                            </td>
                                            <td>+00 567 890</td>
                                            <td>USA</td>
                                            <td>20/07/2016</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <img src="assets/images/users/avatar-5.jpg" alt="user" class="thumb-sm img-circle" />
                                            </th>
                                            <td>
                                                <h5 class="m-0">Mark</h5>
                                                <p class="m-0 text-muted font-13"><small>Web design</small></p>
                                            </td>
                                            <td>+91 123 456</td>
                                            <td>India</td>
                                            <td>07/07/2016</td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Recent Users</h4>

                            <div class="table-responsive">
                                <table class="table table table-hover m-0">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>User Name</th>
                                            <th>Phone</th>
                                            <th>Location</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>
                                                <span class="avatar-sm-box bg-success">L</span>
                                            </th>
                                            <td>
                                                <h5 class="m-0">Louis Hansen</h5>
                                                <p class="m-0 text-muted font-13"><small>Web designer</small></p>
                                            </td>
                                            <td>+12 3456 789</td>
                                            <td>USA</td>
                                            <td>07/08/2016</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <span class="avatar-sm-box bg-primary">C</span>
                                            </th>
                                            <td>
                                                <h5 class="m-0">Craig Hause</h5>
                                                <p class="m-0 text-muted font-13"><small>Programmer</small></p>
                                            </td>
                                            <td>+89 345 6789</td>
                                            <td>Canada</td>
                                            <td>29/07/2016</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <span class="avatar-sm-box bg-brown">E</span>
                                            </th>
                                            <td>
                                                <h5 class="m-0">Edward Grimes</h5>
                                                <p class="m-0 text-muted font-13"><small>Founder</small></p>
                                            </td>
                                            <td>+12 29856 256</td>
                                            <td>Brazil</td>
                                            <td>22/07/2016</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <span class="avatar-sm-box bg-pink">B</span>
                                            </th>
                                            <td>
                                                <h5 class="m-0">Bret Weaver</h5>
                                                <p class="m-0 text-muted font-13"><small>Web designer</small></p>
                                            </td>
                                            <td>+00 567 890</td>
                                            <td>USA</td>
                                            <td>20/07/2016</td>
                                        </tr>

                                        <tr>
                                            <th>
                                                <span class="avatar-sm-box bg-orange">M</span>
                                            </th>
                                            <td>
                                                <h5 class="m-0">Mark</h5>
                                                <p class="m-0 text-muted font-13"><small>Web design</small></p>
                                            </td>
                                            <td>+91 123 456</td>
                                            <td>India</td>
                                            <td>07/07/2016</td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div> -->





            </div> <!-- container -->

        </div> <!-- content -->

@endsection
