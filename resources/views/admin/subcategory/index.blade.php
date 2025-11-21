@extends('admin.layout.table_master')

@section('title', 'List | Subcategory')

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
                                            <a href="#">SubCategory List </a>
                                        </li>
                                        @can('product_create')
                                            <li style="text-align: right;margin-bottom: 5px">
                                                <a class="btn btn-primary" href="{{route('admin.subcategory.add')}}">Add New</a>
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

                                    <h4 class="m-t-0 header-title"><b>Subcategory List</b></h4>


                                    <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Category Name</th>
                                            <th>Sub Category Name</th>
                                            <th>Image</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $srno=0;
                                            ?>
                                            @foreach($subcategory as $list)
                                            <?php
                                            $srno++;
                                            ?>
                                            <tr>
                                               <td style="width: 5%"> {{($subcategory->currentPage() - 1) * $subcategory->perPage() + $loop->iteration}}</td>
                                               <td style="width: 60%">{{$list->category_name}}</td>
                                               <td style="width: 60%">{{$list->subcategory_name}}</td>
                                               <td style="text-align: center;width: 10%">
                                                   @if(empty($list->subcategory_image))
                                                   @else
                                                   <a target="_blank" href="/subcategory/{{$list->subcategory_image}}">
                                                   <img src="/subcategory/{{$list->subcategory_image}}" height="65px" width="65px" class="img-responsive" width="100px" id="myImg<?=$srno?>" onclick="image_show(<?=$srno?>)">
                                                   </a>
                                                   @endif
                                                  </td>
                                            <td class="actions" style="width: 10%">
                                             @can('product_edit')
                                                    <a href="{{route('admin.subcategory.edit',['id' => $list->id] )}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                             @endcan

                                          @can('product_delete')
                                                     <a href="{{route('admin.subcategory.delete',['id' => $list->id] )}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
                                          @endcan
                                          </td>
                                      </tr>
                                      @endforeach
                                        </tbody>
                                    </table>
                                    {{$subcategory->links()}}
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->
                @endsection
