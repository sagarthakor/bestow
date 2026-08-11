{{--
    Shared header for the belt formula create/edit forms. $formula is null on
    create (product is picked) and set on edit (product is fixed - changing it
    would silently move a formula to another belt).
--}}
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Belt Product</label>
            @if($formula)
                <input type="text" class="form-control" value="{{ \App\product::nameWithVariantInline($formula->product_item->product_name ?? '-', $formula->product_item->value1 ?? null, $formula->product_item->value2 ?? null) }}" readonly>
            @else
                <select name="product" id="belt_formula_product" class="form-control js-example-basic-single" required>
                    <option value="">Select belt product</option>
                    @foreach($product as $prod)
                        <option value="{{ $prod->id }}">{{ \App\product::nameWithVariantInline($prod->product_name, $prod->value1 ?? null, $prod->value2 ?? null) }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
    {{-- The picked belt, as a picture - the dropdown labels differ only by size. --}}
    <div class="col-md-2">
        <div class="form-group product-photo-box" style="text-align:left;">
            <label class="control-label" style="display:block;">Photo</label>
            <img class="product-photo" id="belt_formula_photo" data-product="{{ $formula->product ?? '' }}" style="display:none;" title="Click to enlarge">
            <span class="product-photo-empty"></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Belt Costing</label>
            <select name="belt_costing_id" id="belt_costing_id" class="form-control js-example-basic-single" required>
                <option value="">Select bukkal / niwar combination</option>
                @foreach($beltCosting as $cost)
                    <option value="{{ $cost->id }}" data-niwar-id="{{ $cost->niwar_id }}" {{ $formula && $cost->id == $formula->belt_costing_id ? 'selected' : '' }}>{{ $cost->bukkal->type ?? '-' }} / {{ $cost->niwar->type ?? '-' }} &mdash; Total: {{ number_format($cost->total_cost, 2) }}</option>
                @endforeach
            </select>
            <small class="text-muted">Its niwar code decides the size chart used when cutting.</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Size</label>
            {{ Form::text('size', null, ['class' => 'form-control', 'id' => 'size_input', 'placeholder' => 'e.g. 30', 'required']) }}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Nos</label>
            {{ Form::text('nos', 1, ['class' => 'form-control', 'readonly' => 'readonly']) }}
            <small class="text-muted">Formula is always per 1 belt</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Cut From Semi Product (roll)</label>
            <select name="roll_formula_id" id="roll_formula_id" class="form-control js-example-basic-single">
                <option value="">Any roll of this niwar code</option>
                @foreach($rollFormulas as $rf)
                    <option value="{{ $rf->id }}" data-niwar-id="{{ $rf->niwar_code_id }}"
                        {{ $formula && $rf->id == $formula->roll_formula_id ? 'selected' : '' }}>{{ $rf->name }}</option>
                @endforeach
            </select>
            <small class="text-muted">
                One niwar code weaves several semi products &mdash; pick the one this belt is actually
                cut from, so Belt Cutting only offers it on that roll. Left blank, it is offered on
                every roll of the niwar code above.
            </small>
        </div>
    </div>
</div>
