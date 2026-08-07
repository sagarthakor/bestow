@extends('admin.layout.table_master_v2')

@section('title', 'Category List')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Category',
        'items' => [
            ['label' => 'Inventory', 'url' => null],
            ['label' => 'Category', 'url' => null],
        ],
    ])
@endsection

@section('content')

    @if(session()->has('message'))
        <div class="alert alert-success mb-4">
            <span class="alert-icon"><i class="mdi mdi-check-circle-outline"></i></span>
            <div>{{ session()->get('message') }}</div>
        </div>
    @endif

    <x-v2.table-card title="All Categories" :add-url="route('admin.v2.category.add')">
        <x-slot name="toolbar">
            <form method="get" class="d-flex flex-wrap gap-3 align-items-end w-100">
                <div class="table-toolbar-field">
                    <label class="form-label">Category Name</label>
                    <input type="text" name="category_name" value="{{ request('category_name') }}" class="form-control" placeholder="Search by name">
                </div>
                <div class="table-toolbar-actions">
                    <a href="{{ route('admin.v2.category.list') }}" class="btn btn-secondary btn-sm">Reset</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="mdi mdi-magnify"></i> Search</button>
                </div>
            </form>
        </x-slot>

        <table data-v2-datatable data-paging="false" class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Image</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $list)
                    <tr>
                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                        <td>{{ $list->category_name }}</td>
                        <td>
                            @if(!empty($list->category_image))
                                <img src="{{ asset('/product_category/'.$list->category_image) }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:var(--radius-sm);">
                            @else
                                <span class="text-muted text-sm">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                @can('product_update')
                                    <a href="{{ route('admin.v2.category.edit', ['id' => $list->id]) }}" class="btn btn-ghost btn-icon btn-sm" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                @endcan
                                @can('product_delete')
                                    <a href="{{ route('admin.category.delete', ['id' => $list->id]) }}" class="btn btn-ghost btn-icon btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');"><i class="mdi mdi-delete-outline"></i></a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="table-empty"><span>📭</span>No categories found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </x-v2.table-card>

    <div class="mt-3">
        {{ $data->appends(request()->input())->links() }}
    </div>

@endsection
