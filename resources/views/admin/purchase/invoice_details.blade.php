@extends('admin.layout.master_material')

@section('title', 'Edit | Purchase Order')

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
                            <h4 class="page-title">Purchase Order </h4>
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


                {{Form::model($data,['method'=>'post','route'=>'post.po_invoice_details_save','role'=>'form','data-parsley-validate novalidate','files'=>'true'])}}
                {{Form::hidden('id',null)}}
             
                <div class="panel">

                    <div class="panel-body">
                        <div class="row">
                        <div class="col-md-4">
                               <div class="form-group">
                               <label class="control-label">Purchase No</label>
                               {{Form::text('purchase_no',null,["readonly",'class'=>'form-control','id'=>"purchase_no"])}}

                            </div>
                        </div>
                        </div>
                        
                        
                         <div class="row">
                        <div class="col-md-4">
                               <div class="form-group">
                               <label class="control-label">Invoice No</label>
                               {{Form::text('invoice_no',null,['class'=>'form-control','id'=>"invoice_no"])}}

                            </div>
                        </div>
                        
                         <div class="col-md-4">
                             <?php
                             if($data->invoice_date)
                             {
                                $date=date('d-m-Y',strtotime($data->invoice_date));
                             }else{
                             $date="";
                             }
                             ?>
                               <div class="form-group">
                               <label class="control-label">Invoice Date</label>
                               {{Form::text('invoice_date',$date,['required','class'=>'form-control input-daterange-datepicker','id'=>"salaesorder_date",'autocomplete'=>'off'])}}

                            </div>
                        </div>
                        
                        <div class="col-md-4">
                               <div class="form-group">
                               <label class="control-label">Invoice File</label>
                               {{Form::file('invoice_file',['class'=>'form-control','id'=>"invoice_file"])}}
                                @if($data->invoice_file)
                                <br>
                               <label> <a target="_blank" href="{{asset('public/po/invoice_file/'.$data->invoice_file)}}">Click to see invoice File</a></label>
                                @endif
                            </div>
                        </div>
                        
                        </div>
                        
                        



               
                <!-- end: page -->

            <!-- end Panel -->
            <div class="row">
                <div class="col-md-12" style="text-align: center;">
                    <button style="text-align: center;" class="btn btn-primary">Submit</button>
                </div>
            </div>
            {{Form::close()}}
            <br>
            <div class="row">
                @if(session()->has('message'))
                <div class="col-sm-12">
                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                        <strong style="color: #fff">{{session()->get('message')}}</strong>
                    </div>
                </div>
                @endif
                
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                    <tr>
                        <th>#</th>
                        <!--<th>TimeStamps</th>-->
                        <th>Purchase No</th><th>Invoice No</th><th>Invoice Date</th><th>Invoice File</th><th>Action</th>
                    </tr>
                <?php
                $srno=0;
                ?>
                @if($preceive)
               
                <tr>
                    <td>1</td>
                    <!--<td>{{$preceive->created_at}}</td>-->
                    <td>{{$preceive->purchase_no}}</td>
                    <td>{{$preceive->invoice_no}}</td>
                    <td>{{$preceive->invoice_date}}</td>
                    <td><a target="_blank" href="{{asset('public/po/invoice_file/'.$preceive->invoice_file)}}">{{$preceive->invoice_file}}</a></td>
                    <td><a href="{{url('po/invoice_file/delete/'.$preceive->id)}}">Delete</a></td>
                </tr>
                @endif
            </table>
                    </div>
                    
                </div>
            </div>
             </div>
             </div>
        </div> <!-- container -->

    </div> <!-- content -->


<!-- MODAL -->

<!-- end Modal -->




<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->


<!-- Right Sidebar -->

<!-- /Right-bar -->


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="{{asset('public/adminpanel/plugins/parsleyjs/parsley.min.js')}}"></script>
<script>
    $( "#salaesorder_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'
        });
</script>

@endsection
