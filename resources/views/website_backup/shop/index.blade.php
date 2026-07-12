@extends('front.includes.master')

@section('title')

    @section('content')
    <h2>Products</h2>
    <div class="row">
        @foreach($products as $p)
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/'.$p->cover_image) }}" class="card-img-top" alt="">
                    <div class="card-body">
                        <h5>{{ $p->product_name }}</h5>
                        <a href="{{ route('shop.show',$p->id) }}" class="btn btn-primary">View</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $products->links() }}
@endsection
