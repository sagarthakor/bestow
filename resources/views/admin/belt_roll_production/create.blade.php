@extends('admin.layout.master_material')

@section('title', 'Add New | Roll Production')

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
                            <h4 class="page-title">Add Roll Production</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.belt_roll_production.list') }}">Roll Production</a></li>
                                <li>Add New</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::open(['method' => 'post', 'route' => 'admin.belt_roll_production.store', 'id' => 'rollProductionForm']) }}
                        <input type="hidden" name="action" id="action" value="">
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

                            <p class="text-muted">No size here &mdash; a roll is just meters of a semi product. Sizes are cut out of the roll afterwards in Belt Cutting.</p>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Semi Product (roll to weave)</label>
                                        <select name="roll_formula_id" id="roll_formula_id" class="form-control js-example-basic-single" required>
                                            <option value="">Select semi product</option>
                                            @foreach($formulas as $f)
                                                @php
                                                    // The roll's stock name already ends with its niwar, so only
                                                    // spell it out for older formulas that were named by hand.
                                                    $rollName = $f->product_item->product_name ?? ('Product #' . $f->product);
                                                    $niwarLabel = $f->niwar->label ?? '';
                                                @endphp
                                                <option value="{{ $f->id }}" data-belt-product="{{ $f->belt_product_id }}" {{ old('roll_formula_id') == $f->id ? 'selected' : '' }}>
                                                    {{ $rollName }}@if($niwarLabel && !str_contains($rollName, $niwarLabel)) &mdash; {{ $niwarLabel }}@endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($formulas->isEmpty())
                                            <p class="help-block text-danger">No roll formula exists yet - add one under Roll Formula first.</p>
                                        @endif
                                    </div>
                                </div>
                                {{-- The belt these meters are being woven for, as a picture. --}}
                                <div class="col-md-1" style="padding-left:0;">
                                    <div class="form-group product-photo-box">
                                        <label class="control-label" style="display:block;">Photo</label>
                                        <img class="product-photo" id="roll_belt_photo" style="display:none;" title="Click to enlarge">
                                        <span class="product-photo-empty"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">Roll Length (Meter)</label>
                                        <input type="number" step="0.01" min="0.01" name="roll_length_mtr" id="roll_length_mtr" class="form-control" value="{{ old('roll_length_mtr', 70) }}" required>
                                        <small class="text-muted">As per order &mdash; 50, 60, 70 &hellip;</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label">No. of Rolls</label>
                                        <input type="number" step="1" min="1" name="no_of_rolls" id="no_of_rolls" class="form-control" value="{{ old('no_of_rolls', 1) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Customer (optional)</label>
                                        <select name="customer" id="customer" class="form-control js-example-basic-single">
                                            <option value="">Stock / no specific customer</option>
                                            @foreach($customer as $c)
                                                <option value="{{ $c->id }}" {{ old('customer') == $c->id ? 'selected' : '' }}>{{ $c->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="materialPreview"></div>

                            <div style="margin-top:20px;">
                                <button type="submit" id="btnSave" class="btn btn-primary" disabled onclick="document.getElementById('action').value='';">Save</button>
                                <button type="submit" id="btnPurchase" class="btn btn-warning" style="display:none;"
                                        onclick="document.getElementById('action').value='purchase_request';">Send Purchase Request</button>
                                <span id="checkHint" class="text-muted" style="margin-left:10px;">Select a semi product, roll length and count to see the raw material needed</span>
                            </div>
                            <p class="text-muted" style="margin-top:10px;">
                                <i class="fa fa-info-circle"></i>
                                Saving deducts the dhaga above from stock straight away and issues it to this batch.
                                Complete the batch later to record what was actually woven.
                            </p>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('import-javascript')

@include('admin.partials._product_photo')

<script>
        var checkUrl = "{{ route('admin.belt_roll_production.check_material') }}";
        var checkTimer = null;

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
            queueCheck();
            loadBeltPhoto();
        });

        // The roll is size-less, so the photo shown is of the belt this formula
        // says the roll becomes.
        function loadBeltPhoto() {
            var beltProduct = $('#roll_formula_id').find('option:selected').data('belt-product');
            ProductPhoto.load(beltProduct, $('#roll_belt_photo'));
        }

        function queueCheck() {
            clearTimeout(checkTimer);
            checkTimer = setTimeout(runCheck, 350);
        }

        function runCheck() {
            var formulaId = $('#roll_formula_id').val();
            var rollLength = $('#roll_length_mtr').val();
            var noOfRolls = $('#no_of_rolls').val();

            if (!formulaId || !(rollLength > 0) || !(noOfRolls > 0)) {
                $('#materialPreview').html('');
                $('#btnSave').prop('disabled', true);
                $('#btnPurchase').hide();
                $('#checkHint').text('Select a semi product, roll length and count to see the raw material needed');
                return;
            }

            $('#checkHint').text('Checking raw material stock...');

            $.get(checkUrl, {roll_formula_id: formulaId, roll_length_mtr: rollLength, no_of_rolls: noOfRolls}, function (res) {
                $('#materialPreview').html(res.html);
                $('#btnSave').prop('disabled', !res.sufficient);

                // Short stock is not a dead end - the shortage can go to purchase.
                if (res.can_purchase) {
                    $('#btnPurchase').show();
                    $('#checkHint').text('Raw material short - start the batch once stock arrives, or send a purchase request now');
                } else {
                    $('#btnPurchase').hide();
                    $('#checkHint').text('');
                }
            }).fail(function () {
                $('#materialPreview').html('<div class="alert alert-danger">Could not check raw material stock. Reload the page and try again.</div>');
                $('#btnSave').prop('disabled', true);
                $('#btnPurchase').hide();
                $('#checkHint').text('');
            });
        }

        $('#roll_formula_id').on('change', function () {
            queueCheck();
            loadBeltPhoto();
        });
        $('#roll_length_mtr').on('input', queueCheck);
        $('#no_of_rolls').on('input', queueCheck);

        // Both buttons issue stock, so a double click would create two batches
        // and deduct the dhaga twice. The form is sealed on the first submit.
        var submitted = false;
        $('#rollProductionForm').on('submit', function (e) {
            if (submitted) {
                e.preventDefault();
                return false;
            }

            // Worded as a deduction, not as a shortage. "Issue out of stock" read
            // as an out-of-stock warning even when every line said OK.
            var isPurchase = $('#action').val() === 'purchase_request';
            if (!isPurchase && !confirm('The dhaga listed above will be deducted from stock and issued to this batch. Continue?')) {
                e.preventDefault();
                return false;
            }

            submitted = true;
            $('#btnSave, #btnPurchase').prop('disabled', true);
            $('#checkHint').text(isPurchase ? 'Sending purchase request...' : 'Creating batch and issuing dhaga...');
        });
    </script>

@endsection
