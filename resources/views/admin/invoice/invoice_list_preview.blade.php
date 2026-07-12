@extends('admin.table_header_script')
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
</style>
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
                    <th>Invoice No</th>
                    <th>Invoice Date</th>
                    <th>Client Name</th>
                    <th>Subject</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="text" value="<?php if(isset($_GET['invoice_no'])){echo $_GET['invoice_no'];} ?>" placeholder="Invoice No." name="invoice_no" class="listSearchContributor inputElement">
                    </td>
                    <td>
                        <input type="date" value="<?php if(isset($_GET['invoice_date'])){echo $_GET['invoice_date'];} ?>" placeholder="Invoice Date" name="invoice_date" class="listSearchContributor inputElement">
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
                    <td><button class="btn btn-brown"><i class="mdi mdi-file-find"></i>Search</button></td>

                </tr>
                </thead>


                <tbody>



                <?php $srno=0; ?>
                @foreach($list as $data)
                    <?php $srno++; ?>
                    <tr>
                        <td   style="vertical-align: top;">{{$srno}}</td>
                        <td width="12%"  style="vertical-align: top;">
                            <a target="_blank" href="{{url('invoice/view/'.$data->id)}}">{{$data->invoice_number}}</a></td>

                        <td width="10%"  style="vertical-align: top;">
                            {{date('d-m-Y',strtotime($data->invoice_date))}}
                        </td>

                        <!--      <td  style="vertical-align: top;"></td>
                         <td  style="vertical-align: top;"></td> -->
                        <td  style="vertical-align: top;"> {{$data->customer_name}}</td>
                        <td  style="vertical-align: top;"> {{$data->subject}}</td>
                        <td  style="vertical-align: top;width:7%;text-align: left;"> {{number_format($data->grand_total)}}</td>
                        <td  style="vertical-align: top;text-align: center;width: 10%"> {{$data->status}}</td>
                        <td  style="vertical-align: top;">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    @can('invoice_update')
                                        <li>
                                            <a target="_blank" href="{{url('invoice/edit/'.$data->id)}}" title="edit">
                                                Edit
                                            </a>
                                        </li>
                                    @endcan
                                    @can('invoice_print')
                                        <li>
                                            <a  target="_blank" href="{{url('invoice/preview/'.$data->id)}}" title="View">
                                                View
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="{{url('invoice/print/'.$data->id)}}" title="Print">
                                                Print
                                            </a>
                                        </li>
                                    @endcan
                                    @can('invoice_delete')
                                        <li>
                                            <a target="_blank" href="{{url('invoice/delete/'.$data->id)}}" title="Print">
                                                Delete
                                            </a>
                                        </li>
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
                        <textarea class="form-control" name="to_body" id="to_body"></textarea>

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
@extends('admin.table_footer_script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $(".modalopen").click(function(){
        //alert("ds");
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
