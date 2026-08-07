@extends('admin.layout.table_master_material')

@section('title', 'List | Customer')

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
                            <h4 class="page-title">Customers List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    Customer
                                </li>
                                <li class="active">
                                    List
                                </li>
                                @can('customer_create')
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{route('admin.customer.add')}}">Add New</a>
                                    </li>
                                @endcan
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                {{Form::open(['method'=>'get','id'=>'formid'])}}
                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                <strong>{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title">Filter</h4>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control" value="<?php if(isset($_GET['customer_name'])){echo $_GET['customer_name'];} ?>" name="customer_name">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Office Phone</label>
                                        <input type="text" name="primary_phone" value="<?php if(isset($_GET['primary_phone'])){echo $_GET['primary_phone'];} ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Office Email</label>
                                        <input type="text" class="form-control" name="primary_email" value="<?php if(isset($_GET['primary_email'])){echo $_GET['primary_email'];} ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Owner Name</label>
                                        <input type="text" class="form-control" name="owner_name" value="<?php if(isset($_GET['owner_name'])){echo $_GET['owner_name'];} ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Owner Phone</label>
                                        <input type="text" class="form-control" name="owner_mobile" value="<?php if(isset($_GET['owner_mobile'])){echo $_GET['owner_mobile'];} ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Owner Email</label>
                                        <input type="text" class="form-control" name="owner_email" value="<?php if(isset($_GET['owner_email'])){echo $_GET['owner_email'];} ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <button class="btn btn-primary waves-effect waves-light"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-box table-responsive">

                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th >#</th>
                                    <th>


                                        Customer Name

                                    </th>
                                    <th>

                                        Office Phone

                                    </th>
                                    <th>

                                        Office Email


                                    </th>
                                    <th>

                                        Owner Name

                                    </th>
                                    <th>

                                        Owner Phone

                                    </th>
                                    <th>

                                        Owner Email


                                    </th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php $srno=0; ?>
                                @foreach($cdata as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="vertical-align: top;text-align:center"> {{($cdata->currentPage() - 1) * $cdata->perPage() + $loop->iteration}}</td>
                                        <td style="vertical-align: top;">
                                            <a href="{{route('admin.customer.preview',['id' => $data->id])}}">{{$data->customer_name}}</a>

                                        </td>

                                        <td  style="vertical-align: top;text-align: center;">
                                            {{$data->primary_phone}}</td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td  style="vertical-align: top;text-align: center;"><a href="mailto:{{$data->primary_email}}">{{$data->primary_email}}</a></td>

                                        <td  style="vertical-align: top;text-align: center;">{{$data->owner_name}}</td>
                                        <td  style="vertical-align: top;text-align: center;">{{$data->owner_mobile}}</td>
                                        <td  style="vertical-align: top;text-align: center;">{{$data->owner_email}}
                                        </td>

                                        <td class="actions" style="vertical-align: top;white-space: nowrap;">
                                            @can('customer_update')
                                                <a href="{{route('admin.customer.edit',['id' => $data->id])}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                            @endcan
                                            @can('customer_delete')
                                                <a href="{{route('admin.customer.delete',['id' => $data->id])}}" class="btn btn-xs btn-danger waves-effect" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</a>
                                            @endcan
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>

                                </tfoot>

                            </table>

                            {{$cdata->appends(request()->input())->links()}}
                            {{Form::close()}}
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // Using jQuery.

            // $(function() {
            //     $('form').each(function() {
            //         $(this).find('input').keypress(function(e) {
            //             // Enter pressed?
            //             if(e.which == 10 || e.which == 13) {
            //                 this.form.submit();
            //             }
            //         });

            //         $(this).find('input[type=submit]').hide();
            //     });
            // });

            $( ".ascc" ).click(function() {
                //alert("sd");
                $( "#formid" ).submit();
            });
        </script>

@endsection
