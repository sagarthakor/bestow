@extends('admin.layout.table_master')

@section('title', 'List of BOM')

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
                            <h4 class="page-title">BOM List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}} </a>
                                </li>

                                <li class="active">
                                    BOM List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->




                <div class="row">
                 <div class="col-sm-4">
                 </div>
                 <div class="col-sm-4">
                 </div>
                @can('product_create')
                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{url('client/bom/add/')}}">Add New</a>
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
                                        <th>BOM Name</th>

                                        <th>Price</th>
                                        <th>Created Time</th>

                                    </tr>

                                    <tr>
                                        <td><button>Search</button></td>
                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="bom_name" value="@if(isset($_GET['bom_name'])){{$_GET['bom_name']}}@endif">
                                        </td>

                                        <td>
                                            <input type="text" class="listSearchContributor inputElement" name="price" placeholder="price" value="@if(isset($_GET['price'])){{$_GET['price']}}@endif">
                                        </td>

                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                 <?php
                                 $srno=0;
                                 ?>
                                 @foreach($bom as $list)
                                 <?php
                                 $srno++;
                                 ?>
                                 <tr>
                                   <td style="width: 10%"> {{($bom->currentPage() - 1) * $bom->perPage() + $loop->iteration}}</td>

                                   <td style="text-align: left;">
                                       <a href="{{url('client/bom/preview/'.$list->id)}}">{{$list->product_name}}
                                       </a></td>
                                   <td style="text-align: center;width: 10%">{{$list->price}}</td>

                                   <td style="text-align: center;width: 20%">{{$list->created_time}}</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
                {{$bom->appends(request()->input())->links()}}
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
