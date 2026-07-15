@extends('admin.layout.table_master_material')

@section('title', 'List | Quotation')

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
                                @can('quotation_create')
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{route('admin.quotation.add')}}">Add New</a>
                                    </li>
                                @endcan
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                <strong>{{session()->get('message')}}</strong>
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

                        <div class="card-box">
                            <h4 class="m-t-0 header-title">Filter</h4>
                            {{Form::model(request(),['method'=>'get'])}}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Quot No</label>
                                        <input type="text" value="<?php if(isset($_GET['quot_no'])){echo $_GET['quot_no'];} ?>" name="quot_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="text" value="<?php if(isset($_GET['from_date'])){echo $_GET['from_date'];} ?>" name="from_date" class="form-control" id="from_date" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="text" value="<?php if(isset($_GET['end_date'])){echo $_GET['end_date'];} ?>" name="end_date" class="form-control" id="to_date" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Client Name</label>
                                        <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Subject</label>
                                        <input type="text" name="subject" class="form-control" value="<?php if(isset($_GET['subject'])){echo $_GET['subject'];} ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Amount</label>
                                        <input type="text" name="amount" class="form-control" value="<?php if(isset($_GET['amount'])){echo $_GET['amount'];} ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Stage</label>
                                        <input type="text" name="quot_stage" class="form-control" value="<?php if(isset($_GET['quot_stage'])){echo $_GET['quot_stage'];} ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="hidden-xs">&nbsp;</label>
                                        <button class="btn btn-primary btn-block waves-effect waves-light"><i class="fa fa-search"></i> Search</button>
                                        <!--<button type="submit" name="export_excel" class="btn btn-dribbble" value="export_excel">Export Excel</button>-->
                                        <!--                <button type="submit" name="tally_quotation" class="btn btn-dribbble" value="tally_quotation">Tally Export</button>-->
                                    </div>
                                </div>
                            </div>
                            {{Form::close()}}
                        </div>

                        <div class="card-box table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
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
                                    <th>SO.</th>
                                    <th></th>
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
                                            {{$data->quotation_no}}
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
                                        <td style="width:9%">
                                            @if($data->so_status=="Y")
                                                <i style="color: green" class="fa fa-check" aria-hidden="true">Done</i>
                                            @else
                                                <a  title="sales order" href="{{route('admin.sales.quot.add',['id' => $data->id])}}" ><i class="mdi mdi-cart"></i>Create</a>
                                            @endif
                                        </td>
                                        <td class="actions" style="vertical-align: top;white-space: nowrap;">
                                            @can('quotation_update')
                                                <a title="Edit" href="{{route('admin.quotation.edit',['id' => $data->id])}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                            @endcan
                                            @can('quotation_view')
                                                <a title="View" target="_blank" href="{{route('admin.quotation.preview',['quot_no' => $data->id])}}" class="btn btn-xs btn-info waves-effect"><i class="fa fa-eye"></i> View</a>
                                            @endcan
                                            @can('quotation_print')
                                                <a title="Print" href="{{route('admin.quotation.print',['quot_no' => $data->id])}}" class="btn btn-xs btn-warning waves-effect"><i class="fa fa-print"></i> Print</a>
                                                <a title="Internal Print" href="{{route('admin.quotation.print',['quot_no' => $data->id,'is_internal' => 'yes'])}}" class="btn btn-xs btn-purple waves-effect"><i class="fa fa-print"></i> Internal Print</a>
                                            @endcan
                                            @can('quotation_delete')
                                                <a title="Delete" onclick="return confirm('Are you sure you want to delete this item?');" href="{{route('admin.quotation.delete',['id' => $data->id])}}" class="btn btn-xs btn-danger waves-effect"><i class="fa fa-trash-o"></i> Delete</a>
                                            @endcan


                                            <input type="hidden" name="primary_email" id="primary_email{{$srno}}" value="{{$data->primary_email}}">
                                            <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}" value="{{$data->secondary_email}}">

                                            <input type="hidden" name="subject_mail" id="subject_mail{{$srno}}" value="{{$data->subject}}">

                                            <input type="hidden" name="quot_id" id="quot_id{{$srno}}" value="{{$data->id}}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$list->appends(request()->input())->links()}}

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
                if(stage=="Reviewing")
                {
                    $("#followup").modal();
                    $("#quotid").val(quot_id);
                    //   $("#followup").show();
                }
                if(stage=="QuoteRivision")
                {
                    var appurl="{{url('/')}}";
                    window.location.href=appurl+'/quot-revise/'+quot_id;
                }

                {{--var appurl="{{url('/')}}";--}}
                {{--$.ajax({--}}
                {{--    url:appurl+'/client/quot/stage/change',--}}
                {{--    data:{stage:stage,quot_id:quot_id},--}}
                {{--    method:'get',--}}
                {{--    success:function (res)--}}
                {{--    {--}}
                {{--       window.location="{{url('quotation-list')}}"+'?msg=success';--}}
                {{--    }--}}
                {{--});--}}
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
