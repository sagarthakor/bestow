@extends('admin.layout.master')

@section('title', 'List | Banners')

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
                            <h4 class="page-title">Banners List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.banners.index')}}">Banners List </a>
                                </li>

                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="card-box">

                            <div class="row">

                                <h3>Banner List</h3>
                                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary mb-3">Add New Banner</a>

                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($banners as $key => $banner)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $banner->name }}</td>
                                            <td><img src="{{ asset($banner->image) }}" width="120"></td>
                                            <td>{{ $banner->status ? 'Active' : 'Inactive' }}</td>
                                            <td>
                                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="{{ route('admin.banners.destroy', $banner->id) }}"
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Are you sure you want to delete this?')">
                                                    Delete
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
