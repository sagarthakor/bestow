@extends('website.template.layout')
@section('title', $page_title ?? 'Policy')

@section('content')

@if($policy)
<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h4">{{ $policy->title }}</h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item opacity-50">
                        <a class="text-reset" href="{{ route('website.home') }}">@lang('Home')</a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset" href="{{ route('website.policy', ['slug' => $policy->slug, 'id' => $policy->id]) }}">"@lang($policy->title)"</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="mb-4">
    <div class="container">
        <div class="p-4 bg-white rounded shadow-sm overflow-hidden mw-100 text-mute text-left">
            {!! $policy->description !!}
        </div>
    </div>
</section>
@else
@include('website.coming-soon')
@endif
<div class="clearfix mt-5"></div>
@endsection
