@extends('admin.layout.table_master')

@section('title', 'List of Purchase')

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
                    <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                        <a class="btn btn-primary" href="{{url('client/po/add')}}">Add New</a>
                    </div>
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
                                        Subject
                                    </th>
                                    <th>
                                        Amount
                                    </th>
                                    <th>
                                        Status
                                    </th>

                                    <th></th>
                                </tr>
                                <tr>
                                    <td>


                                        <button>search</button>
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['purchase_no'])){echo $_GET['purchase_no'];} ?>" name="purchase_no" class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['po_date'])){echo $_GET['po_date'];} ?>" name="po_date"  class="listSearchContributor inputElement" id="start_date" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name"  class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" name="subject" class="listSearchContributor inputElement" value="<?php if(isset($_GET['subject'])){echo $_GET['subject'];} ?>" >
                                    </td>
                                    <td>
                                        <input type="text" name="amount" style="width:85px;border-radius: 1px;
                    box-shadow: none;
                    border: 1px solid #cccccc;height: 30px;padding: 3px 8px;"   value="<?php if(isset($_GET['amount'])){echo $_GET['amount'];} ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="status" class="listSearchContributor inputElement" value="<?php if(isset($_GET['status'])){echo $_GET['status'];} ?>">
                                    </td>
                                    <td></td>
                                </tr>
                                </thead>


                                <tbody>



                                <?php $srno=0; ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td   style="vertical-align: top;">{{$srno}}</td>
                                        <td width="12%"  style="vertical-align: top;">
                                            <a target="_blank" href="{{url('client/po/view/'.$data->id)}}">{{$data->purchase_no}}</a></td>

                                        <td width="10%"  style="vertical-align: top;">
                                            {{date('d-m-Y',strtotime($data->po_date))}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td  style="vertical-align: top;">{{$data->vendor_name}}</td>
                                        <td  style="vertical-align: top;">{{$data->subject}}</td>
                                        <td  style="vertical-align: top;width:7%;text-align: left;">{{number_format($data->grand_total)}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 10%">{{$data->status}}</td>

                                        <td  style="vertical-align: top;width: 15%">
                                            <a class="table-btn" title="edit"  href="{{url('client/po/edit/'.$data->id)}}" title="edit"><i class="fa fa-pencil" style="margin:10px;font-size: 10px;"></i></a>

                                            <a class="table-btn" title="preview" target="_blank" href="{{url('client/po/preview/'.$data->id)}}">
                                                <i class="fa fa-eye" style="margin:10px;font-size: 10px;"></i>
                                            </a>

                                            <input type="hidden" name="primary_email" id="primary_email{{$srno}}" value="{{$data->primary_email}}">
                                            <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}" value="{{$data->secondary_email}}">

                                            <input type="hidden" name="subject_mail" id="subject_mail{{$srno}}" value="{{$data->subject}}">

                                            <input type="hidden" name="quot_id" id="quot_id{{$srno}}" value="{{$data->id}}">

                                            <a class="table-btn" title="pdf"  href="{{url('client/po/pdf/'.$data->id)}}">
                                                <i class="fa fa-file-pdf-o" style="margin:10px;font-size: 10px;"></i>
                                            </a>
                                        <!-- <a class="table-btn" title="mail" href="{{url('admin/quot_mail/'.$data->id)}}"><i class="fa fa-envelope" style="margin:10px;font-size: 10px;"></i></a> -->
                                            {{--                                  <a class="table-btn modalopen" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"  data-id="{{$srno}}" title="mail" href="#">--}}
                                            {{--                                    <i class="fa fa-envelope" style="font-size: 10px;"></i>--}}
                                            {{--                                  </a>--}}
                                            {{--                                  <a class="table-btn" title="delete"  href="{{url('admin/quotation_delete/'.$data->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash" style="margin:10px;font-size: 10px;"></i></a>--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </form>
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
                                {{Form::open(['method'=>'post','route'=>'post.po_normal_mail_send'])}}
                                <input type="hidden" id="qid" name="poid">
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
                                 Thank You For doing business with us.<br>please find the purchase order and lets me know if any query.
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
</script>
@endsection
