@extends('admin.layout.master_material')

@section('title', 'Add New | Belt Production')

@section('sidebar')
    @parent
@endsection

@section('content')

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Add Belt Production</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.belt_production.list') }}">Belt Production</a></li>
                                <li>Add New</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::open(['method' => 'post', 'route' => 'admin.belt_production.store', 'id' => 'beltProductionForm']) }}
                        <div class="card-box">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger">{{ session()->get('error') }}</div>
                            @endif

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Belt (which one to produce)</label>
                                        <select name="belt_product" id="belt_product" class="form-control js-example-basic-single" required>
                                            <option value="">Select belt</option>
                                            @foreach($belts as $belt)
                                                <option value="{{ $belt->id }}">{{ $belt->product_name }}</option>
                                            @endforeach
                                        </select>
                                        @if($belts->isEmpty())
                                            <p class="help-block text-danger">No belt has a formula yet - add one in Buckle Formula Master first.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Customer</label>
                                        <select name="customer" id="customer" class="form-control js-example-basic-single" required>
                                            <option value="">Select customer</option>
                                            @foreach($customer as $c)
                                                <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Qty To Produce</label>
                                        <input type="text" name="planned_qty" id="planned_qty" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div id="materialPreview"></div>

                            <div style="margin-top:20px;">
                                <button type="submit" id="btnSave" class="btn btn-primary" disabled>Save</button>
                                <span id="checkHint" class="text-muted" style="margin-left:10px;">Select belt, customer and qty to see raw material needed</span>
                            </div>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });

        var checkUrl = "{{ route('admin.belt_production.check_material') }}";

        function checkMaterial() {
            var beltProduct = $('#belt_product').val();
            var qty = $('#planned_qty').val();

            if (!beltProduct || !qty || qty <= 0) {
                $('#materialPreview').html('');
                $('#btnSave').prop('disabled', true);
                return;
            }

            $('#checkHint').text('Checking stock...');

            $.get(checkUrl, {belt_product: beltProduct, qty: qty}, function (res) {
                $('#materialPreview').html(res.html);
                $('#btnSave').prop('disabled', !res.sufficient);
                $('#checkHint').text(res.sufficient ? '' : 'Cannot save - insufficient raw material stock');
            });
        }

        $('#belt_product').on('change', checkMaterial);
        $('#customer').on('change', checkMaterial);
        $('#planned_qty').on('input', checkMaterial);
    </script>

@endsection
