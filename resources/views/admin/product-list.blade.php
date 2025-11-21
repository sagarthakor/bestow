@extends('admin.layout.table_master')

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

                                    <tr>
                                        <td></td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="item_code" placeholder="Item Code" value="@if(isset($_GET['item_code'])){{$_GET['item_code']}}@endif">
                                        </td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="product_name" placeholder="Product Name" value="@if(isset($_GET['product_name'])){{$_GET['product_name']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="category" placeholder="Categrory" value="@if(isset($_GET['category'])){{$_GET['category']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="material" placeholder="Material" value="@if(isset($_GET['material'])){{$_GET['material']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" style="width:85px;border-radius: 1px;
                                            box-shadow: none;
                                            border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" name="usage_unit" placeholder="Unit" value="@if(isset($_GET['usage_unit'])){{$_GET['usage_unit']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" style="width:85px;border-radius: 1px;
                                            box-shadow: none;
                                            border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" name="price" placeholder="Price" value="@if(isset($_GET['price'])){{$_GET['price']}}@endif">
                                        </td>
                                        <td>
                                            <input type="text" class="" style="width:35px;border-radius: 1px;
                                            box-shadow: none;
                                            border: 1px solid #cccccc;height: 30px;padding: 3px 8px;" name="gst" placeholder="GST" value="@if(isset($_GET['gst'])){{$_GET['gst']}}@endif">
                                        </td>
                                        <td><button>Search</button></td>
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
