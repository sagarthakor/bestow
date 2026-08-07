@extends('admin.layout.table_master_material')

@section('title', 'Production Dashboard')

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
                            <h4 class="page-title">Batch Wist Production Dashboard</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    Dashboard
                                </li>

                                @can('production_create')
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{route('admin.production.machine.list')}}">Add Batch</a>
                                    </li>
                                @endcan
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                <div class="row">
                    @if(session()->has("message"))
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 alert alert-success">
                                    <strong>{{Session::get("message")}}</strong>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row text-center">




                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <a href="{{route('admin.production.all')}}">
                                <div class="card-box widget-box-one bg-secondary">
                                    <div class="wigdet-one-content">
                                        <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">Total Production </p>
                                        <h1 class="text-dark"><span data-plugin="counterup">{{$total ?? 0}}</span></h1>
                                        <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                    </div>
                                </div>
                            </a>
                        </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <a href="{{route('admin.production.allocate_machines')}}">
                            <div class="card-box widget-box-one bg-secondary">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">Pending Production </p>
                                    <h1 class="text-dark"><span data-plugin="counterup">{{$pending ?? 0}}</span></h1>
                                    <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <a href="{{route('admin.production.complete')}}">
                            <div class="card-box widget-box-one bg-secondary">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">Complete Production </p>
                                    <h1 class="text-dark"><span data-plugin="counterup">{{$complete ?? 0}}</span></h1>
                                    <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                </div>
                            </div>
                        </a>
                    </div>


                </div>






            </div> <!-- container -->

        </div> <!-- content -->

@endsection
