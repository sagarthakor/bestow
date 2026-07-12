<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- App title -->
    <title>{{Session::get('software_title')}} - Challan List</title>

    <!-- DataTables -->
    @extends('admin.form_header')
    <style type="text/css">
        .listSearchContributor {
            min-height: 28px;
            width: 100%;
            min-width: 100px;
        }
        .inputElement {
            height: 30px;
            width: 100%;
            border-radius: 1px;
            box-shadow: none;
            border: 1px solid #cccccc;
        }
        input[type="text"].inputElement, input[type="password"].inputElement {
            padding: 3px 8px;
        }
        nav{
            float: right;
        }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>

</head>


<body class="fixed-left">

<!-- Begin page -->
<div id="wrapper">

    <!-- Top Bar Start -->

    <!-- Top Bar End -->


    <!-- ========== Left Sidebar Start ========== -->
@include('admin/side_bar')
<!-- Left Sidebar End -->



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
                            <h4 class="page-title">Quotation List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">Zircos</a>
                                </li>

                                <li class="active">
                                    Quotation List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                    </div>
                    @can('delivery_challan_create')
                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{url('challan_add')}}">Add New</a>
                        </div>
                    @endcan
                </div>
                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">

                        <?php
                        if(isset($_GET['msg']))
                        {
                        ?>
                        <div class="alert alert-success">
                            <strong style="color:#000">Stage Change Successfully</strong>
                        </div>
                        <?php

                        }
                        ?>
                        <div class="card-box table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Challan No</th>
                                    <th>Challan Date</th>
                                    <th>Customer</th>
                                    <th>Subject</th>
                                    <th>Stage</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>

                                        {{Form::open(['method'=>'get'])}}
                                        <button>search</button>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['quot_no'])){echo $_GET['quot_no'];} ?>" name="quot_no" placeholder='Quot No' class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['quot_date'])){echo $_GET['quot_date'];} ?>" name="quot_date" placeholder='Quot Date' class="listSearchContributor inputElement" id="start_date" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name" placeholder='Client Name' class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" name="subject" class="listSearchContributor inputElement" value="<?php if(isset($_GET['subject'])){echo $_GET['subject'];} ?>" placeholder='Subject'>
                                    </td>

                                    <td>
                                        <input type="text" name="quot_stage" class="listSearchContributor inputElement" value="<?php if(isset($_GET['quot_stage'])){echo $_GET['quot_stage'];} ?>" placeholder='Quot Stage'>
                                    </td>
                                    <td></form></td>
                                </tr>

                                </thead>


                                <tbody>


                                <?php $srno=0; ?>
                                <?php
                                $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');

                                ?>
                                @foreach($challan as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="width: 2%;vertical-align: top;text-align: center;">
                                            {{($challan->currentPage() - 1) * $challan->perPage() + $loop->iteration}}
                                        </td>
                                        <td   style="vertical-align: top;">
                                            <a href="{{url('challan/view/'.$data->id)}}">
                                                {{$data->challan_number}}
                                            </a>
                                        </td>

                                        <td   style="vertical-align: top;width: 5%">
                                            {{date('d-m-Y',strtotime($data->challan_date))}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td  style="vertical-align: top;width: 25%">{{$data->customer_name}}</td>
                                        <td  style="vertical-align: top;">{{$data->subject}}</td>

                                        <td  style="vertical-align: top;text-align: center;">
                                            <select name="quot_stage" id="quot_stage{{$srno}}" onchange="quot_state_change(this.value,{{$data->id}},{{$srno}})">
                                                <option value="{{$data->challan_stage}}">{{$data->challan_stage}}</option>
                                                @foreach($stage as $s)
                                                    <option>{{$s}}</option>
                                                @endforeach
                                            </select>

                                        </td>

                                        <td  style="vertical-align: top;width: 11%">
                                            @can('delivery_challan_update')
                                                <a class="table-btn" title="edit"  href="{{url('challan_edit/'.$data->id)}}"title="edit"><i class="fa fa-pencil" style="margin:5px;font-size: 8px;"></i></a>
                                            @endcan
                                           @can('delivery_challan_print')
                                                    <a class="table-btn" title="preview" target="_blank" href="{{url('admin/quot_preview/'.$data->id)}}"><i class="fa fa-eye" style="margin:5px;font-size: 8px;"></i></a>
                                           @endcan
                                            <input type="hidden" name="primary_email" id="primary_email{{$srno}}" value="{{$data->primary_email}}">
                                            <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}" value="{{$data->secondary_email}}">

                                            <input type="hidden" name="subject_mail" id="subject_mail{{$srno}}" value="{{$data->subject}}">

                                            <input type="hidden" name="quot_id" id="quot_id{{$srno}}" value="{{$data->id}}">
                                                @can('delivery_challan_print')
                                                    <a class="table-btn" title="pdf"  href="{{url('admin/quot_print/'.$data->id)}}"><i class="fa fa-file-pdf-o" style="margin:5px;font-size: 8px;"></i></a>
                                                @endcan

                                        <!-- <a class="table-btn" title="mail" href="{{url('admin/quot_mail/'.$data->id)}}"><i class="fa fa-envelope" style="margin:10px;font-size: 10px;"></i></a> -->
                                        <!--  <a class="table-btn modalopen" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"  data-id="{{$srno}}" title="mail" href="#">
                                <i class="fa fa-envelope" style="margin:5px;font-size: 8px;"></i>
                              </a> -->

                                            @if($data->so_status=="Y")
                                            @else
                                            <!-- <button class=""> <a class="table-btn" title="sales order" href="{{url('client/sales_order/create/'.$data->id)}}" >Create SO.</a></button> -->
                                            @endif

                                            @can('delivery_challan_delete')
                                                    <a class="table-btn" title="delete"   href="{{url('admin/quotation_delete/'.$data->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash" style="font-size: 8px;"></i></a>
                                            @endcan
                                            {{--                                 <a class="table-btn" title="Followup" href="{{url('admin/quotation/followup/'.$data->id)}}" ><i class="fa fa-comment" style="margin:5px;font-size: 8px;"></i></a>--}}


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$challan->appends(request()->input())->links()}}

                        </div>
                    </div>
                </div>

            </div> <!-- container -->

        </div> <!-- content -->

        @extends('admin.footer')

    </div>


    <!-- ============================================================== -->
    <!-- End Right content here -->
    <!-- ============================================================== -->


    <!-- Right Sidebar -->

    <!-- /Right-bar -->

</div>
<!-- END wrapper -->



@extends('admin.form_fotter')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(".modalopen").click(function(){
        var id=$(this).data("id");
        var primary_email=$("#primary_email"+id).val();
        var secondary_email=$("#secondary_email"+id).val();
        var subject_mail=$("#subject_mail"+id).val();
        var quot_id=$("#quot_id"+id).val();

        $("#to_email").val(primary_email+','+secondary_email);
        $("#qid").val(quot_id);
        $("#to_subject").val(subject_mail);
    });

    ClassicEditor
        .create( document.querySelector( '#to_body' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
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
<script>
    $( function() {
        $( "#start_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'
        });
    } );

    function quot_state_change(stage,quot_id)
    {
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/quot/stage/change',
            data:{stage:stage,quot_id:quot_id},
            method:'get',
            success:function (res)
            {
                window.location="{{url('quotation-list')}}"+'?msg=success';
            }
        });
    }
</script>
</body>

</html>
