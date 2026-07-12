@extends('website.template.layout')
@section('title', 'All Sellers')
@section('content')
<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h4">@lang('All Sellers')</h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item opacity-50">
                        <a class="text-reset" href="{{ route('website.home') }}">
                            @lang('Home')
                        </a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset" href="{{ route('website.sellers.all') }}">
                            "@lang('All Sellers')"
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row justify-content-center">
            @include('website.coming-soon')
        </div>
    </div>
</section>
@endsection
