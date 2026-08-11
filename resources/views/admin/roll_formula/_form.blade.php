{{--
    Shared body of the roll formula create/edit forms. $data is null on create.
    The material tables are built per niwar category by the script below, so the
    markup here is only the header fields and the container they drop into.
--}}
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

<p class="text-muted">
    A roll formula is <b>per 1 meter</b> of niwar, and has no size &mdash; the semi product it makes is cut to size later, in Belt Cutting.
    The niwar code fixes each category's gm/meter (Mono, Roto &hellip;); here you pick the actual raw materials that add up to it.
</p>

{{--
    Belt first, then the niwar it is woven on. Those two decide everything else,
    including the roll's own stock name - which is why there is no semi product
    field: asking for it only invited two names for the same thing.
--}}
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <label class="control-label">Belt Product This Roll Becomes <span class="text-danger">*</span></label>
            <select name="belt_product_id" id="belt_product_id" class="form-control" style="width:100%;" required>
                @if($data && $data->belt_product_id)
                    <option value="{{ $data->belt_product_id }}" selected>{{ trim(($data->belt_product_item->product_name ?? ('Product #' . $data->belt_product_id)) . ' ' . (($data->belt_product_item->item_code ?? null) ? '[' . $data->belt_product_item->item_code . ']' : '')) }}</option>
                @endif
            </select>
            <small class="text-muted">
                Pick the belt by <b>name</b> &mdash; the size on it is ignored, because a roll has no size, it is only meters.
                When this roll is cut, Belt Cutting offers exactly this product's own sizes.
            </small>
        </div>
    </div>
    {{-- Which belt this actually is, as a picture rather than a code. --}}
    <div class="col-md-1" style="padding-left:0;">
        <div class="form-group product-photo-box">
            <label class="control-label" style="display:block;">Photo</label>
            <img class="product-photo" id="belt_product_photo" data-product="{{ $data->belt_product_id ?? '' }}" style="display:none;" title="Click to enlarge">
            <span class="product-photo-empty"></span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Niwar Code <span class="text-danger">*</span></label>
            <select name="niwar_code_id" id="niwar_code_id" class="form-control js-example-basic-single" required>
                <option value="">Select niwar code</option>
                @foreach($niwarCodes as $n)
                    <option value="{{ $n->id }}" {{ ($data->niwar_code_id ?? old('niwar_code_id')) == $n->id ? 'selected' : '' }}>{{ $n->label }}</option>
                @endforeach
            </select>
            <small class="text-muted">Fixes the categories below, and the size chart used when cutting.</small>
        </div>
    </div>
    @if($data)
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Current Version</label>
                <input type="text" class="form-control" value="V{{ $data->version }}" readonly>
                <small class="text-muted">Changing the numbers below saves a new version.</small>
            </div>
        </div>
    @endif
</div>

<div class="row">
    <div class="col-md-9">
        <div class="form-group">
            <label class="control-label">Roll Stocked As <small class="text-muted">(automatic)</small></label>
            <input type="text" id="semi_product_preview" class="form-control" readonly
                   value="{{ $data->product_item->product_name ?? '' }}"
                   placeholder="Pick a belt product and niwar code above">
            <small class="text-muted">
                The size-less semi product this roll is held in stock as, in Meter. Named from the belt and niwar above,
                and created automatically &mdash; @if($data) it does not change when the formula is edited. @else you do not need to enter it. @endif
            </small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Effective Date</label>
            <input type="date" name="effective_date" class="form-control"
                   value="{{ old('effective_date', $data && $data->effective_date ? $data->effective_date->format('Y-m-d') : date('Y-m-d')) }}">
        </div>
    </div>
    @if($data)
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Status</label>
                <select name="status" class="form-control">
                    <option value="active" {{ ($data->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ ($data->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <small class="text-muted">Inactive keeps history readable but blocks new batches.</small>
            </div>
        </div>
    @endif
    <div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Notes</label>
            <input type="text" name="notes" class="form-control" maxlength="1000"
                   value="{{ old('notes', $data->notes ?? '') }}" placeholder="Why this recipe / what changed">
        </div>
    </div>
</div>

@if($data && ($usedInBatches ?? 0) > 0)
    <div class="alert alert-info" style="background:#eef6fd;color:#333;">
        This formula has already been used by <b>{{ $usedInBatches }}</b> roll batch(es). Changing any gm/meter below
        saves it as <b>version {{ $data->version + 1 }}</b> &mdash; those batches keep showing version they were woven on.
    </div>
@endif

<div id="categories-container"></div>

<div style="margin-top:20px;">
    <button type="submit" id="btnSaveFormula" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.roll_formula.list') }}" class="btn btn-default">Cancel</a>
    <span id="saveHint" class="text-muted" style="margin-left:10px;"></span>
</div>
