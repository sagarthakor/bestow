@extends('admin.layout.table_master')

@section('title', 'List of Salesorder')

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
                            <h4 class="page-title">Order List </h4>
                            <ol class="breadcrumb p-0 m-0">

                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    Order
                                </li>
                                <li class="active">
                                    List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">

                        <div class="card-box table-responsive">
                            {{Form::open(['method'=>'get'])}}
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Order No.</th>

                                    <th>Order Date</th>

                                    <th>Client Name</th>

{{--                                    <th>Subject</th>--}}
{{--                                    <th>Amount</th>--}}
{{--                                    <th>Status</th>--}}
                                    <th>Quotation</th>
                                    <th>Payment Status</th>
{{--                                    <th>Sales Order</th>--}}
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>

                                    </td>
                                    <td>
                                        <input type="text" value="<?php if (isset($_GET['salaesorder_no'])) {
                                            echo $_GET['salaesorder_no'];
                                        } ?>" placeholder="Salesorder No." name="salaesorder_no"
                                               class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="date" value="<?php if (isset($_GET['salesorder_date'])) {
                                            echo $_GET['salesorder_date'];
                                        } ?>" placeholder="salesorder_date Date" name="salesorder_date"
                                               class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if (isset($_GET['client_name'])) {
                                            echo $_GET['client_name'];
                                        } ?>" name="client_name" placeholder="Client Name"
                                               class="listSearchContributor inputElement">
                                    </td>
{{--                                    <td>--}}
{{--                                        <input type="text" name="subject" class="listSearchContributor inputElement"--}}
{{--                                               value="<?php if (isset($_GET['subject'])) {--}}
{{--                                                   echo $_GET['subject'];--}}
{{--                                               } ?>" placeholder="Subject">--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <input type="text" name="amount" class="listSearchContributor inputElement"--}}
{{--                                               value="<?php if (isset($_GET['amount'])) {--}}
{{--                                                   echo $_GET['amount'];--}}
{{--                                               } ?>" placeholder="Amount">--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        <input type="text" name="status" class="listSearchContributor inputElement"--}}
{{--                                               value="<?php if (isset($_GET['status'])) {--}}
{{--                                                   echo $_GET['status'];--}}
{{--                                               } ?>" placeholder="Status">--}}
{{--                                    </td>--}}
                                    <td></td><td></td>
{{--                                    <td></td>--}}
                                    <td><button>Search</button></td>
                                </tr>
                                </thead>


                                <tbody>


                                <?php $srno = 0; ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="vertical-align: top;text-align: center;width: 5%;">{{$srno}}</td>
                                        <td width="15%" style="vertical-align: top;">
                                            <a href="{{url('order/view/'.$data->id)}}">{{$data->order_number}}</a>
                                        </td>

                                        <td style="vertical-align: top;text-align: center;width: 5%;">
                                            {{date('d-m-Y',strtotime($data->order_date))}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td style="vertical-align: top;"> {{$data->customer_name}}</td>
{{--                                        <td style="vertical-align: top;"> {{$data->subject}}</td>--}}
{{--                                        <td style="vertical-align: top;width:7%;text-align: left;"> {{number_format($data->grand_total)}}</td>--}}
{{--                                        <td style="vertical-align: top;text-align: center;width: 10%"> {{$data->status}}</td>--}}

                                        <td style="vertical-align: top;text-align: center;width: 10%">
                                            @if($data->quot_status=="Y")
                                                <i style="color: green" class="fa fa-check" aria-hidden="true">Done</i>
                                            @else
                                                <a href="{{url("order/quotation/add/".$data->id)}}">Add</a>
                                            @endif
                                        </td>
                                        <td style="vertical-align: top;"> {{$data->payment}}</td>
{{--                                        <td style="vertical-align: top;text-align: center;width: 10%">--}}
{{--                                            @if($data->invoice_status=="Y")--}}
{{--                                                <i style="color: green" class="fa fa-check" aria-hidden="true">Done</i>--}}
{{--                                            @else--}}
{{--                                                <a href="{{url("order/so/add/".$data->id)}}">Add</a>--}}
{{--                                            @endif--}}
{{--                                        </td>--}}
                                        <td style="vertical-align: top;">
                                            @can('quotation_update')
                                                <a class="table-btn" title="edit"
                                                   href="{{url('order/edit/'.$data->id)}}" title="edit"><i
                                                        class="fa fa-pencil"
                                                        style="margin:10px;font-size: 10px;"></i></a>
                                            @endcan


                                            <input type="hidden" name="primary_email" id="primary_email{{$srno}}"
                                                   value="{{$data->primary_email}}">
                                            <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}"
                                                   value="{{$data->secondary_email}}">

                                            <input type="hidden" name="subjects" id="subjects{{$srno}}"
                                                   value="{{$data->subject}}">

                                            <input type="hidden" name="quot_id" id="quot_id{{$srno}}"
                                                   value="{{$data->id}}">

                                        <!--  <a class="table-btn modalopen" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"  data-id="{{$srno}}" title="mail" href="#">
                                    <i class="fa fa-envelope" style="margin:10px;font-size: 10px;"></i>
                                  </a> -->

                                        <!-- <a class="table-btn" title="delete"   href="{{url('admin/quotation_delete/'.$data->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash" style="margin:10px;font-size: 10px;"></i></a> -->
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{Form::close()}}
                            {{$list->links()}}

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
                                {{Form::open(['method'=>'post','route'=>'post.so_normal_mail_send'])}}
                                <input type="hidden" id="qid" name="salesid">
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
Thank You For doing business with us.<br>please find the Sales Order and lets me know if any query.
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
            $(".modalopen").click(function () {
                var id = $(this).data("id");
                var primary_email = $("#primary_email" + id).val();
                var secondary_email = $("#secondary_email" + id).val();
                var subjects = $("#subjects" + id).val();
                var quot_id = $("#quot_id" + id).val();

                $("#to_email").val(primary_email + ',' + secondary_email);
                $("#qid").val(quot_id);
                $("#to_subject").val(subjects);
            });

            ClassicEditor
                .create(document.querySelector('#to_body'))
                .catch(error => {
                    console.error(error);
                });
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // Using jQuery.

            $(function () {
                $('form').each(function () {
                    $(this).find('input').keypress(function (e) {
                        // Enter pressed?
                        if (e.which == 10 || e.which == 13) {
                            this.form.submit();
                        }
                    });

                    $(this).find('input[type=submit]').hide();
                });
            });
        </script>
@endsection
