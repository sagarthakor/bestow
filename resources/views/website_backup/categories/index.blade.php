@extends('website.template.layout')
@section('title', 'All Categories')
@section('content')
    <div class="container py-4">
        <h3>All Categories</h3>
        <div class="row">
            @foreach($categories as $category)
                <div class="col-md-3 col-6 mb-3">
                    <div class="card">
                        <img src="{{ asset('category_image/' . $category->category_image) }}" class="card-img-top">
                        <div class="card-body">
                            <h6>{{ $category->category_name }}</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
