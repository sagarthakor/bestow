@extends('admin.layout.master_v2')

@section('title', 'Edit Category')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Edit Category',
        'items' => [
            ['label' => 'Inventory', 'url' => null],
            ['label' => 'Category', 'url' => route('admin.v2.category.list')],
            ['label' => 'Edit', 'url' => null],
        ],
    ])
@endsection

@section('content')

    <div class="card">
        <div class="card-body">
            {{-- Submits to the SAME existing 'admin.category.update' route/controller as the live form. --}}
            <form method="post" action="{{ route('admin.category.update') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $data->id }}">

                <div class="row">
                    <div class="col-md-6">
                        <x-v2.form.field name="category_name" label="Category Name" :value="$data->category_name" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Category Image</label>
                        @if(!empty($data->category_image))
                            <div class="form-upload-preview">
                                <img src="{{ asset('/product_category/'.$data->category_image) }}" alt="">
                            </div>
                        @endif
                        <input type="file" name="category_image" class="form-control {{ $errors->has('category_image') ? 'is-invalid' : '' }}">
                        @error('category_image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.v2.category.list') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save-outline"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>

@endsection
