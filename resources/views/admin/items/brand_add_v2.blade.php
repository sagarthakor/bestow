@extends('admin.layout.master_v2')

@section('title', 'Add Brand')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Add Brand',
        'items' => [
            ['label' => 'Inventory', 'url' => null],
            ['label' => 'Brand', 'url' => route('admin.v2.brand.list')],
            ['label' => 'Add', 'url' => null],
        ],
    ])
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- Submits to the SAME existing 'admin.brand.save' route/controller as the live form. --}}
            <form method="post" action="{{ route('admin.brand.save') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <x-v2.form.field name="brand_name" label="Brand Name" required />
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.v2.brand.list') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save-outline"></i> Save Brand</button>
                </div>
            </form>
        </div>
    </div>

@endsection
