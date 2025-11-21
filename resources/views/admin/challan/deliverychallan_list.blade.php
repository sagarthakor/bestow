@extends('admin.layout.table_master')

@section('title', 'List | Delivery Challan')

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
                            <h4 class="page-title">Delivery Challan List </h4>
                            <ol class="breadcrumb p-0 m-0">

                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    Delivery Challan
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
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                    </div>

                    @can('delivery_challan_create')
                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{route('admin.deliverychallan.add',['id' => 0])}}">Add New</a>
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

                        <div class="card-box table-responsive">
                            {{Form::open(['method'=>'get'])}}
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Challan No</th>
                                    <th>Challan Date</th>
                                    <th>Client Name</th>
                                    <th>Subject</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Invoice</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['invoice_no'])){echo $_GET['invoice_no'];} ?>" placeholder="Challan No." name="invoice_no" class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="date" value="<?php if(isset($_GET['from_date'])){echo $_GET['from_date'];} ?>" name="from_date" placeholder='From date' class="listSearchContributor inputElement" id="start_date" autocomplete="off">

                                        <input type="date" value="<?php if(isset($_GET['end_date'])){echo $_GET['end_date'];} ?>" name="end_date" placeholder='End Date' class="listSearchContributor inputElement" id="end_date" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name" placeholder="Client Name" class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" name="subject" class="listSearchContributor inputElement" value="<?php if(isset($_GET['subject'])){echo $_GET['subject'];} ?>" placeholder="Subject">
                                    </td>
                                    <td>
                                        <input type="text" name="amount" class="listSearchContributor inputElement" value="<?php if(isset($_GET['amount'])){echo $_GET['amount'];} ?>" placeholder="Amount">
                                    </td>
                                    <td>
                                        <input type="text" name="status" class="listSearchContributor inputElement" value="<?php if(isset($_GET['status'])){echo $_GET['status'];} ?>" placeholder="Status">
                                    </td>
                                    <td></td>
                                    <td style="text-align: center"><button class="btn btn-brown">Search</button></td>

                                </tr>
                                </thead>


                                <tbody>



                                <?php $srno=0; ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td   style="vertical-align: top;">{{$srno}}</td>
                                        <td width="12%"  style="vertical-align: top;">{{$data->challan_number}}</td>

                                        <td width="10%"  style="vertical-align: top;">
                                            {{date('d-m-Y',strtotime($data->invoice_date))}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td  style="vertical-align: top;"> {{$data->customer_name}}</td>
                                        <td  style="vertical-align: top;"> {{$data->subject}}</td>
                                        <td  style="vertical-align: top;width:7%;text-align: left;"> {{number_format($data->grand_total)}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 10%"> {{$data->status}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 10%">
                                            @if($data->invoice=="Y")
                                                <i style="color: green" class="fa fa-check" aria-hidden="true">Done</i>
                                            @else
                                                <a href="{{route('admin.challan.invoice.add',['id' => $data->id])}}">Add</a>
                                            @endif


                                        </td>
                                        <td  style="vertical-align: top;">

                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                   {{-- <li><a title="Duplicate" href="{{url('deliverychallan/duplicate/'.$data->id)}}">Duplicate</a></li>--}}

                                                    @can('delivery_challan_update')
                                                        <li><a title="Edit" href="{{route('admin.challan.edit',['id' => $data->id])}}">Edit</a></li>
                                                    @endcan
                                                    @can('delivery_challan_print')
                                                        <li><a title="Print" href="{{route('admin.challan.print',['id' => $data->id])}}">Print</a></li>
                                                    @endcan
                                                    @can('delivery_challan_delete')
                                                        <li><a title="delete" onclick="return confirm('Are You Sure You want to delete this DC');" href="{{route('admin.challan.delete',['id' => $data->id])}}">Delete</a></li>
                                                    @endcan

                                                </ul>
                                            </div>

                                            <input type="hidden" name="primary_email" id="primary_email{{$srno}}" value="{{$data->primary_email}}">
                                            <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}" value="{{$data->secondary_email}}">

                                            <input type="hidden" name="subjects" id="subjects{{$srno}}" value="{{$data->subject}}">

                                            <input type="hidden" name="quot_id" id="quot_id{{$srno}}" value="{{$data->id}}">

                                        <!--  <a class="table-btn modalopen" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"  data-id="{{$srno}}" title="mail" href="#">
                                    <i class="fa fa-envelope" style="margin:10px;font-size: 10px;"></i>
                                  </a> -->

                                        <!-- <a class="table-btn" title="delete"   href="{{url('deliverychallan/delete/'.$data->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash" style="margin:10px;font-size: 10px;"></i></a> -->
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
            </div> <!-- container -->

        </div> <!-- content -->


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(".modalopen").click(function(){
                var id=$(this).data("id");
                var primary_email=$("#primary_email"+id).val();
                var secondary_email=$("#secondary_email"+id).val();
                var subjects=$("#subjects"+id).val();
                var quot_id=$("#quot_id"+id).val();

                $("#to_email").val(primary_email+','+secondary_email);
                $("#qid").val(quot_id);
                $("#to_subject").val(subjects);
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
@endsection
