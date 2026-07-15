@extends('admin.layout.table_master_material')

@section('title', 'List of Payment Terms')

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
                                    <a href="#">Payment Terms List </a>
                                </li>

                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{route('admin.payment_terms.add')}}">Add New</a>
                                </li>

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
                                    <th>Sr.</th>
                                    <th>Terms Name</th>
                                    <th>Number of Days</th>
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
                                        <td style="width: 60%">{{$list->terms_name}}</td>
                                        <td style="width: 60%">{{$list->days}}</td>

                                        <td class="actions" style="width: 10%">

                                            <a href="{{route('admin.payment_terms.edit',['id' => $list->id])}}" class="on-default edit-row">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <a href="{{route('admin.payment_terms.delete',['id' => $list->id])}}" class="on-default remove-row" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i></a>

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
