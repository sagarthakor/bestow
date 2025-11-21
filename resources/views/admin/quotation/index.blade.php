@extends('admin.layout.table_master')

@section('title', 'List of Quotation')

@section('sidebar')
    @parent

@endsection

@section('content')

    <!-- DataTables -->

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
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    Quotation
                                </li>
                                <li class="active">
                                    List
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
                    @can('quotation_create')
                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{url('quot/add')}}">Add New</a>
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
                            {{Form::model(request(),['method'=>'get'])}}
                            <button type="submit" name="export_excel" class="btn btn-dribbble" value="export_excel">Export Excel</button>

                            <button type="submit" name="tally_quotation" class="btn btn-dribbble" value="tally_quotation">Tally Export</button>

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th> Quot No
                                    </th>

                                    <th>
                                        Quot Date
                                    </th>

                                    <th>
                                        Client Name
                                    </th>

                                    <th>
                                        Subject
                                    </th>

                                    <th>
                                        Amount

                                    </th>
                                    <th>
                                        Stage
                                    </th>
                                    <th>Sales Order</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>

                                        <button>search</button>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['quot_no'])){echo $_GET['quot_no'];} ?>" name="quot_no" placeholder='Quot No' class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="date" value="<?php if(isset($_GET['from_date'])){echo $_GET['from_date'];} ?>" name="from_date" placeholder='From date' class="listSearchContributor inputElement" id="start_date" autocomplete="off">

                                        <input type="date" value="<?php if(isset($_GET['end_date'])){echo $_GET['end_date'];} ?>" name="end_date" placeholder='End Date' class="listSearchContributor inputElement" id="end_date" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name" placeholder='Client Name' class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" name="subject" class="listSearchContributor inputElement" value="<?php if(isset($_GET['subject'])){echo $_GET['subject'];} ?>" placeholder='Subject'>
                                    </td>
                                    <td>
                                        <input type="text" name="amount" style="width:85px;border-radius: 1px;
      box-shadow: none;
      border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" value="<?php if(isset($_GET['amount'])){echo $_GET['amount'];} ?>" placeholder='Amount'>
                                    </td>
                                    <td>
                                        <input type="text" name="quot_stage" class="listSearchContributor inputElement" value="<?php if(isset($_GET['quot_stage'])){echo $_GET['quot_stage'];} ?>" placeholder='Quot Stage'>
                                    </td>
                                    <td></td> <td></form></td>
                                </tr>

                                </thead>


                                <tbody>


                                <?php $srno=0; ?>
                                <?php
                                $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');

                                ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="width: 2%;vertical-align: top;text-align: center;">
                                            {{($list->currentPage() - 1) * $list->perPage() + $loop->iteration}}
                                        </td>
                                        <td   style="vertical-align: top;">
                                            @can('quotation_view')
                                                <a href="{{url('client/quot/normal/view/'.$data->id)}}">{{$data->quotation_no}}</a>
                                            @endcan
                                        </td>

                                        <td   style="vertical-align: top;width: 5%">
                                            {{date('d-m-Y',strtotime($data->quot_date))}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td  style="vertical-align: top;width: 25%">{{$data->customer_name}}</td>
                                        <td  style="vertical-align: top;">{{$data->subject}}</td>
                                        <td  style="vertical-align: top;width:2%;text-align: left;">{{number_format($data->grand_total)}}
                                        </td>
                                        <td  style="vertical-align: top;text-align: center;">
                                            <select name="quot_stages" id="quot_stage{{$srno}}" onchange="quot_state_change(this.value,{{$data->id}},{{$srno}})">
                                                <option value="{{$data->quot_stage}}">{{$data->quot_stage}}</option>
                                                @foreach($stage as $s)
                                                    <option>{{$s}}</option>
                                                @endforeach
                                            </select>

                                        </td>
                                        <td  style="vertical-align: top;width: 15%">
                                            @can('quotation_update')
                                                <a class="table-btn" title="edit"  href="{{url('quot/edit/'.$data->id)}}"title="edit"><i class="fa fa-pencil" style="margin:10px;font-size: 10px;"></i></a>
                                            @endcan
                                            @can('quotation_print')
                                                    <a class="table-btn" title="preview" target="_blank" href="{{url('quot/preview/'.$data->id)}}"><i class="fa fa-eye" style="margin:10px;font-size: 10px;"></i></a>
                                            @endcan

                                            <input type="hidden" name="primary_email" id="primary_email{{$srno}}" value="{{$data->primary_email}}">
                                            <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}" value="{{$data->secondary_email}}">

                                            <input type="hidden" name="subject_mail" id="subject_mail{{$srno}}" value="{{$data->subject}}">

                                            <input type="hidden" name="quot_id" id="quot_id{{$srno}}" value="{{$data->id}}">

                                                @can('quotation_print')
                                                    <a class="table-btn" title="pdf"  href="{{url('quot/print/'.$data->id)}}"><i class="fa fa-file-pdf-o" style="margin:10px;font-size: 10px;"></i></a>
                                                @endcan
                                        <!-- <a class="table-btn" title="mail" href="{{url('admin/quot_mail/'.$data->id)}}"><i class="fa fa-envelope" style="margin:10px;font-size: 10px;"></i></a> -->
                                        <!--  <a class="table-btn modalopen" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"  data-id="{{$srno}}" title="mail" href="#">
                                    <i class="fa fa-envelope" style="font-size: 10px;"></i>
                                  </a> -->

                                            @if($data->so_status=="Y")
                                            @else
                                            <!--  <a class="table-btn" title="sales order" href="{{url('client/sales_order/create/'.$data->id)}}" >Create SO.</a> -->
                                            @endif

                                                @can('quotation_delete')
                                                    <a class="table-btn" title="delete"  href="{{url('admin/quotation_delete/'.$data->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash" style="margin:10px;font-size: 10px;"></i></a>
                                                @endcan
                                            <a class="table-btn" title="Followup" href="{{url('admin/quotation/followup/'.$data->id)}}" ><i class="fa fa-comment" style="margin:5px;font-size: 8px;"></i></a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$list->appends(request()->input())->links()}}

                        </div>
                    </div>
                </div>

                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Mail</h4>
                            </div>
                            <div class="modal-body">
                                {{Form::open(['method'=>'post','route'=>'post.quot_mail_send'])}}
                                <input type="hidden" id="qid" name="qid">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="text" class="form-control" name="to_email" id="to_email">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>CC</label>
                                        <input type="text" class="form-control" name="to_cc" id="to_cc">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <input type="text" class="form-control" name="to_subject" id="to_subject">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Body</label>
                                        <textarea class="form-control" name="to_body" id="to_body">
                              Hello sir/Madam,<br><br>
                              Thank You For doing business with us.<br>please find the quotation and lets me know if any query.
                              <br><br><br>
                              Thanks & Regards
                              <br>
                              {{$company}}
                              <br><br>
                              Sales Team
                            </textarea>

                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button class="btn btn-primary">Send</button>
                                </div>
                                {{Form::close()}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->


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
        <script>
            $( function() {
                $( "#start_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            } );
        </script>
        <script>
            $( function() {
                $( "#end_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            } );

            $( function() {
                $( "#support_expiry_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            } );

            $( function() {
                $( "#support_start_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            } );

            $( function() {
                $( "#support_start_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            } );

            $(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
                $(this).closest(".select2-container").siblings('select:enabled').select2('open');
            });

            // steal focus during close - only capture once and stop propogation
            $('select.select2').on('select2:closing', function (e) {
                $(e.target).data("select2").$selection.one('focus focusin', function (e) {
                    e.stopPropagation();
                });
            });
        </script>
@endsection
