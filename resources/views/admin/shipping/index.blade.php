@extends('admin.layout.table_master_material')

@section('title', 'Shipping Charges')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="page-title-box">
                    <h4 class="page-title">Shipping Charges</h4>
                </div>

                @if(session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <div class="card-box p-3">
                    <h5>{{ $editData ? 'Edit Shipping Charge' : 'Add New Shipping Charge' }}</h5>

                    <form method="POST" action="{{ $editData ? route('admin.shipping.update', $editData->id) : route('admin.shipping.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label>State</label>
                                <select name="state_id" class="form-control" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $s)
                                        <option value="{{ $s->id }}"
                                            {{ $editData && $editData->state_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>Min Amount</label>
                                <input type="number" step="0.01" name="min_amount" class="form-control"
                                       value="{{ $editData->min_amount ?? '' }}" required>
                            </div>
                            <div class="col-md-2">
                                <label>Max Amount (optional)</label>
                                <input type="number" step="0.01" name="max_amount" class="form-control"
                                       value="{{ $editData->max_amount ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label>Shipping Charge</label>
                                <input type="number" step="0.01" name="shipping_fee" class="form-control"
                                       value="{{ $editData->shipping_fee ?? '' }}" required>
                            </div>
                            <div class="col-md-3 mt-4">
                                <button class="btn btn-success">{{ $editData ? 'Update' : 'Add' }}</button>
                                @if($editData)
                                    <a href="{{ route('admin.shipping.index') }}" class="btn btn-secondary">Cancel</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-box table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>State</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Charge</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($charges as $i => $c)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $c->state->state_name ?? '-' }}</td>
                                <td>₹{{ $c->min_amount }}</td>
                                <td>{{ $c->max_amount ? '₹'.$c->max_amount : 'No Limit' }}</td>
                                <td>₹{{ $c->shipping_fee }}</td>
                                <td>
                                    <a href="{{ route('admin.shipping.index', ['edit' => $c->id]) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.shipping.delete', $c->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Delete?')" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
