@extends('admin.layout.master_v2')

@section('title', 'Edit Subcategory')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Edit Subcategory',
        'items' => [
            ['label' => 'Inventory', 'url' => null],
            ['label' => 'Subcategory', 'url' => route('admin.v2.subcategory.list')],
            ['label' => 'Edit', 'url' => null],
        ],
    ])
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- Submits to the SAME existing 'admin.subcategory.update' route/controller as the live form. --}}
            <form method="post" action="{{ route('admin.subcategory.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $subcategory->id }}">

                <div class="row">
                    <div class="col-md-6">
                        <x-v2.form.field name="category" label="Category" type="select" :options="$category" :value="$subcategory->category" select2 />
                    </div>
                    <div class="col-md-6">
                        <x-v2.form.field name="subcategory_name" label="Subcategory Name" :value="$subcategory->subcategory_name" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subcategory Image</label>
                        @if(!empty($subcategory->subcategory_image))
                            <div class="form-upload-preview">
                                <img src="{{ asset('subcategory/'.$subcategory->subcategory_image) }}" alt="">
                            </div>
                        @endif
                        <input type="file" name="subcategory_image" class="form-control {{ $errors->has('subcategory_image') ? 'is-invalid' : '' }}">
                        @error('subcategory_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.v2.subcategory.list') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save-outline"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>

@endsection
