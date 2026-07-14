@extends('admin.layout.table_master_v2')

@section('title', 'Brand List')

@section('breadcrumb')
    @include('admin.layout.partials.breadcrumb_v2', [
        'title' => 'Brand',
        'items' => [
            ['label' => 'Inventory', 'url' => null],
            ['label' => 'Brand', 'url' => null],
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

    <x-v2.table-card title="All Brands" :add-url="route('admin.v2.brand.add')">
        <table data-v2-datatable data-paging="false" class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Brand Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $list)
                    <tr>
                        <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                        <td>{{ $list->brand_name }}</td>
                        <td>
                            <div class="table-actions">
                                @can('product_update')
                                    <a href="{{ route('admin.v2.brand.edit', ['id' => $list->id]) }}" class="btn btn-ghost btn-icon btn-sm" title="Edit"><i class="mdi mdi-pencil"></i></a>
                                @endcan
                                @can('product_delete')
                                    <a href="{{ route('admin.brand.delete', ['id' => $list->id]) }}" class="btn btn-ghost btn-icon btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this item?');"><i class="mdi mdi-delete-outline"></i></a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3"><div class="table-empty"><span>📭</span>No brands found</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </x-v2.table-card>

    <div class="mt-3">
        {{ $data->links() }}
    </div>

@endsection
