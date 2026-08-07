@extends('admin.layout.master_v2')

@section('title', 'Add Customer')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Add Customer',
        'items' => [
            ['label' => 'Organizations', 'url' => null],
            ['label' => 'Customer', 'url' => route('admin.v2.customer.list')],
            ['label' => 'Add', 'url' => null],
        ],
    ])
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- Submits to the SAME existing 'admin.customer.save' route/controller as the live form. --}}
            <form method="post" action="{{ route('admin.customer.save') }}">
                @csrf

                <div class="form-section">
                    <div class="form-section-title">Basic Information</div>
                    <div class="row">
                        <div class="col-md-4">
                            <x-v2.form.field name="customer_name" label="Customer Name" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="website" label="Website" />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="industry" label="Industry" type="select" :options="$industry" select2 />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="type" label="Customer Type" type="select" :options="$type" select2 />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="department" label="Department" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="designation" label="Designation" required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Owner &amp; Contact</div>
                    <div class="row">
                        <div class="col-md-4">
                            <x-v2.form.field name="owner_name" label="Owner Name" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="owner_mobile" label="Owner Mobile" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="owner_email" label="Owner Email" type="email" />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="primary_phone" label="Primary Phone" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="alternate_no" label="Alternate Number" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="primary_email" label="Primary Email" type="email" />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="secondary_phone" label="Secondary Phone" />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="secondary_email" label="Secondary Email" type="email" />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="work_email" label="Work Email" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="owner_gst" label="GST Number" />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="owner_pan" label="PAN Number" />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Billing Address</div>
                    <div class="row">
                        <div class="col-md-8">
                            <x-v2.form.field name="billing_address" label="Billing Address" type="textarea" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="billing_pobox" label="PO Box" />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="billing_country" label="Country" type="select" :options="$country" select2 required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="billing_state" label="State" type="select" :options="$state" select2 required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="billing_city" label="City" type="select" :options="$city" select2 required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="billing_postalcode" label="Postal Code" required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Shipping Address</div>
                    <div class="row">
                        <div class="col-md-8">
                            <x-v2.form.field name="shipping_address" label="Shipping Address" type="textarea" required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="shipping_pobox" label="PO Box" />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="shipping_country" label="Country" type="select" :options="$country" select2 required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="shipping_state" label="State" type="select" :options="$state" select2 required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="shipping_city" label="City" type="select" :options="$city" select2 required />
                        </div>
                        <div class="col-md-4">
                            <x-v2.form.field name="shipping_postalcode" label="Postal Code" required />
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Additional</div>
                    <div class="row">
                        <div class="col-md-4">
                            <x-v2.form.field name="payment_terms" label="Payment Terms" type="select" :options="$payment_terms" select2 />
                        </div>
                        <div class="col-md-8">
                            <x-v2.form.field name="description" label="Description" type="textarea" />
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.v2.customer.list') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save-outline"></i> Save Customer</button>
                </div>
            </form>
        </div>
    </div>

@endsection
