@extends('admin.layout.master_material')

@section('title', 'Add New | Belt Cutting')

@section('sidebar')
    @parent
@endsection

@section('content')

    <style>
        /* Reference and status blocks read as quiet chrome; the entry table is
           the page. Bootstrap 3 alerts were shouting over it. */
        .belt-panel { border: 1px solid #e3e8ef; border-radius: 4px; margin-bottom: 10px; background: #fff; }
        .belt-panel-head { display: block; padding: 9px 12px; color: #243447; text-decoration: none; font-size: 13px; }
        .belt-panel-head:hover, .belt-panel-head:focus { background: #f6f9fc; color: #243447; text-decoration: none; }
        .belt-panel-head .text-muted { font-weight: 400; }

        .belt-note {
            border: 1px solid #dbe7f3; background: #f2f8fd; color: #2b3b4e;
            border-radius: 4px; padding: 8px 12px; margin-bottom: 10px; font-size: 12.5px;
        }
        .belt-note-warn { border-color: #f0dfae; background: #fdf7e6; color: #6b5518; }
        .belt-chip {
            display: inline-block; background: #fff; border: 1px solid #d5e2ee;
            border-radius: 12px; padding: 1px 9px; margin: 2px 3px 2px 0; font-size: 12px;
        }

        /* Entry grid: quantities are what the operator types, the derived
           figures sit back so the eye lands on the inputs. */
        #cuttingtable > thead > tr > th {
            background: #f6f9fc; border-bottom: 2px solid #e3e8ef;
            font-size: 11px; letter-spacing: .04em; text-transform: uppercase;
            color: #5b6b80; font-weight: 600; vertical-align: middle; padding: 8px;
        }
        #cuttingtable > tbody > tr > td { vertical-align: middle; padding: 6px 8px; }
        #cuttingtable .derived { color: #7a8699; font-size: 12px; }
        #cuttingtable .good-cell { color: #1c8a56; font-weight: 600; }
        #cuttingtable > tfoot > tr > th {
            background: #f6f9fc; border-top: 2px solid #e3e8ef; padding: 8px; font-size: 13px;
        }
        #cuttingtable input { text-align: right; }
        .belt-actions { border-top: 1px solid #eceff5; margin-top: 18px; padding-top: 14px; }
    </style>

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Belt Cutting &amp; Fitting</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li><a href="{{ route('admin.belt_cutting.list') }}">Belt Cutting</a></li>
                                <li>Add New</li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::open(['method' => 'post', 'route' => 'admin.belt_cutting.store', 'id' => 'cuttingForm']) }}
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

                            <p class="text-muted" style="margin-bottom:12px;">
                                Pick one roll, then cut as many sizes out of it as you like. Meters per piece come from the niwar code's size chart.
                            </p>

                            <div id="sizeChartBox"></div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Roll</label>
                                        <select name="roll_id" id="roll_id" class="form-control js-example-basic-single" required>
                                            <option value="">Select roll</option>
                                            @foreach($rolls as $roll)
                                                <option value="{{ $roll->id }}" {{ $selectedRoll == $roll->id ? 'selected' : '' }}>{{ $roll->label }}</option>
                                            @endforeach
                                        </select>
                                        @if($rolls->isEmpty())
                                            <p class="help-block text-danger">No open roll in stock - produce rolls in Roll Production first.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Customer (optional)</label>
                                        <select name="customer" id="customer" class="form-control js-example-basic-single">
                                            <option value="">Stock / no specific customer</option>
                                            @foreach($customer as $c)
                                                <option value="{{ $c->id }}">{{ $c->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-bordered" id="cuttingtable">
                                <thead>
                                <tr>
                                    <th>Belt (size-wise)</th>
                                    <th style="width:8%;">Photo</th>
                                    <th style="width:9%;">Pieces Cut</th>
                                    <th style="width:9%;">Rejected</th>
                                    <th style="width:7%;">Good</th>
                                    <th style="width:9%;">Inch / Pc</th>
                                    <th style="width:9%;">Mtr / Pc</th>
                                    <th style="width:9%;">Gm / Pc</th>
                                    <th style="width:9%;">Total Mtr</th>
                                    <th style="width:9%;">Total Gm</th>
                                    <th style="width:5%;"></th>
                                </tr>
                                </thead>
                                <tbody id="cutting-rows"></tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-right">Total</th>
                                    <th></th>
                                    <th id="foot_pieces" class="text-right">0</th>
                                    <th id="foot_rejected" class="text-right">0</th>
                                    <th id="foot_good" class="text-right">0</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th id="foot_meter" class="text-right">0</th>
                                    <th id="foot_gram" class="text-right">0</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>

                            <button type="button" id="add_row" class="btn btn-default" disabled>+ Add Size</button>

                            <div class="row" style="margin-top:15px;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Trim Wastage (Meter)</label>
                                        <input type="number" step="0.01" min="0" name="wastage_mtr" id="wastage_mtr" class="form-control" value="0">
                                        <small class="text-muted">Meters lost cutting this roll. Leave 0 if none.</small>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted" style="margin-top:28px;">
                                        Usable meters left over stay on the roll for the next cut &mdash; only close the roll
                                        from the Roll Stock Register once the tail is genuinely too short to use.
                                    </p>
                                </div>
                            </div>

                            <div id="planPreview" style="margin-top:15px;"></div>

                            <div class="belt-actions">
                                <button type="submit" id="btnSave" class="btn btn-primary" disabled
                                        onclick="document.getElementById('action').value='';">Save &amp; Add To Stock</button>
                                <button type="submit" id="btnPurchase" class="btn btn-warning" style="display:none;"
                                        onclick="document.getElementById('action').value='purchase_request';">Send Purchase Request</button>
                                <span id="checkHint" class="text-muted" style="margin-left:10px;">Select a roll to begin</span>
                            </div>
                            <p class="text-muted" style="margin-top:10px;">
                                <i class="fa fa-info-circle"></i>
                                Saving does all of it in one step &mdash; meters come off the roll, the kadi / slider / bukkal
                                of each size is consumed from stock, and the good pieces go straight into finished goods stock
                                as size-wise belts. Rejected pieces are recorded but never stocked.
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
        var productsUrl = "{{ route('admin.belt_cutting.products') }}";
        var checkUrl = "{{ route('admin.belt_cutting.check') }}";

        // Sizes cuttable from the selected roll, keyed by belt product id.
        var cuttable = [];
        var meterByProduct = {};
        var inchByProduct = {};
        var gramByProduct = {};
        var rollRemaining = 0;
        var gmPerMeter = 0;
        var checkTimer = null;

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
            if ($('#roll_id').val()) {
                loadProducts();
            }
        });

        function loadProducts() {
            var rollId = $('#roll_id').val();
            $('#cutting-rows').empty();
            $('#planPreview').html('');
            $('#btnSave').prop('disabled', true);

            if (!rollId) {
                cuttable = [];
                $('#add_row').prop('disabled', true);
                $('#sizeChartBox').html('');
                $('#checkHint').text('Select a roll to begin');
                recalc();
                return;
            }

            $('#checkHint').text('Loading sizes...');

            $.get(productsUrl, {roll_id: rollId}, function (res) {
                cuttable = res.products || [];
                rollRemaining = res.remaining_mtr;
                meterByProduct = {};
                inchByProduct = {};
                gramByProduct = {};
                cuttable.forEach(function (p) {
                    meterByProduct[p.belt_product] = p.meter_per_piece;
                    inchByProduct[p.belt_product] = p.required_inch;
                    gramByProduct[p.belt_product] = p.gram_per_piece;
                });

                gmPerMeter = (res.size_chart && res.size_chart.gm_per_meter) || 0;
                renderSizeChart(res.size_chart);

                renderBeltFamily(res);
                renderFitting(res.fitting);

                var any = res.total_products > 0;
                $('#add_row').prop('disabled', !any);

                if (!any) {
                    $('#checkHint').html('<span class="text-danger">No finished belt product has a size on this roll\'s niwar chart, ' +
                        'so nothing can be cut from it. Give the belt products a size (28, 30, 32 &hellip;) in Product master.</span>');
                } else {
                    $('#checkHint').text('Roll has ' + rollRemaining + ' mtr left · ' +
                        res.total_products + ' size' + (res.total_products == 1 ? '' : 's') + ' available');
                    addRow();
                }
                recalc();
            });
        }

        /**
         * The label leads with the niwar chart's P.P size and its inches, because
         * that is what actually decides how much roll the piece eats. A product's
         * own variant size is a different field and can disagree with it, so it is
         * only shown when it does - as a warning, not as the size.
         */
        function optionLabel(p) {
            return p.base_name +
                ' — P.P. ' + p.size +
                ' · ' + p.required_inch + ' inch' +
                ' · ' + p.meter_per_piece + ' mtr' +
                ' · ' + p.gram_per_piece + ' g';
        }

        /**
         * Hundreds of sized belts qualify for a roll, so each row searches the
         * catalogue server-side instead of carrying every option in the markup.
         */
        function attachProductSearch(select) {
            select.select2({
                placeholder: 'Type to search belt product...',
                width: '100%',
                ajax: {
                    url: productsUrl,
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        // Always sent, even empty - it is what tells the server
                        // this is a dropdown search and not the roll being picked.
                        return {roll_id: $('#roll_id').val(), search: params.term || ''};
                    },
                    processResults: function (res) {
                        (res.products || []).forEach(function (p) {
                            meterByProduct[p.belt_product] = p.meter_per_piece;
                            inchByProduct[p.belt_product] = p.required_inch;
                            gramByProduct[p.belt_product] = p.gram_per_piece;
                        });

                        return {
                            results: (res.products || []).map(function (p) {
                                return {id: p.belt_product, text: optionLabel(p)};
                            })
                        };
                    }
                }
            });
        }

        /**
         * The chart the sizes come from, plus the charted sizes that have no belt
         * formula - otherwise a size missing from the dropdown looks like a bug.
         */
        /**
         * What one belt takes in bukkal / kadi / panni, so the operator sees the
         * fitting bill before entering a single piece.
         */
        /**
         * Which belt this roll was woven to become. Stated plainly, because it is
         * the reason the size list is short.
         */
        function renderBeltFamily(res) {
            if (!res.belt_family) {
                $('#sizeChartBox').append(
                    '<div class="belt-note"><i class="mdi mdi-information-outline"></i> ' +
                    'This roll\'s formula does not name a belt product, so <b>every sized belt</b> on the ' +
                    'niwar chart is offered. Set <b>Belt Product This Roll Becomes</b> on the roll formula to narrow it.' +
                    '</div>'
                );
                return;
            }

            $('#sizeChartBox').append(
                '<div class="belt-note"><i class="mdi mdi-content-cut"></i> ' +
                'Cutting <b>' + res.belt_family + '</b> &mdash; only this product\'s sizes are offered.</div>'
            );
        }

        function renderFitting(fitting) {
            if (!fitting || !fitting.configured) {
                $('#sizeChartBox').append(
                    '<div class="belt-note belt-note-warn">' +
                    '<i class="mdi mdi-alert-outline"></i> No bukkal, kadi or panni product is set on this niwar\'s ' +
                    'belt costing, so <b>no fitting stock will be deducted</b>. Set them in Belt Costing if it should.' +
                    '</div>'
                );
                return;
            }

            var parts = fitting.lines.map(function (l) {
                return '<span class="belt-chip">' + l.label + ' <b>' + l.qty + '</b> &times; ' + l.name + '</span>';
            }).join(' ');

            $('#sizeChartBox').append(
                '<div class="belt-note"><i class="mdi mdi-link-variant"></i> <b>Fitting per belt</b> ' + parts + '</div>'
            );
        }

        function renderSizeChart(chart) {
            var box = $('#sizeChartBox');

            if (!chart || !chart.charted || chart.charted.length === 0) {
                box.html('');
                return;
            }

            var head = '', inches = '', metres = '', grams = '';
            chart.charted.forEach(function (s) {
                var can = chart.cuttable.some(function (c) { return c.size === s.size; });
                var cell = 'text-align:center;padding:4px 10px;border-bottom:1px solid #e9eef5;' +
                    (can ? 'color:#243447;font-weight:600;' : 'color:#b6bfcc;');
                head += '<td style="' + cell + 'background:#f6f9fc;">' + s.size + '</td>';
                inches += '<td style="' + cell + '">' + s.inch + '"</td>';
                metres += '<td style="' + cell + '">' + s.mtr + '</td>';
                grams += '<td style="' + cell + '">' + s.gm + '</td>';
            });

            var rowHead = 'style="text-align:right;padding:4px 12px 4px 0;white-space:nowrap;' +
                'color:#7a8699;font-size:11.5px;border-bottom:1px solid #e9eef5;"';

            var summary = chart.niwar + ' · sizes ' + chart.charted[0].size +
                '–' + chart.charted[chart.charted.length - 1].size +
                ' · ' + chart.gm_per_meter + ' g per meter';

            box.html(
                '<div class="belt-panel">' +
                '<a href="javascript:void(0)" id="chartToggle" class="belt-panel-head">' +
                '<i class="mdi mdi-chevron-right" id="chartCaret"></i> ' +
                '<b>Size chart</b> <span class="text-muted">' + summary + '</span>' +
                '</a>' +
                '<div id="chartBody" style="display:none;padding:4px 12px 12px;overflow-x:auto;">' +
                '<table style="border-collapse:collapse;">' +
                '<tr><td ' + rowHead + '>P.P. size</td>' + head + '</tr>' +
                '<tr><td ' + rowHead + '>Roll used</td>' + inches + '</tr>' +
                '<tr><td ' + rowHead + '>Meters</td>' + metres + '</tr>' +
                '<tr><td ' + rowHead + '>Dhaga (g)</td>' + grams + '</tr>' +
                '</table>' +
                (chart.missing.length
                    ? '<p class="text-muted" style="margin:8px 0 0;font-size:11.5px;">' +
                      'Greyed sizes (' + chart.missing.join(', ') + ') have no finished belt product yet.</p>'
                    : '') +
                '</div></div>'
            );

            $('#chartToggle').on('click', function () {
                $('#chartBody').slideToggle(120);
                $('#chartCaret').toggleClass('mdi-chevron-right mdi-chevron-down');
            });
        }



        function addRow() {
            var row = document.createElement('tr');
            row.innerHTML =
                '<td><select name="belt_product[]" class="form-control product-select" style="width:100%;"></select></td>' +
                // The size is picked from a text dropdown, so the photo is the
                // only way to see on the floor that the right belt was chosen.
                '<td class="text-center product-photo-box">' +
                '<img class="product-photo" style="display:none;" title="Click to enlarge">' +
                '<span class="product-photo-empty"></span></td>' +
                '<td><input type="number" min="0" step="1" name="pieces[]" class="form-control pieces-input" value=""></td>' +
                '<td><input type="number" min="0" step="1" name="rejected_pieces[]" class="form-control rejected-input" value="0"></td>' +
                '<td class="text-right good-cell">-</td>' +
                '<td class="text-right derived inch-cell">-</td>' +
                '<td class="text-right derived mtr-cell">-</td>' +
                '<td class="text-right derived gm-cell">-</td>' +
                '<td class="text-right total-cell">-</td>' +
                '<td class="text-right total-gm-cell">-</td>' +
                '<td class="text-center"><a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a></td>';
            document.getElementById('cutting-rows').appendChild(row);
            attachProductSearch($(row).find('.product-select'));
        }

        document.getElementById('add_row').addEventListener('click', addRow);

        function removeRow(el) {
            el.closest('tr').remove();
            recalc();
            queueCheck();
        }

        /**
         * Keeps the per-row meters and the footer totals live, so the operator
         * sees the roll balance going negative before hitting Save.
         */
        function recalc() {
            var totalPieces = 0;
            var totalRejected = 0;
            var totalMeter = 0;
            var totalGram = 0;

            document.querySelectorAll('#cutting-rows tr').forEach(function (row) {
                var productId = row.querySelector('.product-select').value;
                var pieces = parseInt(row.querySelector('.pieces-input').value) || 0;
                var rejectedInput = row.querySelector('.rejected-input');
                var rejected = parseInt(rejectedInput.value) || 0;
                var mtr = meterByProduct[productId];

                // More rejects than pieces is meaningless; the server clamps it
                // too, but correcting it here keeps the totals honest as typed.
                if (rejected > pieces) {
                    rejected = pieces;
                    rejectedInput.value = pieces;
                }

                if (!productId || !mtr) {
                    ['.inch-cell', '.mtr-cell', '.gm-cell', '.total-cell', '.total-gm-cell', '.good-cell']
                        .forEach(function (sel) { row.querySelector(sel).innerText = '-'; });
                    return;
                }

                var gm = gramByProduct[productId] || 0;
                var total = Math.round(mtr * pieces * 100) / 100;
                // From the meters, matching the server - a rounded per-piece
                // gram multiplied out does not add up to the roll's own loss.
                var totalGm = Math.round(mtr * pieces * gmPerMeter * 100) / 100;

                row.querySelector('.inch-cell').innerText = (inchByProduct[productId] || 0) + '"';
                row.querySelector('.mtr-cell').innerText = mtr;
                row.querySelector('.gm-cell').innerText = gm + ' g';
                row.querySelector('.total-cell').innerText = total;
                row.querySelector('.total-gm-cell').innerText = totalGm + ' g';
                row.querySelector('.good-cell').innerHTML = '<b>' + (pieces - rejected) + '</b>';

                totalPieces += pieces;
                totalRejected += rejected;
                totalMeter += total;
                totalGram += totalGm;
            });

            var wastage = parseFloat(document.getElementById('wastage_mtr').value) || 0;
            totalMeter = Math.round((totalMeter + wastage) * 100) / 100;
            totalGram = Math.round((totalMeter * gmPerMeter) * 100) / 100;

            document.getElementById('foot_gram').innerText = totalGram + ' g';
            document.getElementById('foot_pieces').innerText = totalPieces;
            document.getElementById('foot_rejected').innerText = totalRejected;
            document.getElementById('foot_good').innerText = totalPieces - totalRejected;
            document.getElementById('foot_meter').innerHTML = totalMeter > rollRemaining
                ? '<span style="color:#c0392b">' + totalMeter + '</span>'
                : totalMeter;
        }

        // Debounced so typing a piece count does not fire a request per keystroke.
        function queueCheck() {
            clearTimeout(checkTimer);
            checkTimer = setTimeout(runCheck, 400);
        }

        function runCheck() {
            var rollId = $('#roll_id').val();
            if (!rollId) return;

            var data = $('#cuttingForm').serializeArray().filter(function (f) {
                return f.name === 'belt_product[]' || f.name === 'pieces[]'
                    || f.name === 'rejected_pieces[]' || f.name === 'wastage_mtr';
            });
            data.push({name: 'roll_id', value: rollId});

            $('#checkHint').text('Checking roll balance and fitting stock...');

            $.get(checkUrl, $.param(data), function (res) {
                $('#planPreview').html(res.html);
                $('#btnSave').prop('disabled', !res.sufficient);

                // Short fitting is not a dead end - it can go to purchase.
                if (res.can_purchase) {
                    $('#btnPurchase').show();
                    $('#checkHint').text('Fitting material short - cut once stock arrives, or send a purchase request now');
                } else {
                    $('#btnPurchase').hide();
                    $('#checkHint').text('');
                }
            }).fail(function () {
                $('#planPreview').html('<div class="alert alert-danger">Could not check the cutting plan. Reload the page and try again.</div>');
                $('#btnSave').prop('disabled', true);
                $('#btnPurchase').hide();
                $('#checkHint').text('');
            });
        }

        $(document).on('change', '.product-select', function () {
            ProductPhoto.load($(this).val(), $(this).closest('tr').find('.product-photo'));
            recalc();
            queueCheck();
        });
        $(document).on('input', '.pieces-input, .rejected-input', function () { recalc(); queueCheck(); });
        $('#wastage_mtr').on('input', function () { recalc(); queueCheck(); });
        $('#roll_id').on('change', loadProducts);

        // Saving takes meters off the roll, fitting material off the shelf and
        // puts belts into stock - all of which would happen twice on a double
        // click.
        var submitted = false;
        $('#cuttingForm').on('submit', function (e) {
            if (submitted) {
                e.preventDefault();
                return false;
            }

            var isPurchase = $('#action').val() === 'purchase_request';

            if (!isPurchase && !confirm('This will consume the roll meters and fitting material shown, and add the good belts to stock. Continue?')) {
                e.preventDefault();
                return false;
            }

            submitted = true;
            $('#btnSave, #btnPurchase').prop('disabled', true);
            $('#checkHint').text(isPurchase ? 'Sending purchase request...' : 'Saving cutting entry...');
        });
    </script>

@endsection
