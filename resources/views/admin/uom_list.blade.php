@extends('admin.layout.table_master')

@section('title', 'List of UOM')

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
                                    <h4 class="page-title">Usage Unit List </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{ env('APP_NAME') }}</a>
                                        </li>

                                        <li class="active">
                                            Usage Unit List
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
                                    <a class="btn btn-primary" href="{{route('admin.uom.add')}}">Add New</a>
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
                                            <th>Usage Unit</th>

                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tr>
                                            <td><button>Search</button></td>
                                            <td>
                                                 <input value="<?php if(isset($_GET['usage_unit'])){echo $_GET['usage_unit']; }?>" type="text" class="listSearchContributor inputElement" name="usage_unit">
                                            </td>
                                            <td></td>
                                        </tr>

                                        <tbody>
                                             <?php
                                            $srno=0;
                                            ?>
                                            @foreach($data as $list)
                                            <?php
                                            $srno++;
                                            ?>
                                        <tr>
                                            <td style="width: 5%">{{$srno}}</td>
                                            <td>{{$list->uom_name}}</td>

                                            <td class="actions" style="width: 5%">
                                             @can('product_update')
                                                    <a href="{{route('admin.uom.edit',['id' => $list->id] )}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                             @endcan
                                            @can('product_delete')
                                                     <a href="{{route('admin.uom.delete',['id' => $list->id] )}}" class="on-default remove-row"><i class="fa fa-trash-o" onclick="return confirm('Are you sure you want to delete this item?');"></i></a>
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



                        <!-- end row -->



                    </div> <!-- container -->

                </div> <!-- content -->


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

    @endsection
