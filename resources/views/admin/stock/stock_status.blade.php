@extends('admin.layout.table_master_material')

@section('title', 'List | Stock')

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
                            <h4 class="page-title">Items Stock </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li class="active">
                                    <a href="#">Stock List </a>
                                </li>
{{--                                @can('stock_create')--}}
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{route('admin.stock.inward.add')}}">Add New</a>
                                    </li>
{{--                                @endcan--}}
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">

                        <div class="card-box">
                            <h4 class="m-t-0 header-title">Filter</h4>
                            <form method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <input type="text" value="{{ request('product_name') }}" name="product_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Qty</label>
                                            <input type="text" value="{{ request('qty') }}" name="qty" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <input type="date" value="{{ request('created_time') }}" name="created_time" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="hidden-xs">&nbsp;</label>
                                        <button class="btn btn-primary btn-block waves-effect waves-light"><i class="mdi mdi-file-find"></i> Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-box table-responsive">
                            <form method="get">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Date</th>

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
                                            <td style="width: 5%">{{($data->currentPage() - 1) * $data->perPage() + $loop->iteration}}</td>
                                            <td>{{$list->product_name}}</td>
                                            <td>{{$list->qty ?? 0}}</td>
                                            <td>{{$list->created_time ?? 0}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </form>
                            {{$data->appends(request()->input())->links()}}
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

@endsection
