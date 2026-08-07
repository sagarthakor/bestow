@extends('admin.layout.table_master_v2')

@section('title', 'Subcategory List')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Subcategory',
        'items' => [
            ['label' => 'Inventory', 'url' => null],
            ['label' => 'Subcategory', 'url' => null],
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

    <x-v2.table-card title="All Subcategories" :add-url="route('admin.v2.subcategory.add')">
        <table data-v2-datatable data-paging="false" class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Image</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($subcategory as $list)
                    <tr>
                        <td>{{ ($subcategory->currentPage() - 1) * $subcategory->perPage() + $loop->iteration }}</td>
                        <td>{{ $list->category_name }}</td>
                        <td>{{ $list->subcategory_name }}</td>
                        <td>
                            @if(!empty($list->subcategory_image))
                                <img src="{{ asset('subcategory/'.$list->subcategory_image) }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:var(--radius-sm);">
                            @else
                                <span class="text-muted text-sm">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                @can('product_update')
                                    <a href="{{ route('admin.v2.subcategory.edit', ['id' => $list->id]) }}" class="btn btn-ghost btn-icon btn-sm" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                @endcan
                                @can('product_delete')
                                    <a href="{{ route('admin.subcategory.delete', $list->id) }}" class="btn btn-ghost btn-icon btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');"><i class="mdi mdi-delete-outline"></i></a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="table-empty"><span>📭</span>No subcategories found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </x-v2.table-card>

    <div class="mt-3">
        {{ $subcategory->links() }}
    </div>

@endsection
