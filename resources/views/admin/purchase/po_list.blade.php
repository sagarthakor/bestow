@extends('admin.layout.table_master')

@section('title', 'List | Purchase')

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
                            <h4 class="page-title">Purchase List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li class="active">
                                    Purchase List
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
                    @can('purchase_create')
                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{route('admin.purchase.add')}}">Add New</a>
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
                                    <th>#.</th>
                                    <th>
                                        Purchase No

                                    </th>

                                    <th>
                                        Purchase Date
                                    </th>

                                    <th>

                                        Vendor Name

                                    </th>
                                    <th>

                                        Vendor PO

                                    </th>
                                    <th>
                                        Subject
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                        Status
                                    </th>
                                    <th>
                                        Received
                                    </th>
                                    <th>
                                        P Invoice
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td>


                                    </td>
                                    <td>
                                        <input type="text" value="<?php if (isset($_GET['purchase_no'])) {
                                            echo $_GET['purchase_no'];
                                        } ?>" name="purchase_no" class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['from_date'])){echo $_GET['from_date'];} ?>" name="from_date" id="from_date" class="listSearchContributor inputElement">
                                        <input type="text" value="<?php if(isset($_GET['end_date'])){echo $_GET['end_date'];} ?>" name="end_date" id="to_date" class="listSearchContributor inputElement">
                                    </td>


                                    </td>
                                    <td>
                                        <input type="text" value="<?php if (isset($_GET['client_name'])) {
                                            echo $_GET['client_name'];
                                        } ?>" name="client_name" class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if (isset($_GET['vendor_po'])) {
                                            echo $_GET['vendor_po'];
                                        } ?>" name="vendor_po" class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" name="subject" class="listSearchContributor inputElement"
                                               value="<?php if (isset($_GET['subject'])) {
                                                   echo $_GET['subject'];
                                               } ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="amount" style="width:85px;border-radius: 1px;
                    box-shadow: none;
                    border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" value="<?php if (isset($_GET['amount'])) {
                                            echo $_GET['amount'];
                                        } ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="status" class="listSearchContributor inputElement"
                                               value="<?php if (isset($_GET['status'])) {
                                                   echo $_GET['status'];
                                               } ?>">
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <button class="btn btn-brown">
                                            <i class="mdi mdi-file-find"></i>Search
                                        </button>
                                    </td>
                                </tr>
                                </thead>


                                <tbody>


                                <?php $srno = 0; ?>
                                <?php
                                $totitem = 0;
                                ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr class="parent" id="{{$srno}}">
                                        <td style="vertical-align: top;text-align:center">{{$srno}}
                                        <!--<span class="btn btn-default {{$srno}}">Show</span>-->
                                        </td>
                                        <td width="12%" style="vertical-align: top;">{{$data->purchase_no}}</td>

                                        <td width="10%" style="vertical-align: top;">
                                            {{date('d-m-Y',strtotime($data->po_date))}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td style="vertical-align: top;">{{$data->vendor_name}}</td>
                                        <td style="vertical-align: top;">{{$data->vendor_po}}</td>
                                        <td style="vertical-align: top;">{{$data->subject}}</td>
                                        <td style="vertical-align: top;width:7%;text-align: left;">
                                            {{number_format($data->grand_total)}}
                                        </td>
                                        <td style="vertical-align: top;text-align: center;width: 10%">{{$data->status}}</td>
                                        <td style="vertical-align: top;text-align: center;width: 10%">
                                            <?php
                                            $poitemsum=0;
                                            $poitem=\App\purchase_item::select("purchase_item.qty","product.status")
                                                ->leftJoin('product','product.id',"purchase_item.product")
                                                ->where(['purchase_item.pono' =>$data->purchase_no])
                                                ->get();
                                            foreach($poitem as $pitem){
                                                if($pitem->status !="service"){
                                                    $poitemsum=$poitemsum+$pitem->qty;
                                                }
                                            }
                                            $poreceiveditem=\App\purchase_receive_item::where(['purchase_no' =>$data->purchase_no])->sum('qty_received');
                                            ?>
                                            @if($poitemsum==$poreceiveditem)
                                                <i style="color: green" class="fa fa-check" aria-hidden="true">Done</i>
                                            @else
                                                <a href="{{route('admin.inward.add',['po_id' => $data->id] )}}">Add</a>
                                            @endif
                                        </td>
                                        <td style="vertical-align: top;text-align: center;width: 10%"><a href="{{route('admin.po.invoice',['id' => $data->id] )}}" data-id="{{$data->id}}">
                                                @if($data->invoice_file=="")<span>Invoice</span> @else <span style="color:green">Invoice</span> @endif </a></td>
                                        <td style="vertical-align: top;">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle" type="button"
                                                        data-toggle="dropdown">Action
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    @can('purchase_update')
                                                       {{-- <li><a title="Edit" href="{{url('client/po_duplicate/'.$data->id)}}">Duplicate</a> </li>--}}
                                                        <li><a title="Edit" href="{{route('admin.purchase.edit',['id' => $data->id] )}}">Edit</a></li>
                                                    @endcan
                                                    @can('purchase_print')
                                                        {{--<li><a title="View" target="_blank" href="{{route('admin.purchase.preview',['id' => $data->id] )}}">View</a></li>--}}
                                                        <li><a title="Print"  target="_blank" href="{{route('admin.purchase.print',['id' => $data->id] )}}">Print</a></li>
                                                    @endcan
                                                    @can('purchase_delete')
                                                        <li><a title="Delete" onclick="return confirm('Are You Sure You want to delete this po');" href="{{route('admin.purchase.delete',['id' => $data->id] )}}">Delete</a>
                                                        </li>
                                                    @endcan

                                                <!--<a class="table-btn" title="edit"  href="{{url('client/po/edit/'.$data->id)}}" title="edit"><i class="fa fa-pencil" style="margin:10px;font-size: 10px;"></i></a>-->

                                                <!--<a class="table-btn" title="preview" target="_blank" href="{{url('client/po/preview/'.$data->id)}}">-->
                                                    <!--    <i class="fa fa-eye" style="margin:10px;font-size: 10px;"></i>-->
                                                    <!--</a>-->

                                                    <input type="hidden" name="primary_email" id="primary_email{{$srno}}"
                                                           value="{{$data->primary_email}}">
                                                    <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}"
                                                           value="{{$data->secondary_email}}">

                                                    <input type="hidden" name="subject_mail" id="subject_mail{{$srno}}"
                                                           value="{{$data->subject}}">

                                                    <input type="hidden" name="quot_id" id="quot_id{{$srno}}"
                                                           value="{{$data->id}}">

                                                <!--<a class="table-btn" title="pdf"  href="{{url('client/po/pdf/'.$data->id)}}">-->
                                                    <!--    <i class="fa fa-file-pdf-o" style="margin:10px;font-size: 10px;"></i>-->
                                                    <!--</a>-->
                                                <!-- <a class="table-btn" title="mail" href="{{url('admin/quot_mail/'.$data->id)}}"><i class="fa fa-envelope" style="margin:10px;font-size: 10px;"></i></a> -->
                                            {{-- <a class="table-btn modalopen" class="btn btn-info btn-lg"
                                                    data-toggle="modal" data-target="#myModal" data-id="{{$srno}}"
                                                    title="mail" href="#">--}}
                                            {{-- <i class="fa fa-envelope" style="font-size: 10px;"></i>--}}
                                            {{-- </a>--}}
                                            {{-- <a class="table-btn" title="delete"
                                                    href="{{url('admin/quotation_delete/'.$data->id)}}"
                                                    onclick="return confirm('Are you sure you want to delete this item?');"><i
                                                    class="fa fa-trash" style="margin:10px;font-size: 10px;"></i></a>--}}
                                        </td>
                                    </tr>
                                    <!--@if(count($receive_detail) < 1)-->
                                    <!--@else-->
                                    <!--    <tr>-->

                                    <!--        <td colspan="10" >-->
                                    <!--            <table class="table table-bordered">-->
                                    <!--                @foreach($receive_detail as $recv)-->
                                    <!--                @if($recv->purchase_no == $data->purchase_no)-->
                                    <!--                <tr class="child-{{$srno}}">-->
                                    <!--                    <td colspan="9" style="font-weight:800">-->
                                    <!--                        Receive Date  : @if($recv->receive_date){{date('d-m-Y',strtotime($recv->receive_date))}}@endif-->
                                    <!--                        <br>-->
                                    <!--                        Notes : {{$recv->note}}-->
                                    <!--                        <br>-->
                                    <!--                         @if($recv->invoice_no)-->
                                    <!--                         Invoice No : {{$recv->invoice_no}}-->
                                    <!--                         @endif-->
                                    <!--                         <br>-->
                                    <!--                         @if($recv->invoice_date)-->
                                    <!--                         Invoice Date : {{date('d-m-Y',strtotime($recv->invoice_date))}}-->
                                    <!--                         @endif-->
                                    <!--                         <br>-->
                                    <!--                         @if($recv->invoice_file)-->
                                    <!--                            Invoice File : <a target="_blank" href="{{$recv->invoice_file}}">{{$recv->invoice_file}}</a>-->
                                    <!--                         @endif-->

                                    <!--                            </td>-->
                                    <!--                </tr>-->


                                    <!--                <tr class="child-{{$srno}}">-->
                                    <!--                    <th>#</th><th colspan="5">Product Name</th><th>Order Qty</th>-->
                                    <!--                    <th>Received Qty</th><th>Remaining Qty</th>-->
                                    <!--                </tr>-->
                                    <!--    @foreach($receive_item as $receive)-->



                                    <!--    @if($receive->purchase_receive_id == $recv->id)-->
                                    <?php
                                    //   $totitem++;
                                    ?>


                                    <!--                <tr class="child-{{$srno}}">-->
                                    <!--                    <td style="text-align:center">{{$totitem}}</td>-->
                                    <!--                    <td colspan="5" style="text-align:center">{{$receive->product_name}}</td>-->
                                    <!--                    <td style="text-align:center">{{$receive->order_qty}}</td>-->
                                    <!--                    <td style="text-align:center">{{$receive->qty_received}}</td>-->
                                    <!--                    <td style="text-align:center">{{$receive->remain_qty}}</td>-->
                                    <!--                </tr>-->


                                    <!--    @endif-->

                                    <!--    @endforeach-->

                                    <!--                @endif-->

                                    <!--                @endforeach-->

                                    <!--    </table>-->
                                    <!--    </td>-->
                                    <!--</tr>-->
                                    <?php
                                    $totitem=0;
                                    ?>
                                    <!--    @endif-->

                                    @endforeach

                                </tbody>
                            </table>
                            </form>
                            {{$list->appends(request()->input())->links()}}

                        </div>
                    </div>
                </div>

               {{-- <div id="invoice" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Invoice</h4>
                            </div>
                            <div class="modal-body">
                                {{Form::open(['method'=>'post','route'=>'post.quot_followup_save','files'=>'true'])}}

                                <input type="hidden" id="quotid" name="quotid">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Invoice Date</label>
                                        {{Form::date('followup_date',date('Y-m-d'),['class'=>'form-control'.$errors->first('invoice_date',' error'),'autocomplete'=>'off','id'=>'followups_date'])}}
                                        @if($errors->has('followup_date'))
                                            <p class="help-block">This field is required</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Invoice No</label>
                                        {{Form::text('invoice_no',null,['class'=>'form-control'.$errors->first('invoice_no',' error'),'autocomplete'=>'off','id'=>'invoice_no'])}}
                                        @if($errors->has('invoice_no'))
                                            <p class="help-block">This field is required</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>File</label>
                                        {{Form::file('invoice_file',['class'=>'form-control'.$errors->first('remark',' error')])}}
                                        @if($errors->has('invoice_file'))
                                            <p class="help-block">This field is required</p>
                                        @endif
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
                </div>--}}

                <!-- end row -->


            </div> <!-- container -->

        </div> <!-- content -->


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
        $(".modalopen").click(function () {
            var id = $(this).data("id");
            var primary_email = $("#primary_email" + id).val();
            var secondary_email = $("#secondary_email" + id).val();
            var subject_mail = $("#subject_mail" + id).val();
            var quot_id = $("#quot_id" + id).val();

            $("#to_email").val(primary_email + ',' + secondary_email);
            $("#qid").val(quot_id);
            $("#to_subject").val(subject_mail);
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

       $(function() {
  $('tr.parent td span.btn')
    .on("click", function(){

    var idOfParent = $(this).parents('tr').attr('id');
    //alert(idOfParent);
    $('tr.child-'+idOfParent).toggle('slow');

    var textbtn=$('.'+idOfParent).text();
   if(textbtn=="Show")
   {
       //alert("hide");
       $('.'+idOfParent).text("Hide");
   }
   if(textbtn=="Hide")
   {
       //alert("show");
       $('.'+idOfParent).text("Show");
   }

  });
  $('tr[class^=child-]').hide().children('td');

});
    </script>
        <script>
        $(function () {
            $("#start_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'
            });
        });
    </script>
@endsection
