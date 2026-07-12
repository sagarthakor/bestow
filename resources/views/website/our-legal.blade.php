@extends('website.template.layout')
@section('title', 'Our Legal')
@section('content')
<section class="pt-4 mb-4">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-6 text-center text-lg-left">
                <h1 class="fw-600 h4">@lang('Our Legal')</h1>
            </div>
            <div class="col-lg-6">
                <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                    <li class="breadcrumb-item opacity-50">
                        <a class="text-reset" href="{{ route('website.home') }}">
                            @lang('Home')
                        </a>
                    </li>
                    <li class="text-dark fw-600 breadcrumb-item">
                        <a class="text-reset" href="{{ route('website.legal') }}">
                            "@lang('Our Legal')"
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
@if(count($documents) > 0)
<section class="mb-4">
    <div class="container">
        <div class="row gutters-10">
            @foreach ($documents as $key => $document)
            <div class="col-md-4 mt-2 mb-2">
                <div class="aiz-card-box border border-light rounded hov-shadow-md mt-1 mb-2 has-transition bg-white">
                    <div class="position-relative hov-scale-img">
                        <a href="javascript:void(0)" class="d-block overflow-hidden"
                        data-toggle="modal" data-target="#documentModal{{ $document->id }}"
                        data-name="{{ $document->name }}"
                        data-file="{{ asset(env('COMPANY_DOCUMENT_URL') . $document->file_name) }}">

                        <!-- Image -->
                        <img
                        class="img-fit w-100 has-transition lazyload mx-auto h-140px h-md-210px"
                        src="{{ asset('website-assets/img/placeholder.jpg') }}"
                        data-src="{{ asset(env('COMPANY_DOCUMENT_URL') . $document->file_name) }}"
                        alt="{{$document->name}}"
                        onerror="this.onerror=null;this.src='{{ asset('website-assets/img/placeholder.jpg') }}';"
                        >

                        <!-- Overlay and Button -->
                        <div class="image-overlay">
                            <button class="btn-link-overlay">View Document</button>
                        </div>

                    </a>
                </div>
                <style type="text/css">
                    /* Container for image and overlay */
                    .position-relative {
                        position: relative;
                    }

                    /* Hide the overlay initially */
                    .image-overlay {
                        position: absolute;
                        top: 0;
                        left: 0;
                        right: 0;
                        bottom: 0;
                        background-color: rgba(91, 74, 184, 0.6); /* Orange background with transparency */
                        opacity: 0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        transition: opacity 0.3s ease-in-out;
                    }

                    /* Center the button */
                    .btn-link-overlay {
                        padding: 10px 20px;
                        background-color: white;
                        border: none;
                        border-radius: 5px;
                        font-size: 14px;
                        cursor: pointer;
                        color: #ff6600; /* Orange color for the button */
                        text-transform: uppercase;
                    }

                    .btn-link-overlay:hover {
                        background-color: #ff6600;
                        color: white;
                    }

                    /* On hover, show the overlay */
                    .position-relative:hover .image-overlay {
                        opacity: 1;
                    }

                    /* Optional scaling effect */
                    .position-relative:hover img {
                        transform: scale(1.05);
                        transition: transform 0.3s ease-in-out;
                    }

                </style>
                <div class="p-md-3 p-2 text-center bg-white shadow-sm rounded">
                    <h3 class="fw-600 fs-16 text-truncate-2 lh-1-4 mb-0 h-35px">
                        <a href="javascript:void(0)" class="d-block text-reset"
                        data-toggle="modal" data-target="#documentModal{{ $document->id }}"
                        data-name="{{ $document->name }}"
                        data-file="{{ asset(env('COMPANY_DOCUMENT_URL') . $document->file_name) }}">
                        {{$document->name}}
                    </a>
                </h3>
            </div>
        </div>
    </div>
    <!-- Modal Structure -->
    <div class="modal fade" id="documentModal{{ $document->id }}" tabindex="-1" aria-labelledby="documentModalLabel{{ $document->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentModalLabel{{ $document->id }}">{{$document->name}}</h5>
                    <button class="absolute-top-right bg-white shadow-lg btn btn-circle btn-icon mr-n3 mt-n3 set-session" type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><i class="la la-close fs-20"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <img id="modalImage{{ $document->id }}" class="img-fluid" src="{{ asset(env('COMPANY_DOCUMENT_URL') . $document->file_name) }}" alt="{{ $document->name }}"
                        onerror="this.onerror=null;this.src='{{ asset('website-assets/img/placeholder.jpg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
</div>
</section>
@else
@include('website.coming-soon')
@endif
<div class="clearfix mt-5"></div>

@endsection
