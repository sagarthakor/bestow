@extends('website.template.layout')
@section('title', 'Grievance Cell')
@section('content')
<section class="pt-4 my-4">
    @php
    $lang = str_replace('_', '-', app()->getLocale());
    @endphp
    <div class="container">
        <div class="" style="background-color: #ebe9e9">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <div class="p-3 p-md-4 p-xl-5">
                        <h3 class="fs-36 fw-700 mb-4">GRIEVANCE CELL</h3>
                        
                        <p class="fs-16 fw-400 mb-5">{{ config('project.brand') }} Customer Care Number: {{ \App\Library\Helper::get_setting('mobile', 'basic_detail', 'N/A') }}</p>
                        <p class="fs-16 fw-400 mb-5"><b>Working Hours:</b>

                            <br>09:30 AM to 06:00 PM  (Mon to Fri), 09:30 AM to 01:30 PM (Sat)
                            <br><br>

                        For any assistance please speak to our customer care executive or create a service request. Please note down the service request to track the status.</p>
                        <div class="d-flex mb-2">
                            <span class="ml-3">
                                <h5 class="fs-36 fw-700 mb-2">Grievance Redressal Officers</h5>
                                <span class="fs-15 fw-400"><b>@lang('Name :')</b> {{ \App\Library\Helper::get_setting('redressal_name', 'grievance_detail') ?? 'N/A' }}</span><br>
                                <span class="fs-15 fw-400"><b>@lang('Mobile No :')</b> {{ \App\Library\Helper::get_setting('r_mobile', 'grievance_detail') ?? 'N/A' }}</span><br>
                                <span class="fs-15 fw-400"><b>@lang('Email :')</b> {{ \App\Library\Helper::get_setting('r_email', 'grievance_detail') ?? 'N/A' }}</span><br>
                            </span>
                        </div>
                        <div class="d-flex mb-2 mt-4">
                            <span class="ml-3">
                                <h5 class="fs-36 fw-700 mb-2">Nodal Officer</h5>
                                <span class="fs-15 fw-400"><b>@lang('Name :')</b> {{ \App\Library\Helper::get_setting('nodal_name', 'grievance_detail') ?? 'N/A' }}</span><br>
                                <span class="fs-15 fw-400"><b>@lang('Email :')</b> {{ \App\Library\Helper::get_setting('n_email', 'grievance_detail') ?? 'N/A' }}</span><br>
                            </span>
                        </div>

                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-3 p-md-4 p-xl-5">
                        <div class="bg-white p-4 p-xl-2rem border rounded-3">
                            <form class="form-default" role="form" action="{{ route('website.grievance') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="user_id" class="fs-14 fw-700 text-soft-dark">@lang('User Type')</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Select User Type</option>
                                        <option value="seller">Direct Seller</option>
                                        <option value="customer">Customer</option>
                                    </select>
                                </div>

                                <div id="customer-fields" class="d-none">
                                    <!-- Name -->
                                    <div class="form-group">
                                        <label for="name" class="fs-14 fw-700 text-soft-dark">@lang('Name')</label>
                                        <input type="text" class="form-control rounded-0" value="{{ old('name') }}" placeholder="@lang('Enter Name')" name="name" required>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email" class="fs-14 fw-700 text-soft-dark">@lang('Email')</label>
                                        <input type="email" class="form-control rounded-0" value="{{ old('email') }}" placeholder="@lang('Enter Email')" name="email" required>
                                    </div>

                                    <!-- Phone -->
                                    <div class="form-group">
                                        <label for="telephone" class="fs-14 fw-700 text-soft-dark">@lang('Phone no.')</label>
                                        <input type="tel" class="form-control rounded-0" value="{{ old('phone') }}" placeholder="@lang('Enter Phone')" name="telephone" required>
                                    </div>

                                    <!-- Subject -->
                                    <div class="form-group">
                                        <label class="fs-14 fw-700 text-soft-dark">@lang('Grievance Subject')</label>
                                        <select name="subject" class="form-control">
                                            <option value="">Select</option>
                                            <option value="General Enquiry">General Enquiry</option>
                                            <option value="Order Related">Order Related</option>
                                            <option value="Product Related">Product Related</option>
                                            <option value="Business Opportunity">Business Opportunity</option>
                                        </select>
                                    </div>

                                    <!-- Query -->
                                    <div class="form-group">
                                        <label for="message" class="fs-14 fw-700 text-soft-dark">@lang('Tell us about your Comments/Query')</label>
                                        <textarea class="form-control rounded-0" placeholder="@lang('Type here...')" name="message" rows="3" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary fw-700 fs-14 rounded-0 w-200px">@lang('Submit')</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('page-javascript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userTypeSelect = document.getElementById('user_id');
        const customerFields = document.getElementById('customer-fields');
        
        // Event listener when user type changes
        userTypeSelect.addEventListener('change', function () {
            const selectedValue = userTypeSelect.value;

            if (selectedValue === 'seller') {
                // Show SweetAlert for Direct Seller
                Swal.fire({
                    title: 'For Support',
                    text: 'Please raise your service request from your Virtual Office.',
                    icon: 'info',
                    confirmButtonText: 'Okay'
                });
                customerFields.classList.add('d-none'); // Hide customer fields
            } else if (selectedValue === 'customer') {
                customerFields.classList.remove('d-none'); // Show customer fields
            }
        });

        // Trigger the change event to set the initial state
        userTypeSelect.dispatchEvent(new Event('change'));
    });
</script>
@stop

