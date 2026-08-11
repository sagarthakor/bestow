<script>
    var categoriesUrl = "{{ route('admin.roll_formula.categories') }}";

    var materialOptions = `@foreach($rawmaterial as $mat)<option value="{{ $mat->id }}">{{ \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) }}</option>@endforeach`;

    // On edit, the saved rows are reinstated into their category once the
    // category tables have been fetched for the selected niwar code.
    var savedRows = {!! json_encode($savedRows ?? []) !!};

    var beltProductsUrl = "{{ route('admin.roll_formula.belt_products') }}";

    // Only meaningful while creating - an existing roll keeps the stock name it
    // was created with, because stock already sits against it.
    var isNewFormula = {{ $data ? 'false' : 'true' }};
    var niwarLabels = {!! json_encode($niwarCodes->mapWithKeys(fn ($n) => [$n->id => $n->label])) !!};

    $(document).ready(function () {
        $('.js-example-basic-single').select2();
        attachBeltProductSearch();
        loadCategories();
        updateSemiProductPreview();
        updateBeltPhoto();
    });

    /**
     * The belt is picked by name from a search box, so the photo is what
     * confirms the right one was picked.
     */
    function updateBeltPhoto() {
        var $photo = $('#belt_product_photo');
        ProductPhoto.load($('#belt_product_id').val() || $photo.data('product'), $photo);
    }

    /**
     * Mirrors RollFormulaController::semiProductName so the operator sees the
     * name the roll will be stocked under before saving, not after.
     */
    function updateSemiProductPreview() {
        if (!isNewFormula) {
            return;
        }

        // Read from the option itself rather than from whatever the last click
        // handed us: the field must also be right after a validation bounce, or
        // any other path that sets the select without firing select2's event.
        var option = $('#belt_product_id').find('option:selected');
        var belt = (option.data('stock-name') || '').toString();

        if (!belt) {
            // Fall back to the visible label with its " — N sizes" tail removed.
            belt = (option.text() || '').split('—')[0].trim();
        }

        var niwar = niwarLabels[$('#niwar_code_id').val()] || '';

        $('#semi_product_preview').val(belt ? (belt + ' ROLL' + (niwar ? ' - ' + niwar : '')) : '');
    }

    /**
     * Searched server-side and grouped by product family - hundreds of belts
     * exist, and each carries a dozen sizes that are irrelevant to a roll.
     */
    function attachBeltProductSearch() {
        $('#belt_product_id').select2({
            placeholder: 'Search item code / name...',
            allowClear: true,
            width: '100%',
            // One character is enough - an item code alone is often that short.
            minimumInputLength: 1,
            ajax: {
                url: beltProductsUrl,
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {search: params.term};
                },
                processResults: function (res) {
                    return {
                        results: (res.products || []).map(function (p) {
                            return {
                                id: p.id,
                                // Kept apart from the size count so the preview can
                                // reuse exactly what the server will name it.
                                stock_name: p.name + (p.item_code ? ' [' + p.item_code + ']' : ''),
                                text: p.name + (p.item_code ? ' [' + p.item_code + ']' : '') +
                                    ' — ' + p.size_count + ' size' + (p.size_count == 1 ? '' : 's')
                            };
                        })
                    };
                }
            }
        });

        // select2 rebuilds the option, so the stock name is stamped onto it and
        // read back from there by updateSemiProductPreview().
        $('#belt_product_id').on('select2:select', function (e) {
            $('#belt_product_id').find('option:selected').attr('data-stock-name', e.params.data.stock_name || '');
            updateSemiProductPreview();
            updateBeltPhoto();
        });
        $('#belt_product_id').on('select2:clear change', function () {
            updateSemiProductPreview();
            updateBeltPhoto();
        });
    }

    function loadCategories() {
        var niwarId = $('#niwar_code_id').val();
        var container = $('#categories-container').empty();

        if (!niwarId) {
            container.html('<div class="alert alert-info" style="background:#eef6fd;color:#333;">Select a niwar code to see which raw material categories this formula has to fill.</div>');
            return;
        }

        $.get(categoriesUrl, {niwar_code_id: niwarId}, function (res) {
            if (!res.categories || res.categories.length === 0) {
                container.html('<div class="alert alert-danger">This niwar code has no gm/meter rows yet. Set them in Niwar Code &rarr; Manage Details first.</div>');
                return;
            }

            res.categories.forEach(function (c) { buildCategory(container, c); });
            recalc();
        });
    }

    function buildCategory(container, category) {
        var box = $('<div class="card-box category-box" style="margin-top:15px;border:1px solid #ddd;"></div>')
            .attr('data-category-id', category.id)
            .attr('data-target', category.gm_per_meter);

        // A group category (Roto) is a total filled by several threads; a plain
        // one (Mono) is usually a single material. Saying so up front is what
        // stops the 12.1 vs 12.3 mistake this validation exists to catch.
        var blurb = category.is_group
            ? 'This is a <b>composite</b> material. The threads below must add up to exactly <b>'
              + category.gm_per_meter + ' gm per meter</b>.'
            : 'The raw material(s) below must add up to exactly <b>' + category.gm_per_meter + ' gm per meter</b>.';

        box.html(
            '<h4>' + category.name + (category.is_group ? ' <span class="label label-info">Composite</span>' : '') + '</h4>' +
            '<p class="text-muted" style="margin-bottom:10px;">' + blurb + '</p>' +
            '<table class="table table-bordered">' +
            '<thead><tr><th>Raw Material</th><th style="width:24%;">Gm per Meter</th><th style="width:8%;"></th></tr></thead>' +
            '<tbody class="category-rows"></tbody>' +
            '<tfoot><tr>' +
            '<th class="text-right">Required</th>' +
            '<th class="text-right required-cell">' + category.gm_per_meter + '</th><th></th>' +
            '</tr><tr>' +
            '<th class="text-right">Entered</th>' +
            '<th class="text-right entered-cell">0</th><th></th>' +
            '</tr><tr>' +
            '<th class="text-right">Difference</th>' +
            '<th class="text-right difference-cell">-</th><th></th>' +
            '</tr></tfoot>' +
            '</table>' +
            '<button type="button" class="btn btn-default btn-sm add-row">+ Add Raw Material</button>' +
            '<span class="category-sum" style="margin-left:12px;"></span>'
        );

        container.append(box);

        var rows = (savedRows[category.id] || []);
        if (rows.length === 0) {
            rows = [{material: '', gm_per_meter: ''}];
        }
        rows.forEach(function (r) { addRow(box, category.id, r.material, r.gm_per_meter); });

        box.find('.add-row').on('click', function () {
            addRow(box, category.id, '', '');
            recalc();
        });
    }

    function addRow(box, categoryId, materialId, gm) {
        var row = $('<tr class="category-row"></tr>');
        row.html(
            '<td><select name="material[]" class="form-control js-example-basic-single category-material">' +
            '<option value="">Select raw material</option>' + materialOptions + '</select>' +
            '<input type="hidden" name="category[]" value="' + categoryId + '"></td>' +
            '<td><input type="text" name="gm_per_meter[]" class="form-control gm-input" value="' + (gm || '') + '"></td>' +
            '<td class="text-center"><a href="javascript:void(0)" class="remove-row" title="Remove"><i class="fa fa-trash"></i></a></td>'
        );

        box.find('.category-rows').append(row);

        var select = row.find('.category-material');
        select.val(materialId || '');
        select.select2();

        row.find('.remove-row').on('click', function () {
            if (box.find('.category-row').length > 1) {
                row.remove();
            }
            recalc();
        });
    }

    /**
     * Live "X of Y gm/meter" per category, so a mismatch shows before the server
     * rejects the save.
     */
    function recalc() {
        var allBalanced = true;

        $('.category-box').each(function () {
            var box = $(this);
            var target = parseFloat(box.data('target')) || 0;
            var sum = 0;

            box.find('.gm-input').each(function () {
                sum += parseFloat($(this).val()) || 0;
            });
            sum = Math.round(sum * 10000) / 10000;

            // Same half-milligram tolerance the server applies, so the screen
            // never shows a tick on something the save would then reject.
            var diff = Math.round((target - sum) * 10000) / 10000;
            var ok = Math.abs(diff) <= 0.0005;
            var colour = ok ? '#27ae60' : '#c0392b';

            if (!ok) { allBalanced = false; }

            box.find('.entered-cell').html('<span style="color:' + colour + '">' + sum + '</span>');
            box.find('.difference-cell').html('<span style="color:' + colour + '">' +
                (ok ? '0 &check;' : (diff > 0 ? 'short by ' + Math.abs(diff) : 'over by ' + Math.abs(diff))) +
                '</span>');

            box.find('.category-sum').html('<b style="color:' + colour + '">' +
                sum + ' / ' + target + ' gm per meter' +
                (ok ? ' &check;' : (diff > 0 ? ' &mdash; short by ' + Math.abs(diff) : ' &mdash; over by ' + Math.abs(diff))) +
                '</b>');
        });

        var hasCategories = $('.category-box').length > 0;
        $('#btnSaveFormula').prop('disabled', hasCategories && !allBalanced);
        $('#saveHint').text(hasCategories && !allBalanced
            ? 'Every category must add up to its niwar rate exactly before this can be saved.'
            : '');
    }

    // Picking an existing semi product and typing a new name are mutually
    // exclusive, so each clears the other.
    $('#product').on('change', function () {
        if ($(this).val()) { $('#new_product_name').val(''); }
    });
    $('#new_product_name').on('input', function () {
        if ($(this).val()) { $('#product').val('').trigger('change.select2'); }
    });

    $('#niwar_code_id').on('change', function () {
        savedRows = {};
        loadCategories();
        updateSemiProductPreview();
    });
    $(document).on('input', '.gm-input', recalc);
</script>
