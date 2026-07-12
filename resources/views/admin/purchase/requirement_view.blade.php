@extends('admin.layout.table_master')

@section('title', 'Required Material')

@section('sidebar')
    @parent

@endsection

@section('content')


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
                            <h4 class="page-title">Purchase Requirement </h4>
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
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                            @if(session()->has('message'))
                                <div class="col-sm-12">
                                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                                        <strong style="color: #fff">{{session()->get('message')}}</strong>
                                    </div>
                                </div>
                            @endif
                            {{Form::model($list,['method'=>'post','route'=>'admin.requirement.add'])}}
                            {{Form::hidden("id",null)}}
                                <div class="col-md-4">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Order No</td><td>{{$list->order_no}}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Date</td><td>{{$list->timestamp}}</td>
                                    </tr>
                                    <tr>
                                        <td>Order Request Create By</td><td>{{$list->first_name}} {{$list->last_name}}</td>
                                    </tr>
                                </table>
                            </div>
                                <div class="row">

                                <div class="col-md-8">
                                    @if($status==1)
                                        <div class="col-md-12 alert alert-info">
                                            <div class="col-md-8">
                                            <strong>Purchase Done</strong>
                                            </div>
                                            <div class="col-md-4">
                                                <strong>Purchase No : {{$list->po_no}}</strong>
                                            </div>
                                        </div>

                                    @endif
                                    <div class="row">
                                        <div class="col-md-8">
                                        <h3>Required Raw Material for Production</h3>
                                        </div>
                                        @if($status==0)
                                        <div class="col-md-4">
                                        <button class="btn btn-primary pull-right">Add to Purchase</button>
                                        </div>
                                       @endif
                                       <div class="col-md-12">
                                           <div class="form-group">
                                               <label>Vendor</label>
                                               {{Form::select("vendor",$vendor,null,["required",'class'=>'form-control js-example-basic-single product'])}}
                                           </div>
                                       </div>
                                    </div>

                                    <table class="table table-bordered">
                                        <tr>
                                            <th>#.</th>
                                            <th >Raw Material</th>
                                            <th colspan="2">Qty</th>

                                        </tr>
                                        <?php
                                        $srno=0;
                                        ?>
                                        @foreach($item as $ilist)
                                            <?php
                                            $srno++;
                                            ?>
                                            <tr>
                                                <td>{{$srno}}</td>
                                                <td>{{$ilist->product_name}}</td>
                                                <td>{{$ilist->qty}}</td>
                                                <td>{{$ilist->uom_name}}</td>
                                            </tr>
                                        @endforeach

                                    </table>
                                </div>
                                </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->
        <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function () {
                $('.js-example-basic-single').select2();
            });
</script>
@endsection
