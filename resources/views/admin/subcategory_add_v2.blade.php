@extends('admin.layout.master_v2')

@section('title', 'Add Subcategory')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Add Subcategory',
        'items' => [
            ['label' => 'Inventory', 'url' => null],
            ['label' => 'Subcategory', 'url' => route('admin.v2.subcategory.list')],
            ['label' => 'Add', 'url' => null],
        ],
    ])
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- Submits to the SAME existing 'admin.subcategory.save' route/controller as the live form. --}}
            <form method="post" action="{{ route('admin.subcategory.save') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <x-v2.form.field name="category" label="Category" type="select" :options="$category" select2 />
                    </div>
                    <div class="col-md-6">
                        <x-v2.form.field name="subcategory_name" label="Subcategory Name" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subcategory Image</label>
                        <input type="file" name="subcategory_image" class="form-control {{ $errors->has('subcategory_image') ? 'is-invalid' : '' }}">
                        @error('subcategory_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.v2.subcategory.list') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save-outline"></i> Save Subcategory</button>
                </div>
            </form>
        </div>
    </div>

@endsection
