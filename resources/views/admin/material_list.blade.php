@extends('admin.layout.table_master')

@section('title', 'List of Material')

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
                            <h4 class="page-title">Material List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    Material List
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
                            <a class="btn btn-primary" href="{{route('admin.material.add')}}">Add New</a>
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

                        {{Form::open(['method'=>'get'])}}

                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr.</th>

                                    <th>Material Name</th>
                                    <th>HSN CODE</th>

                                    <th></th>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td >
                                        <input type="text" name="material_name" class="listSearchContributor inputElement" placeholder="Material Name" value="@if(isset($_GET['material_name'])){{$_GET['material_name']}}@endif">
                                    </td>
                                    <td>
                                        <input type="text" name="hsn_code" class="listSearchContributor inputElement" placeholder="HSN CODE" value="@if(isset($_GET['hsn_code'])){{$_GET['hsn_code']}}@endif">
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
                                <td width="5%">{{$srno}}</td>
                                <td style="width: 60%">{{$list->material_name}}</td>
                                <td width="20%">{{$list->hsn_code}}</td>

                                <td class="actions" width="5%">
                                 @can('product_update')
                                        <a href="{{route('admin.material_edit',['id' => $list->id])}}" class="on-default edit-row"><i class="fa fa-pencil"></i></a>
                                 @endcan
                                @can('product_delete')
                                         <a href="{{route('admin.material.delete',['id' => $list->id])}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>
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

@endsection
