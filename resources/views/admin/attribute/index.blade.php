@extends('admin.layout.table_master_material')

@section('title', 'List | Attribute')

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
                                            <a href="#">Attributes List </a>
                                        </li>
                                        @can('product_create')
                                            <li style="text-align: right;margin-bottom: 5px">
                                                <a class="btn btn-primary" href="{{route('admin.attribute.add')}}">Add New</a>
                                            </li>
                                        @endcan
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                        <!-- end row -->






                        <div class="row">
                            @if(session()->has('message'))
                            <div class="col-sm-12">
                                <div class="alert alert-info">
                                    <strong>{{session()->get('message')}}</strong>
                                </div>
                            </div>
                            @endif
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">

                                    <h4 class="m-t-0 header-title"><b>Attributes List</b></h4>


                                    <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Attribute Name</th>

                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $srno=0;
                                            ?>
                                            @foreach($list as $lst)
                                            <?php
                                            $srno++;
                                            ?>
                                            <tr>
                                               <td style="width: 5%"> {{($list->currentPage() - 1) * $list->perPage() + $loop->iteration}}</td>
                                               <td style="width: 60%">{{$lst->attribute_name}}</td>

                                            <td class="actions" style="width: 10%">
                                              @can('product_update')
                                                    <a href="{{route('admin.attribute.edit',['id' => $lst->id])}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                              @endcan
                                              @can('product_delete')
                                                      <a href="{{route('admin.attribute.delete',['id' => $lst->id])}}" class="btn btn-xs btn-danger waves-effect" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</a>
                                              @endcan

                                          </td>
                                      </tr>
                                      @endforeach
                                        </tbody>
                                    </table>
                                    {{$list->links()}}
                                </div>
                            </div>
                        </div>



                        <!-- end row -->



                    </div> <!-- container -->

                </div> <!-- content -->

                @endsection
