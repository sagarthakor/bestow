@extends('admin.layout.table_master')

@section('title', 'Order List')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container">

                <!-- ✅ Page Header -->
                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box d-flex justify-content-between align-items-center">
                            <h4 class="page-title">Website Orders</h4>
                            <ol class="breadcrumb m-0 p-0">
                                <li><a href="#">{{ Session::get('software_title') }}</a></li>
                                <li class="active">Order List</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- ✅ Table -->
                <div class="row">
                    <div class="col-sm-12">
                        @if(session('message'))
                            <div class="alert alert-info" style="background:#188ae2;color:white;">
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="card-box table-responsive">
                            <form method="get" action="{{ route('admin.orders.index') }}">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th width="20%">Actions</th>
                                    </tr>
                                    </thead>

                                    <!-- ✅ Filter row -->
                                    <tr>
                                        <td>
                                            <button class="btn btn-primary btn-sm">Search</button>
                                        </td>
                                        <td>
                                            <input type="text" name="order_id" value="{{ request('order_id') }}"
                                                   placeholder="Order ID" class="form-control inputElement">
                                        </td>
                                        <td>
                                            <input type="text" name="customer" value="{{ request('customer') }}"
                                                   placeholder="Customer" class="form-control inputElement">
                                        </td>
                                        <td>
                                            <input type="text" name="amount" value="{{ request('amount') }}"
                                                   placeholder="Amount" class="form-control inputElement">
                                        </td>
                                        <td>
                                            <select name="status" class="form-control inputElement">
                                                <option value="">All</option>
                                                @foreach(['placed','confirmed','shipped','delivered','cancelled'] as $st)
                                                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>
                                                        {{ ucfirst($st) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tbody>
                                    @php $sr = ($orders->currentPage() - 1) * $orders->perPage(); @endphp

                                    @forelse($orders as $order)
                                        <tr>
                                            <td>{{ ++$sr }}</td>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->user->customer_name ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($order->amount, 2) }}</td>
                                            <td>
                                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="status-form">
                                                    @csrf
                                                    <select name="status" class="form-control input-sm status-select"
                                                            data-order-id="{{ $order->id }}"
                                                            data-customer-email="{{ $order->user->email ?? '' }}"
                                                            data-customer-phone="{{ $order->user->phone ?? '' }}">
                                                        @foreach(['placed','confirmed','shipped','delivered','cancelled'] as $st)
                                                            <option value="{{ $st }}" {{ $order->status == $st ? 'selected' : '' }}>
                                                                {{ ucfirst($st) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </td>

                                            <td>{{ $order->created_at->format('d M Y, h:i A') }}</td>

                                            <td>
                                                <a href="{{ route('admin.orders.view',['id' => $order->id]) }}" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No Orders Found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </form>

                            <!-- ✅ Pagination -->
                            {{ $orders->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
                <!-- 🚚 Shipment Modal -->
                <div class="modal fade" id="shippingModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="shippingForm" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Enter Shipping Details</h5>
                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="status" value="shipped">

                                    <div class="form-group">
                                        <label>Courier Name</label>
                                        <input type="text" class="form-control" name="courier_name" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Tracking Number</label>
                                        <input type="text" class="form-control" name="tracking_number" required>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Update & Send Notification</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.status-select').forEach(function (dropdown) {
            dropdown.addEventListener('change', function () {
                let orderId = this.getAttribute('data-order-id');

                if (this.value === 'shipped') {
                    // ✅ Open modal instead of submitting form
                    document.getElementById('shippingForm').action =
                        '/admin/orders/' + orderId + '/update-status';
                    $('#shippingModal').modal('show'); // Bootstrap modal
                } else {
                    this.form.submit();
                }
            });
        });
    });
</script>
