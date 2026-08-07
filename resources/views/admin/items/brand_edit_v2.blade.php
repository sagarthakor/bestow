@extends('admin.layout.master_v2')

@section('title', 'Edit Brand')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Edit Brand',
        'items' => [
            ['label' => 'Inventory', 'url' => null],
            ['label' => 'Brand', 'url' => route('admin.v2.brand.list')],
            ['label' => 'Edit', 'url' => null],
        ],
    ])
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- Submits to the SAME existing 'admin.brand.update' route/controller as the live form. --}}
            <form method="post" action="{{ route('admin.brand.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $data->id }}">

                <div class="row">
                    <div class="col-md-6">
                        <x-v2.form.field name="brand_name" label="Brand Name" :value="$data->brand_name" required />
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.v2.brand.list') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save-outline"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>

@endsection
