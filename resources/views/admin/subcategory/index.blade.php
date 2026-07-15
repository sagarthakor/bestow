@extends('admin.layout.table_master_material')

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

                                    <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Category</th>
                                            <th>Subcategory</th>
                                            <th>Image</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @forelse($subcategory as $list)
                                            <tr>
                                                <td style="width: 5%">
                                                    {{ ($subcategory->currentPage() - 1) * $subcategory->perPage() + $loop->iteration }}
                                                </td>

                                                <td style="width: 25%">{{ $list->category_name }}</td>

                                                <td style="width: 35%">{{ $list->subcategory_name }}</td>

                                                <td style="text-align: center;width: 10%">
                                                    @if(!empty($list->subcategory_image))
                                                        <a target="_blank" href="{{ asset('subcategory/'.$list->subcategory_image) }}">
                                                            <img src="{{ asset('subcategory/'.$list->subcategory_image) }}" height="65px" width="65px">
                                                        </a>
                                                    @endif
                                                </td>

                                                <td class="actions" style="width: 10%">
                                                    @can('product_update')
                                                        <a href="{{ route('admin.subcategory.edit', $list->id) }}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                                    @endcan

                                                    @can('product_delete')
                                                        <a href="{{ route('admin.subcategory.delete', $list->id) }}" class="btn btn-xs btn-danger waves-effect" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No subcategories found.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    {{ $subcategory->links() }}

                                </div>

                            </div>
                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->
                @endsection
