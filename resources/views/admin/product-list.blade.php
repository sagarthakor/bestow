@extends('admin.layout.table_master_material')

@section('title', 'List of Products')

@section('sidebar')
    @parent

@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


            {{--                    <div class="row">--}}
            {{--                       <div class="col-xs-12">--}}
            {{--                        <div class="page-title-box1">--}}
            {{--                           <br>--}}
            {{--                            <ol class="breadcrumb p-0 m-0">--}}
            {{--                                <li>--}}
            {{--                                    <a href="{{url('admin')}}">{{Session::get('software_title')}}</a>--}}
            {{--                                </li>--}}

            {{--                                <li class="active">--}}
            {{--                                    Product List--}}
            {{--                                </li>--}}
            {{--                            </ol>--}}
            {{--                            <div class="clearfix"></div>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            <!-- end row -->



                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Product List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="#">Product List </a>
                                </li>

                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    @can('product_create')
                        <div class="col-sm-12" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{route('admin.product.add')}}">Add New</a>
                            {{--<a class="btn btn-primary" href="{{url('client/product/import/')}}">Import</a>
                            <a class="btn btn-primary" href="{{asset('public/yogi-product.xlsx')}}">Download Product Sample File</a>--}}
                        </div>
                    @endcan

                </div>
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
                                            <label>Item Code</label>
                                            <input type="text" value="{{ request('item_code') }}" name="item_code" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <input type="text" value="{{ request('product_name') }}" name="product_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <input type="text" value="{{ request('category') }}" name="category" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Material</label>
                                            <input type="text" value="{{ request('material') }}" name="material" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Usage Unit</label>
                                            <input type="text" value="{{ request('usage_unit') }}" name="usage_unit" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="text" value="{{ request('price') }}" name="price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>GST</label>
                                            <input type="text" value="{{ request('gst') }}" name="gst" class="form-control">
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
                                        <th>Item Code</th>
                                        <th>Product Name</th>
                                        <th>Categrory</th>
                                        <th>Material</th>
                                        <th>Usage Unit</th>
                                        <th>Price</th>
                                        <th>GST</th>

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
                                            <td style="text-align: center"> {{($data->currentPage() - 1) * $data->perPage() + $loop->iteration}}</td>
                                            <td style="text-align: center;">{{$list->item_code}}</td>
                                            <td style="width: 40%"><a href="{{url('client/product/preview/'.$list->id)}}">{{$list->product_name}}</a></td>
                                            <td style="text-align: center;">{{$list->catname}}</td>
                                            <td style="text-align: center;">{{$list->matname}}</td>
                                            <td style="text-align: center;width: 10%">{{$list->uom_name}}</td>
                                            <td style="text-align: center;width: 5%">{{number_format($list->price,2,'.',',')}}</td>
                                            <td style="text-align: center;">{{$list->gst_per}}</td>
                                            <td class="actions" style="width: 8%">
                                                @can('product_update')
                                                    <a style="margin: 5px" href="{{ route('admin.product.edit',['id' => $list->id] )}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                                @can('product_delete')
                                                    <a style="margin: 5px" href="{{ route('admin.product.delete',['id' => $list->id] )}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </form>
                            {{$data->appends(request()->input())->links()}}
                        </div>
                    </div>
                </div>

                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script type="text/javascript">
                    // Using jQuery.

                    $(function() {
                        $('form').each(function() {
                            $(this).find('input').keypress(function(e) {
                                // Enter pressed?
                                if(e.which == 10 || e.which == 13) {
                                    this.form.submit();
                                }
                            });

                            $(this).find('input[type=submit]').hide();
                        });
                    });
                </script>

                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

@endsection
