<div class="modal fade" id="updateAddress" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Your Address</h5>
                <a href="{{ url()->current() }}" class="close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                @if(session('address_errors'))
                <div class="alert alert-danger">
                    <ul>
                        @foreach(session('address_errors') as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('website.order.checkout.address.update') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ Route::getCurrentRoute()->getName() }}" name="page">
                    <input type="hidden" value="{{ $selected_address->id }}" name="id">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control"
                        value="{{ $selected_address->name ? : Session::get('orderUser')->name }}"
                        required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Contact</label>
                            <input type="text" name="mobile" class="form-control"
                            onkeypress="CLEVER.numericInput(event)"
                            value="{{ $selected_address->mobile ? : Session::get('orderUser')->mobile }}"
                            required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control"
                            value="{{ $selected_address->email ? : Session::get('orderUser')->email }}"
                            required>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="address" placeholder="Enter Your Address"
                        class="form-control" cols="30" rows="4"
                        required>{{ $selected_address->address }}</textarea>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Country</label>
                            <select name="country_id" data-url="{{route('ajax.states')}}"
                            data-target="state_id" select-trigger="state_change"
                            data-string="selected_id={{$selected_address->state_id}}&country_id"
                            data-other-target="city"
                            class="form-control country_change select-change">
                            <option value="">Choose Country</option>
                            @foreach($countries as $id => $title)
                            <option
                            value="{{$id}}" {{ $selected_address->country_id == $id ? 'selected' : '' }}>{{$title}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group col-md-6">
                        <label>State</label>
                        <select name="state_id" data-url="{{route('ajax.cities')}}" data-target="city_id"
                        data-string="selected_id={{$selected_address->city_id}}&state_id"
                        class="form-control select-change state_change"
                        id="state_id">

                        <option value="">Choose State</option>
                    </select>

                </div>
                <div class="form-group col-md-6">
                    <label>City</label>
                    <select name="city_id" class="form-control city" id="city_id">
                        <option value="">Choose City</option>
                    </select>

                </div>
                <div class="form-group col-md-6">
                    <label>PinCode</label>
                    <input type="text" name="pin_code" class="form-control"
                    onkeypress="CLEVER.numericInput(event)"
                    value="{{ $selected_address->pin_code }}" required>
                </div>
            </div>
            <div class="form-group">
                <label>Address Type</label>
                <select name="type" class="form-select selectpicker" data-live-search="true">
                    <option value="1" {{ $selected_address->type == 1 ? 'selected' : null }}>Home
                    </option>
                    <option value="2" {{ $selected_address->type == 2 ? 'selected' : null }}>Office
                    </option>
                    <option value="3" {{ $selected_address->type == 3 ? 'selected' : null }}>Other
                    </option>
                </select>
            </div>
            <div class="text-center">
                <button class="btn btn-sm btn-primary" type="submit">Save</button>
                <a href="{{ url()->current() }}" class="btn btn-sm btn-dark">Close</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
