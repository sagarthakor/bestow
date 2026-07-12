@extends('admin.layout.master')

@section('title', 'Vendor Preview')

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
                            <h4 class="page-title">Vendor Preview</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('customer-list')}}">Vendor List</a>
                                </li>

                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="card-box table-responsive">

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#home">Details</a></li>
                                <li><a data-toggle="tab" href="#contact">Contacts</a></li>
                                <!-- <li><a data-toggle="tab" href="#menu2">Quotes</a></li> -->
                                <ul class="nav navbar-nav navbar-right">
                                    @can('vendor_update')
                                        <li><a href="{{route('admin.vendor.edit',['id' => $data->id])}}"><span class="glyphicon glyphicon-user"></span>Edit</a></li>
                                    @endcan
                                    @can('vendor_delete')
                                        <li><a onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('admin.vendor.delete',['id' => $data->id])}}"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                                    @endcan
                                </ul>
                            </ul>

                            <div class="tab-content">
                                <div id="home" class="tab-pane fade in active">

                                    <table class="table table-borderless" style="border:0px !important">
                                        <caption style="color: #222;font-weight: 600">Organization Details</caption>
                                        <tr>
                                            <td style="width: 20%">Vendor Name </td>
                                            <td style="color: #222;"> {{$data->vendor_name}}</td>
                                            <td style="width: 20%">Primary Phone</td>
                                            <td style="color: #222;">{{$data->primary_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">Industry</td>
                                            <td style="color: #222;"> {{$data->industry_name}}</td>
                                            <td style="width: 20%">Secondary Phone</td>
                                            <td style="color: #222;">{{$data->secondary_phone}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">Company Type</td>
                                            <td style="color: #222;"> {{$data->type_name}}</td>
                                            <td style="width: 20%">Primary Email</td>
                                            <td style="color: #222;">{{$data->primary_email}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">GST No</td>
                                            <td style="color: #222;"> {{$data->owner_gst}}</td>
                                            <td style="width: 20%">Secondary Email</td>
                                            <td style="color: #222;">{{$data->secondary_email}}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">PAN No</td>
                                            <td style="color: #222;"> {{$data->owner_pan}}</td>
                                            <td style="width: 20%">Website</td>
                                            <td style="color: #222;">{{$data->website}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="color: #222;font-weight: 600">Organization Contact Person Information</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">Full Name</td>
                                            <td style="color: #222;">{{$data->owner_name}}</td>
                                            <td style="width: 20%">Alternate No.</td>
                                            <td style="color: #222;">{{$data->alternate_no}}</td>

                                        </tr>
                                        <tr>
                                            <td style="width: 20%">Mobile No</td>
                                            <td style="color: #222;">{{$data->owner_mobile}}</td>
                                            <td style="width: 20%">Work Email.</td>
                                            <td style="color: #222;">{{$data->work_email}}</td>

                                        </tr>

                                        <tr>
                                            <td style="width: 20%">Personal Email</td>
                                            <td style="color: #222;">{{$data->owner_email}}</td>

                                            <td style="width: 20%">Department</td>
                                            <td style="color: #222;">{{$data->department}}</td>

                                        </tr>
                                        <tr>
                                            <td style="width: 20%">Designation</td>
                                            <td style="color: #222;">{{$data->designation}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" style="color: #222;font-weight: 600">Address Information</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">Billing Address</td>
                                            <td style="color: #222;">{{$data->billing_address}}</td>
                                            <td style="width: 20%">Country</td>
                                            <td style="color: #222;">{{$data->country_name}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">State</td>
                                            <td style="color: #222;">{{$data->state_name}}</td>
                                            <td style="width: 20%">City</td>
                                            <td style="color: #222;">{{$data->city_name}}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">Postal Code</td>
                                            <td style="color: #222;"> {{$data->billing_postalcode}}</td>

                                        </tr>


                                        <tr>
                                            <td style="width: 20%">Shipping Address</td>
                                            <td style="color: #222;">{{$data->shipping_address}}</td>
                                            <td style="width: 20%">Country</td>
                                            <td style="color: #222;">{{$shipping_country1}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">State</td>
                                            <td style="color: #222;"> {{$shipping_state1}}</td>
                                            <td style="width: 20%">City</td>
                                            <td style="color: #222;">{{$shipping_city1}}</td>
                                        </tr>

                                        <tr>
                                            <td style="width: 20%">Postal Code</td>
                                            <td style="color: #222;"> {{$data->shipping_postalcode}}</td>

                                        </tr>
                                        <tr>
                                            <td colspan="4" style="color: #222;font-weight: 600">Description Details</td>

                                        </tr>
                                        <tr>
                                            <td colspan="4" style="color: #222"><?php
                                                echo $data->description;
                                                ?></td>

                                        </tr>
                                    </table>


                                </div>
                                <div id="contact" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-sm-4">
                                        </div>
                                        <div class="col-sm-4">
                                        </div>
                                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                                            <a class="btn btn-primary" href="{{url('client/vendor/contact/add/'.$data->id)}}">Add New</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <iframe style="width: 100%;height: 1000px" src="{{url('client/vendor_list_preview/'.$data->id)}}" frameborder="0"></iframe>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>



                <!-- end row -->


            </div> <!-- container -->

        </div> <!-- content -->



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // Using jQuery.

            $(function() {
                $('form').each(function() {
                    $(this).find('input').keypress(function(e) {
                        // Enter pressed?
                        if(e.which == 10 || e.which == 13) {
                            this.form.submit();
                        }
                    });

                    $(this).find('input[type=submit]').hide();
                });
            });
        </script>
@endsection
