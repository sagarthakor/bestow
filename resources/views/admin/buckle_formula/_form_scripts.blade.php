<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
        $('#material-rows .material-select').each(function () { updateUomHint(this); });
        updateRollInfo();

        // On edit the belt is fixed, so its photo is loaded straight away; on
        // create it follows the dropdown.
        var $photo = $('#belt_formula_photo');
        ProductPhoto.load($photo.data('product') || $('#belt_formula_product').val(), $photo);

        $('#belt_formula_product').on('change', function () {
            ProductPhoto.load($(this).val(), $photo);
        });
    });

    var materialOptions = `@foreach($rawmaterial as $mat)<option value="{{ $mat->id }}" data-uom="{{ strtoupper($mat->uomName->uom_name ?? '') }}">{{ \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) }}</option>@endforeach`;

    // Only the size chart is needed here now - the gm/meter raw materials belong
    // to Roll Production and are maintained on the Niwar Code screen.
    var niwarTypeData = {!! $niwarTypes->mapWithKeys(function ($n) {
        return [$n->id => [
            'type' => $n->type,
            'code' => $n->code,
            'inchPerMeter' => (float) ($n->inch_per_meter ?: 39.37),
            'sizeChart' => $n->sizeChart->pluck('required_inch', 'pp_size'),
        ]];
    })->toJson() !!};

    function updateRollInfo() {
        var box = $('#roll-info');
        var niwarId = $('#belt_costing_id').find(':selected').data('niwar-id');
        var size = ($('#size_input').val() || '').trim();

        if (!niwarId || !niwarTypeData[niwarId]) {
            box.html('Select a belt costing and size to see how much roll this belt uses.');
            return;
        }

        var data = niwarTypeData[niwarId];
        var label = data.type + ' (' + data.code + ')';

        if (size === '') {
            box.html('Niwar Code <b>' + label + '</b> — enter a size to see the roll consumption.');
            return;
        }

        var requiredInch = data.sizeChart[size];

        if (requiredInch === undefined) {
            box.html('<span style="color:#c0392b">Size "' + size + '" is not on Niwar Code ' + label +
                '\'s size chart, so this belt cannot be cut. Add it under Niwar Code &rarr; Manage Details.</span>');
            return;
        }

        var meter = requiredInch / data.inchPerMeter;
        box.html('1 belt of size <b>' + size + '</b> uses <b>' + meter.toFixed(3) + ' mtr</b> of ' + label +
            ' roll (' + requiredInch + '" &divide; ' + data.inchPerMeter + '). Deducted from the roll at cutting time.');
    }

    function updateUomHint(selectEl) {
        var $hint = $(selectEl).closest('tr').find('.uom-hint');
        var uom = $(selectEl).find(':selected').data('uom');
        if (!uom) {
            $hint.text('');
        } else if (uom === 'KG') {
            $hint.text('Enter in grams');
        } else {
            $hint.text('Unit: ' + uom);
        }
    }

    document.getElementById('add_rawmaterial').addEventListener('click', function () {
        var row = document.createElement('tr');
        row.innerHTML =
            '<td><select name="material[]" class="form-control js-example-basic-single material-select" onchange="updateUomHint(this)">' +
            '<option value="">Select raw material</option>' + materialOptions + '</select></td>' +
            '<td><input type="text" name="qty[]" class="form-control"><small class="uom-hint text-muted"></small></td>' +
            '<td class="text-center"><a href="javascript:void(0)" onclick="removeRow(this)" title="Remove"><i class="fa fa-trash"></i></a></td>';
        document.getElementById('material-rows').appendChild(row);
        $(row).find('.js-example-basic-single').select2();
    });

    function removeRow(el) {
        var rows = document.getElementById('material-rows').rows;
        if (rows.length > 1) {
            el.closest('tr').remove();
        }
    }

    /**
     * A semi product belongs to one niwar code, so only the ones under the chosen
     * belt costing's niwar can be cut into this belt. Anything else in the list
     * would just be a way to record an impossible combination.
     */
    function filterRollFormulas() {
        var niwarId = String($('#belt_costing_id').find(':selected').data('niwar-id') || '');
        var select = $('#roll_formula_id');
        var current = select.val();
        var stillValid = false;

        select.find('option').each(function () {
            var option = $(this);

            if (option.val() === '') {
                return;
            }

            var belongs = String(option.data('niwar-id')) === niwarId;
            option.prop('disabled', !belongs).toggle(belongs);

            if (belongs && option.val() === current) {
                stillValid = true;
            }
        });

        if (!stillValid) {
            select.val('');
        }
        select.trigger('change.select2');
    }

    $('#belt_costing_id').on('change', function () {
        updateRollInfo();
        filterRollFormulas();
    });

    filterRollFormulas();
    $('#size_input').on('input blur', updateRollInfo);
</script>
