@extends('admin.layout.table_master_material')

@section('title', 'Niwar Code List')

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
                            <h4 class="page-title">Niwar Code</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{ Session::get('software_title') }}</a></li>
                                <li>Belt</li>
                                <li class="active">Niwar Code</li>
                                <li style="text-align:right;margin-bottom:5px;">
                                    <a class="btn btn-primary" href="{{ route('admin.niwar.add') }}">Add New</a>
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                @if(session()->has('success'))
                    <div class="alert alert-info" style="background-color:#188ae2!important;">
                        <strong style="color:#fff">{{ session()->get('success') }}</strong>
                    </div>
                @endif

                <div class="card-box table-responsive">
                    <p class="text-muted">
                        A niwar code fixes what a meter of that niwar weighs and how many inches each belt size takes.
                        Both have to be set before a roll can be woven or cut &mdash; use <b>Manage Details</b>.
                    </p>

                    <table class="table table-striped belt-table" width="100%">
                        <thead>
                        <tr>
                            <th class="sr">Sr</th>
                            <th>Niwar Type</th>
                            <th>Code</th>
                            <th class="num">Rate</th>
                            <th>Raw Material Rate</th>
                            <th>Size Chart</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        @php $sr = 0; @endphp
                        @forelse($items as $n)
                            @php $sr++; @endphp
                            <tr>
                                <td class="sr">{{ $sr }}</td>
                                <td><b>{{ $n->type }}</b></td>
                                <td>{{ $n->code }}</td>
                                <td class="num">{{ number_format($n->rate, 2) }}</td>

                                {{-- A code with no rate rows or no size chart cannot make a roll or
                                     be cut, so it says so here rather than failing later on. --}}
                                <td>
                                    @if($n->materials->count())
                                        {{ $n->materials->count() }} categor{{ $n->materials->count() == 1 ? 'y' : 'ies' }}
                                        &middot; {{ rtrim(rtrim(number_format($n->gramsPerMeter(), 2), '0'), '.') }} g/mtr
                                    @else
                                        <span class="text-danger">not set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($n->sizeChart->count())
                                        {{ $n->sizeChart->count() }} sizes
                                        <span class="text-muted">({{ $n->sizeChart->first()->pp_size }}&ndash;{{ $n->sizeChart->last()->pp_size }})</span>
                                    @else
                                        <span class="text-danger">not set</span>
                                    @endif
                                </td>

                                <td class="belt-actions-cell">
                                    <a class="belt-act" href="{{ route('admin.niwar.details', $n->id) }}"><i class="mdi mdi-tune"></i>Manage Details</a>
                                    <a class="belt-act" href="{{ route('admin.niwar.edit', $n->id) }}"><i class="mdi mdi-pencil"></i>Edit</a>
                                    <a class="belt-act belt-act-danger" href="{{ route('admin.niwar.delete', $n->id) }}"
                                       onclick="return confirm('Delete niwar code {{ $n->type }}/{{ $n->code }}?')"><i class="mdi mdi-delete"></i>Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="belt-empty">No niwar code yet &mdash; add one to start belt production</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

@endsection
