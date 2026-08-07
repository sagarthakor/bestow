@extends('admin.layout.table_master_material')

@section('title', 'List of Brand')

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
                                    <a href="#">Brand List </a>
                                </li>
                                @can('product_create')
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{route('admin.brand.add')}}">Add New</a>
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

                            @if(session()->has('message'))
                                <div class="alert alert-info">
                                    <strong>{{session()->get('message')}}</strong>
                                </div>
                            @endif
                            <table class="table table-striped table-bordered table-hover dt-responsive nowrap" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Brand Name</th>

                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $srno=0;
                                ?>
                                @foreach($data as $list)
                                    <?php
                                    $srno++;
                                    ?>
                                    <tr>
                                        <td style="width: 5%"> {{($data->currentPage() - 1) * $data->perPage() + $loop->iteration}}</td>
                                        <td style="width: 60%">{{$list->brand_name}}</td>

                                        <td class="actions" style="width: 10%">
                                            @can('product_update')
                                                <a href="{{route('admin.brand.edit',['id' => $list->id] )}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                            @endcan
                                            @can('product_delete')
                                                <a href="{{route('admin.brand.delete',['id' => $list->id] )}}" class="btn btn-xs btn-danger waves-effect" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$data->links()}}
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

@endsection
