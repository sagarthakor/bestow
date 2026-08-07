@extends('admin.layout.table_master_material')

@section('title', 'List | Formula')

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
                                    <a href="#">Formula List </a>
                                </li>
                                @can('formula_create')
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{route('admin.production.formula_add')}}">Add New</a>
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
                                <div class="col-sm-12">
                                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                                        <strong style="color: #fff">{{session()->get('message')}}</strong>
                                    </div>
                                </div>
                            @endif

                            <table class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Nos.</th>
                                    <th>Size.</th>
                                    <th>Total Material.</th>

                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td></td>
                                    <td colspan="4">
                                        <form method="get">
                                            <input type="text" class="form-control" name="search"  value="{{ Request::get('search') }}">
                                    </td>
                                    <td><button class="btn btn-primary">Search</button></form> </td>
                                </tr>
                                <?php
                                $srno=0;
                                ?>
                                @foreach($data as $list)

                                    <?php
                                    $srno++;
                                    ?>
                                    <tr>
                                        <td style="width: 5%"> {{$srno}}</td>
                                        <td style="text-align:center"><a  target="_blank" title="View" href="{{url('formula_view/'.$list->id)}}"><x-product-name :name="$list->product_item->product_name ?? ''" :color="$list->product_item->value1 ?? null" :size="$list->product_item->value2 ?? null" /></a></td>
                                        <td style="text-align:center">{{$list->nos}}</td>
                                        <td style="text-align:center">{{$list->size}}</td>
                                        <td style="text-align:center">{{$list->required_qty}}</td>

                                        <td class="actions" style="width: 10%">
                                            @can('formula_update')
                                                <a href="{{route('admin.production.formula_edit',['id' => $list->id])}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                            @endcan
                                            @can('formula_delete')
                                                <a href="{{route('admin.production.formula_delete',['id' => $list->id])}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
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
