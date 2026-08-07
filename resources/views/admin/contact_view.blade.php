@extends('admin.layout.master_material')

@section('title', 'Add Category')

@section('sidebar')
    @parent

@endsection

@section('content')
    <style type="text/css">
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            border-top:0px !important;
        }

    </style>

        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container">


                    <div class="row">
                       <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Contact View</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('client/customer/contact')}}">Contact List </a>
                                </li>
                                <li>
                                    Contact View
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->



                <div class="row">
                    <div class="col-xs-12">
                       <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home">Details</a></li>

                        <ul class="nav navbar-nav navbar-right">
                           @can('customer_contact_create')
                                <li><a href="{{route('admin.contact.edit',['id' => $data->id])}}"><span class="glyphicon glyphicon-user"></span>Edit</a></li>
                           @endcan
                          @can('customer_contact_delete')
                                   <li><a onclick="return confirm('Are you sure you want to delete this item?');" href="{{url('client/contact/delete/'.$data->id)}}"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                          @endcan

                       </ul>
                   </ul>

                   <div class="card-box">

                    <div class="row">
                        @if ($errors->any())
                        <div class="col-xs-12">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                        <div class="tab-content">
                          <div id="home" class="tab-pane fade in active">
                            <div class="col-xs-12">

                                <div class="row justify-content-md-center">
                                    {{Form::model($data,['method'=>'post','route'=>'admin.contact.update','files'=>'true'])}}
                                    {{Form::hidden('id',null)}}
                                    <div class="col-md-12">
                                        <div class="demo-box">

                                           <table class="table table-borderless" style="border:0px !important">
                                            <caption style="color: #222">Contact Details</caption>
                                            <tr>

                                                <td style="width: 20%">Contact Name</td>
                                                <td style="color: #222;">{{$data->contact_name}}</td>
                                            </tr>

                                            <tr>
                                                <td style="width: 20%">Customer </td>
                                                <td style="color: #222;"> {{$data->customer_name}}</td>
                                                <td style="width: 20%">Office Phone</td>
                                                <td style="color: #222;">{{$data->office_phone}}</td>

                                            </tr>
                                            <tr>
                                                <td style="width: 20%">Office Email  </td>
                                                <td style="color: #222;"> {{$data->office_email}}</td>
                                                <td style="width: 20%">Primary Phone</td>
                                                <td style="color: #222;">{{$data->primary_phone}}</td>

                                            </tr>

                                            <tr>
                                                <td style="width: 20%">Primary Email  </td>
                                                <td style="color: #222;"> {{$data->primary_email}}</td>
                                                <td style="width: 20%">Department</td>
                                                <td style="color: #222;">{{$data->department}}</td>

                                            </tr>

                                            <tr>
                                                <td style="width: 20%">Designation  </td>
                                                <td style="color: #222;"> {{$data->designation}}</td>
                                                <td style="width: 20%">Description</td>
                                                <td style="color: #222;">{{$data->description}}</td>

                                            </tr>
                                        </table>
                                    </div>

                                </div>
                                {{Form::close()}}

                            </div><!-- end row -->


                        </div>
                    </div>
                </div>

            </div>
            <!-- end row -->


            <!-- end row -->


        </div> <!-- end card-box -->
    </div><!-- end col-->

</div>
<!-- end row -->


</div> <!-- container -->

</div> <!-- content -->


<script src="{{asset('public/adminpanel/default/assets/js/jquery-1.12.4.js')}}"></script>
<script>
    ClassicEditor
    .create( document.querySelector( '#editor' ) )
    .catch( error => {
        console.error( error );
    } );

    ClassicEditor
    .create( document.querySelector( '#editor2' ) )
    .catch( error => {
        console.error( error );
    } );

    ClassicEditor
    .create( document.querySelector( '#editor3' ) )
    .catch( error => {
        console.error( error );
    } );

    $("#copy_billing").click(function(){

        var billing_city=$("#billing_city").val();
        var billing_address=$("#billing_address").val();
        var billing_state=$("#billing_state").val();
        var billing_city=$("#billing_city").val();
        var billing_country=$("#billing_country").val();
        var billing_postalcode=$("#billing_postalcode").val();


        $("#shipping_city").val(billing_city);
        $("#shipping_address").val(billing_address);
        $("#shipping_state").val(billing_state);
        $("#shipping_city").val(billing_city);
        $("#shipping_country").val(billing_country);
        $("#shipping_postalcode").val(billing_postalcode);

    });

    $("#billing_country").change(function(){
        var country=$("#billing_country").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/get_state',
            data:{country:country},
            method:'get',
            success:function(res)
            {
                $("#billing_state").html(res);
            }
        });
    });

    $("#billing_state").change(function(){
        var state=$("#billing_state").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/get_city',
            data:{state:state},
            method:'get',
            success:function(res)
            {
                $("#billing_city").html(res);
            }
        });
    });

    $("#shipping_country").change(function(){
        var country=$("#shipping_country").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/get_state',
            data:{country:country},
            method:'get',
            success:function(res)
            {
                $("#shipping_state").html(res);
            }
        });
    });

    $("#shipping_state").change(function(){
        var state=$("#shipping_state").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/get_city',
            data:{state:state},
            method:'get',
            success:function(res)
            {
                $("#shipping_city").html(res);
            }
        });
    });
</script>
@endsection
