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
                                <div class="card-box">

                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered align-middle mb-0">
                                            <thead class="thead-light">
                                            <tr>
                                                <th style="width:5%">#</th>
                                                <th>Category</th>
                                                <th>Subcategory</th>
                                                <th class="text-center" style="width:12%">Image</th>
                                                <th class="text-center" style="width:15%">Actions</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @forelse($subcategory as $list)
                                                <tr>
                                                    <td class="fw-semibold">
                                                        {{ ($subcategory->currentPage() - 1) * $subcategory->perPage() + $loop->iteration }}
                                                    </td>

                                                    <td>
                            <span class="text-dark fw-semibold">
                                {{ $list->category_name }}
                            </span>
                                                    </td>

                                                    <td>
                                                        {{ $list->subcategory_name }}
                                                    </td>

                                                    <td class="text-center">
                                                        @if(!empty($list->subcategory_image))
                                                            <a target="_blank" href="{{ asset('subcategory/'.$list->subcategory_image) }}">
                                                                <img src="{{ asset('subcategory/'.$list->subcategory_image) }}"
                                                                     style="height:60px;width:60px;object-fit:cover;"
                                                                     class="rounded shadow-sm border">
                                                            </a>
                                                        @else
                                                            <span class="badge badge-secondary">No Image</span>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">

                                                        @can('product_update')
                                                            <a href="{{ route('admin.subcategory.edit', $list->id) }}"
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        @endcan

                                                        @can('product_delete')
                                                            <a href="{{ route('admin.subcategory.delete', $list->id) }}"
                                                               class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Are you sure you want to delete this item?');">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        @endcan

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        No subcategories found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        {{ $subcategory->links() }}
                                    </div>

                                </div>

                            </div>
                        </div>
                        <!-- end row -->
                    </div> <!-- container -->
                </div> <!-- content -->
                @endsection
