@extends('website.template.layout')
@section('title', 'Your Shipping Address')
@section('content')
<section class="pt-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="row aiz-steps arrow-divider">
                    <div class="col done">
                        <div class="text-center text-success">
                            <i class="la-3x mb-2 las la-shopping-cart"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block ">@lang('1. My Cart')</h3>
                        </div>
                    </div>
                    <div class="col active">
                        <div class="text-center text-primary">
                            <i class="la-3x mb-2 las la-map"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block ">@lang('2. Shipping info')</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50 ">@lang('3. Payment')</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50 ">@lang('4. Confirmation')</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mb-4 gry-bg">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            <div class="col-xxl-8 col-xl-10 mx-auto">
                <form action="" method="post" data-toggle="validator"  onsubmit="ANALOG.blockUI(true)">
                    @csrf
                    @if(Session::has('user') || Session::has('customer'))
                    <div class="shadow-sm bg-white p-4 rounded mb-4">
                        @if(count($addresses) > 0)
                        <h6 class="text-dark">Select Your Address</h6>
                        @endif
                        <div class="row gutters-5">
                            @foreach($addresses as $index => $address)
                            <div class="col-md-6 mb-3">
                                <label class="aiz-megabox d-block bg-white mb-0" for="selectAddress{{ $index }}">
                                    <input type="radio" name="address_id" value="{{ $address->id }}"
                                    id="selectAddress{{ $index }}"
                                    aria-invalid="false" {{ count($addresses) == 1 ? 'checked' : null }}>
                                    <span class="d-flex p-3 aiz-megabox-elem">
                                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                        <span class="flex-grow-1 pl-3 text-left">
                                            <span class="opacity-60">@lang('Type'):</span>
                                            <span class="badge badge-primary badge-inline ">{{ $address->type_name }}</span>
                                            <div>
                                                <span class="opacity-60">@lang('Name'):</span>
                                                <span class="fw-600 ml-2">{{ $address->name }}</span>
                                            </div>
                                            <div>
                                                <span class="opacity-60">@lang('Address'):</span>
                                                <span class="fw-600 ml-2">{{ $address->address }}</span>
                                            </div>
                                            <div>
                                                <span class="opacity-60">@lang('Postal Code'):</span>
                                                <span class="fw-600 ml-2">{{ $address->pin_code }}</span>
                                            </div>
                                            <div>
                                                <span class="opacity-60">@lang('City'):</span>
                                                <span class="fw-600 ml-2">{{ optional($address->city)->city_name }}</span>
                                            </div>
                                            <div>
                                                <span class="opacity-60">@lang('State'):</span>
                                                <span class="fw-600 ml-2">{{ optional($address->state)->state_name }}</span>
                                            </div>
                                            <div>
                                                <span class="opacity-60">@lang('Country'):</span>
                                                <span class="fw-600 ml-2">{{ optional($address->country)->country_name }}</span>
                                            </div>
                                            <div>
                                                <span class="opacity-60">@lang('Phone'):</span>
                                                <span class="fw-600 ml-2">{{ $address->mobile }}</span>
                                            </div>
                                        </span>
                                    </span>
                                </label>
                                <div class="dropdown position-absolute right-0 top-0 mt-1 mr-3">
                                    <a href="{{ route('website.order.checkout.address', ['id' => $address->id]) }}"
                                       class="badge badge-dark badge-inline text-white pull-right">Edit</a>
                                   </div>
                               </div>
                               @endforeach
                               <div class="col-md-6 mx-auto mb-3" >
                                <div class="border p-3 rounded mb-3 c-pointer text-center bg-white h-100 d-flex flex-column justify-content-center" data-toggle="modal"
                                data-target="#createAddress">
                                <i class="las la-plus la-2x mb-3"></i>
                                <div class="alpha-7">@lang('Add New Address')</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                        <a href="{{ route('website.product.view') }}" class="btn btn-link">
                            <i class="las la-arrow-left"></i>
                            @lang('Return to shop')
                        </a>
                    </div>
                    <div class="col-md-6 text-center text-md-right">
                        <button type="submit" class="btn btn-primary fw-600">@lang('Continue to Payment')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($selected_address)
    @include('website.order-process.address.update')
    @else
    @include('website.order-process.address.create')
    @endif
</section>
@endsection
@section('page-javascript')
<script type="text/javascript">
    @if(session('address_errors'))
    @if($selected_address)
    new bootstrap.Modal(document.getElementById('updateAddress'), {
        keyboard: false,
        backdrop: 'static',
    }).toggle();
    @else
    new bootstrap.Modal(document.getElementById('createAddress')).toggle();
    @endif
    @endif

    @if($selected_address)
    new bootstrap.Modal(document.getElementById('updateAddress'), {
        keyboard: false,
        backdrop: 'static',
    }).toggle();
    @endif
    if ('{{isset($selected_address) ? $selected_address->country_id : ''}}' != '') {
        setTimeout(function(){
            jQuery(".country_change").trigger( "change" );
        }, 100);
    }
</script>
@stop
