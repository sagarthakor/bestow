@extends('admin.layout.table_master_material')

@section('title', 'Dashboard')

@section('sidebar')
    @parent
@endsection

@section('content')

    <div class="content-page">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Production Dashboard</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{ Session::get('software_title') }}</a>
                                </li>
                                <li class="active">Production Dashboard</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if(session()->has("message"))
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 alert alert-info">
                                <strong>{{ Session::get("message") }}</strong>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row text-center">

                    {{-- Production --}}
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <a href="{{ route('admin.production.process') }}">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary">Production</p>
                                    <h2 class="text-danger">
                                        <span data-plugin="counterup">{{ $productionComplete + $productionPending }}</span>
                                    </h2>
                                    <a href="{{ route('admin.production.complete') }}">
                                        <p class="text-muted m-0"><b>Complete:</b> {{ $productionComplete }}</p>
                                    </a>

                                    <a href="{{ route('admin.production.allocate_machines') }}">
                                        <p class="text-muted m-0"><b>Pending:</b> {{ $productionPending }}</p>
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Stitching --}}
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <a href="{{ route('admin.production.stitching.dashboard') }}">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary">Stitching</p>
                                    <h2 class="text-danger">
                                        <span data-plugin="counterup">{{ $stitchingComplete + $stitchingPending }}</span>
                                    </h2>

                                    <a href="{{ route('admin.production.stitching.complete') }}">
                                        <p class="text-muted m-0"><b>Complete:</b> {{ $stitchingComplete }}</p>
                                    </a>

                                    <a href="{{ route('admin.production.stitching.pending') }}">
                                        <p class="text-muted m-0"><b>Pending:</b> {{ $stitchingPending }}</p>
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Pressing --}}
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <a href="{{ route('admin.production.pressing.dashboard') }}">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary">Pressing</p>
                                    <h2 class="text-danger">
                                        <span data-plugin="counterup">{{ $pressingComplete + $pressingPending }}</span>
                                    </h2>

                                    <a href="{{ route('admin.production.pressing.complete') }}">
                                        <p class="text-muted m-0"><b>Complete:</b> {{ $pressingComplete }}</p>
                                    </a>

                                    <a href="{{ route('admin.production.pressing.pending') }}">
                                        <p class="text-muted m-0"><b>Pending:</b> {{ $pressingPending }}</p>
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Washing --}}
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <a href="{{ route('admin.production.washing.dashboard') }}">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary">Washing</p>
                                    <h2 class="text-danger">
                                        <span data-plugin="counterup">{{ $washingComplete + $washingPending }}</span>
                                    </h2>

                                    <a href="{{ route('admin.production.washing.complete') }}">
                                        <p class="text-muted m-0"><b>Complete:</b> {{ $washingComplete }}</p>
                                    </a>

                                    <a href="{{ route('admin.production.washing.pending') }}">
                                        <p class="text-muted m-0"><b>Pending:</b> {{ $washingPending }}</p>
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                    {{-- Packaging --}}
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <div class="card-box widget-box-one">
                            <a href="{{ route('admin.production.packaging.dashboard') }}">
                                <div class="wigdet-one-content">
                                    <p class="m-0 text-uppercase font-600 font-secondary">Packaging</p>
                                    <h2 class="text-danger">
                                        <span data-plugin="counterup">{{ $packagingComplete + $packagingPending }}</span>
                                    </h2>

                                    <a href="{{ route('admin.production.packaging.complete') }}">
                                        <p class="text-muted m-0"><b>Complete:</b> {{ $packagingComplete }}</p>
                                    </a>

                                    <a href="{{ route('admin.production.packaging.pending') }}">
                                        <p class="text-muted m-0"><b>Pending:</b> {{ $packagingPending }}</p>
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

@endsection
