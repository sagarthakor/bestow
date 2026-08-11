@extends('admin.layout.table_master_material')

@section('title', 'Belt Costing List')

@section('sidebar')
    @parent
@endsection

@section('content')

    @include('admin.belt._list_styles')

    <div class="content-page">
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Belt Costing</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a></li>
                                <li>Belt</li>
                                <li class="active">Belt Costing</li>
                                <li style="text-align:right;margin-bottom:5px;">
                                    <a class="btn btn-primary" href="{{ route('admin.belt.add') }}">Add New</a>
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                        <strong style="color: #fff">{{ session()->get('success') }}</strong>
                    </div>
                @endif

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                            <table class="table table-striped belt-table dt-responsive nowrap" width="100%">
                                <thead>
                                <tr>
                                    <th>SR</th>
                                    <th>Bukkal Type</th>
                                    <th>Bukkal Code</th>
                                    <th>Bukkal Rate</th>
                                    <th>Miter (1Miter=39 inch)</th>
                                    <th>Kadi / Slider</th>
                                    <th>Kadi / Slider Rate</th>
                                    <th>Niwar Type</th>
                                    <th>Rate</th>
                                    <th>Size Lable</th>
                                    <th>Panni Packing Rate</th>
                                    <th>Total Costing</th>
                                    <th>Fitting</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php $sr = 0; @endphp
                                @foreach($data as $cost)
                                    @php $sr++; @endphp
                                    <tr>
                                        <td>{{ $sr }}</td>
                                        <td>{{ $cost->bukkal->type }}</td>
                                        <td>{{ $cost->bukkal->code }}</td>
                                        <td>{{ $cost->bukkal_rate }}</td>
                                        <td>{{ $cost->miter }}</td>
                                        <td>{{ $cost->kadi_qty }}</td>
                                        <td>{{ $cost->kadi_rate }}</td>
                                        <td>{{ $cost->niwar->type }}</td>
                                        <td>{{ $cost->niwar_rate }}</td>
                                        <td>{{ $cost->size_label }}</td>
                                        <td>{{ $cost->panni_packing }}</td>
                                        <td>{{ $cost->total_cost }}</td>

                                        {{-- Whether cutting will actually deduct anything for this
                                             costing, which is invisible from the rates alone. --}}
                                        <td>
                                            @if($cost->fittings->count())
                                                {{ $cost->fittings->count() }} component(s)
                                            @else
                                                <span class="text-danger">not set</span>
                                            @endif
                                        </td>
                                        <td class="belt-actions-cell">
                                            <a class="belt-act" href="{{ route('admin.belt.edit', $cost->id) }}"><i class="mdi mdi-pencil"></i>Edit</a>
                                            <a class="belt-act belt-act-danger" href="{{ route('admin.belt.delete', $cost->id) }}"
                                               onclick="return confirm('Delete this belt costing?')"><i class="mdi mdi-delete"></i>Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
