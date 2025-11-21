@extends('admin.layout.master')

@section('title', 'Followup Quotation')

@section('sidebar')
    @parent

@endsection

@section('content')
    <style type="text/css">
        .glyphicon {
            position: relative;
            top: 1px;
            display: inline-block;
            font-family: arial !important;
            /* font-style: normal; */
            font-weight: 400;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .ui-widget-header {
            border: 1px solid #e3a1a1 !important;
            background: #cc0000 url({{asset('public/adminpanel/default/assets/images/ui-icons_777777_256x240.png')}}) 50% 50% repeat-x !important;
            color: #000 !important;
            font-weight: bold !important;
        }
    </style>

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Followup Add </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>


                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->



                <div class="row">
                    <div class="col-xs-12">
                        <div class="card-box">

                            <div class="row">

                                <div class="col-xs-12">



                                    <div id="dialog" style="display: none">
                                       Followup Save Successfully
                                    </div>
                                    <div class="row">
                                        {{Form::model($quot,['method'=>'post','route'=>'post.quot_followup_save','files'=>'true'])}}
                                        {{Form::hidden('id',$quot->quotid)}}
                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Quot No</label>
                                                        {{Form::text('quotation_no',null,['readonly','class'=>'form-control'.$errors->first('quotation_no',' error')])}}
                                                        @if($errors->has('quotation_no'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Quot Date</label>
                                                        {{Form::date('quot_date',null,['readonly','class'=>'form-control'.$errors->first('quot_date',' error'),'autocomplete'=>'off'])}}
                                                        @if($errors->has('quot_date'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Customer</label>
                                                        {{Form::text('customer_name',null,['readonly','class'=>'form-control'.$errors->first('customer_name',' error'),'autocomplete'=>'off'])}}
                                                        @if($errors->has('customer_name'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Quot Stage</label>
                                                        {{Form::text('quot_stage',$quot->quot_stage,['readonly','class'=>'form-control'.$errors->first('quot_stage',' error')])}}
                                                        @if($errors->has('quot_stage'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Followup Date</label>
                                                        {{Form::text('followup_date',date('d-m-Y'),['class'=>'form-control'.$errors->first('followup_date',' error'),'autocomplete'=>'off','id'=>'start_date'])}}
                                                        @if($errors->has('followup_date'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Next Followup Date</label>
                                                        {{Form::text('next_followup_date',null,['class'=>'form-control'.$errors->first('next_followup_date',' error'),'autocomplete'=>'off','id'=>'end_date'])}}
                                                        @if($errors->has('next_followup_date'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Remark</label>
                                                        {{Form::textarea('remark',null,['class'=>'form-control'.$errors->first('remark',' error'),'cols'=>"2","rows"=>'2'])}}
                                                        @if($errors->has('remark'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <button class="btn btn-primary">Save</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-box">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                    <table class="table table-striped table-bordered">
                                                        <tr>
                                                            <th>Sr.</th>
                                                            <th>Quot. No.</th>
                                                            <th>Quot Date</th>
                                                            <th>Customer</th>
                                                            <th>Quot Stage</th>
                                                            <th>Followup Date</th>
                                                            <th>Next Followup Date</th>
                                                            <th>Remark</th>
                                                        </tr>
                                                        <?php
                                                            $srno=0;
                                                        ?>
                                                        @foreach($followup as $followup)
                                                            <?php
                                                            $srno++;
                                                            ?>
                                                            <tr>
                                                                <td>{{$srno}}</td>
                                                                <td>{{$followup->quot_no}}</td>
                                                                <td>{{date('d-m-Y',strtotime($followup->quot_date))}}</td>
                                                                <td>{{$followup->customer_name}}</td>
                                                                <td>{{$followup->quot_stage}}</td>
                                                                <td>{{date('d-m-Y h:i:s A',strtotime($followup->followp_time))}}</td>
                                                                <td>{{date('d-m-Y',strtotime($followup->next_follwup_date))}}</td>
                                                                <td>{{$followup->remark}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div><!-- end col-->

            </div>
            <!-- end row -->


        </div> <!-- container -->

    </div> <!-- content -->
    <div class="modal" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="model_close()">&times;</button>
                    <h4 class="modal-title">Quick Create Organization</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Vendor Name</label>
                                <input type="text" class="form-control" name="vendor_name" id="vendor_name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Primary Email</label>
                                <input type="text" class="form-control" name="primary_email" id="primary_email">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Primary Phone</label>
                                <input type="text" class="form-control" name="primary_phone" id="primary_phone">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="vendor_form()" class="btn btn-default">Go to full form</button>
                    <button type="button" onclick="vendor_save()" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" onclick="model_close()">Close</button>
                </div>
            </div>
        </div>
    </div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
<script type="text/javascript">

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
    function add_vendor()
    {
        $("#myModal").show();
    }
    function model_close()
    {
        $("#myModal").hide();
    }
    function vendor_save()
    {
        var primary_phone=$("#primary_phone").val();
        var primary_email=$("#primary_email").val();
        var vendor_name=$("#vendor_name").val();
        var appurl="{{url('/')}}";
        $.ajax({
            url:appurl+'/client/ajax_vendor_save',
            data:{vendor_name:vendor_name,primary_email:primary_email,primary_phone:primary_phone},
            method:'get',
            success:function(res)
            {
                if(res=="1")
                {
                    alert("error in vendor save");
                    $("#myModal").hide();
                }else{
                    $("#vendor").html(res);
                    $("#myModal").hide();
                }
            }
        });
    }
    function vendor_form()
    {
        window.location="{{url('client/vendor/add')}}";
    }
    function gethsn()
    {
        var appurl="{{url('/')}}";
        var material=$("#material").val();
        $.ajax({
            url:appurl+'/client/ajax_get_hsn',
            data:{material:material},
            method:'get',
            success:function(res)
            {
                $("#hsn").val(res);
            }
        });
    }
</script>
@if(session()->has('msg'))
    <script type="text/javascript">
        $(function () {
            $("#dialog").dialog({
                modal: true,
                title: "Success",
                width: 300,
                height: 150,
                open: function (event, ui) {
                    setTimeout(function () {
                        $("#dialog").dialog("close");
                    }, 2000);
                }
            });
        });
    </script>
@endif
<script type="text/javascript">
    $(function () {
        $("#btnShow").click(function () {
            $("#dialog").dialog({
                modal: true,
                title: "jQuery Dialog",
                width: 300,
                height: 150,
                open: function (event, ui) {
                    // setTimeout(function () {
                    //     $("#dialog").dialog("close");
                    // }, 5000);
                }
            });
        });
    });
</script>

@endsection
