@extends('admin.table_master')

@section('title', 'List of Size')

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
                                    <h4 class="page-title">{{Session::get('software_title')}} </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{Session::get('software_title')}}</a>
                                        </li>
                                        <li class="active">
                                            <a href="#">Size List </a>
                                        </li>
                                        @can('product_create')
                                            <li style="text-align: right;margin-bottom: 5px">
                                                <a class="btn btn-primary" href="{{url('erp/size/add')}}">Add New</a>
                                            </li>
                                        @endcan
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">

                                    <h4 class="m-t-0 header-title"><b>Size List</b></h4>


                                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Size</th>

                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $srno=0;
                                            ?>
                                            @foreach($size as $list)
                                            <?php
                                            $srno++;
                                            ?>
                                            <tr>
                                               <td style="width: 5%"> {{($size->currentPage() - 1) * $size->perPage() + $loop->iteration}}</td>
                                               <td style="width: 60%">{{$list->size}}</td>

                                            <td class="actions" style="width: 10%">
                                                @can('product_update')
                                                    <a href="{{url('erp/size/edit/'.$list->id)}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                                @can('product_delete')
                                                        <a href="{{url('client/size/delete/'.$list->id)}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
                                                @endcan
                                          </td>
                                      </tr>
                                      @endforeach
                                        </tbody>
                                    </table>
                                    {{$size->links()}}
                                </div>
                            </div>
                        </div>



                        <!-- end row -->



                    </div> <!-- container -->

                </div> <!-- content -->

                @endsection
