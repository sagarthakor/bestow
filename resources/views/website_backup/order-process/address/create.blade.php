<div class="modal fade" id="createAddress" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Your Address</h5>
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
                <form class="addressForm" action="{{ route('website.order.checkout.address.create') }}" method="post">
                    @csrf
                    <input type="hidden" value="{{ Route::getCurrentRoute()->getName() }}" name="page">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required
                               value="{{ old('first_name') ? old('first_name') : Session::get('orderUser')->name }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact</label>
                                <input type="text" name="mobile" class="form-control"
                                       onkeypress="CLEVER.numericInput(event)"
                                       value="{{ old('mobile') ? old('mobile') : Session::get('orderUser')->mobile }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control" value="{{ old('email') }}"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="address" placeholder="Enter Your Address" class="form-control" cols="30"
                                  rows="4" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Country</label>
                            <select name="country_id" data-url="{{route('ajax.states')}}"
                                    data-target="state_id" data-string="country_id"
                                    class="form-control select-change">
                                <option value="">Choose Country</option>
                                @foreach($countries as $id => $title)
                                    <option value="{{$id}}">{{$title}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group col-md-6">
                            <label>State</label>
                            <select name="state_id" data-url="{{route('ajax.cities')}}" data-target="city_id1"
                                    data-string='state_id' class="form-control select-change" id="state_id">
                                <option value="">Choose State</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>City</label>
                            <select name="city_id" data-url="" class="form-control select-change"
                                    id="city_id1">
                                <option value="">Choose City</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>PinCode</label>
                                <input type="text" name="pin_code" class="form-control"
                                       onkeypress="CLEVER.numericInput(event)"
                                       value="{{ old('pin_code') }}"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address Type</label>
                        <select name="type" class="form-select selectpicker" data-live-search="true" required>
                            <option value="1" {{ old('type') == 1 ? 'selected' : null }}>Home</option>
                            <option value="2" {{ old('type') == 2 ? 'selected' : null }}>Office</option>
                            <option value="3" {{ old('type') == 3 ? 'selected' : null }}>Other</option>
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
