@extends('admin.layout.master')

@section('title', 'Add | Banner')

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
                            <h4 class="page-title">Banner Add </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{env('COMPANY_NAME')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.banners.index')}}">Banner List </a>
                                </li>

                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->



                <div class="row">
                    <div class="col-xs-12">
                        <div class="card-box">

                            <div class="row">
                                @if ($errors->any())
                                    <div class="col-xs-12">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xs-12">
                                    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <label>Name:</label>
                                        <input type="text" name="name" class="form-control" required>

                                        <label>Image: (2 MB)</label>
                                        <input type="file" name="image" class="form-control" required>

                                        <button class="btn btn-success mt-3">Save</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
