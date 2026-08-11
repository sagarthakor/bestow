@extends('admin.layout.master_material')

@section('title', 'List | Roll Formula')

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
                            <h4 class="page-title">Roll Formula</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li><a href="{{ url('admin') }}">{{Session::get('software_title')}}</a></li>
                                <li>Belt</li>
                                <li class="active">Roll Formula</li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{ route('admin.roll_formula.add') }}">Add New</a>
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                            @if(session()->has('message'))
                                <div class="col-sm-12">
                                    <div class="alert alert-info" style="background-color: #188ae2 !important">
                                        <strong style="color: #fff">{{session()->get('message')}}</strong>
                                    </div>
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="col-sm-12">
                                    <div class="alert alert-danger"><strong>{{session()->get('error')}}</strong></div>
                                </div>
                            @endif

                            <p class="text-muted">Per 1 meter of niwar, for a size-less semi product. Roll Production multiplies it by the meters being woven.</p>

                            {{ Form::open(['method' => 'get', 'route' => 'admin.roll_formula.list']) }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Semi Product</label>
                                        <input type="text" name="search" value="{{ request('search') }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('admin.roll_formula.list') }}" class="btn btn-default">Reset</a>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}

                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Semi Product</th>
                                    <th>Item Code</th>
                                    <th>Niwar Code</th>
                                    <th>Version</th>
                                    <th>Raw Materials</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $row)
                                    <tr>
                                        <td style="width:3%;text-align:center;">{{ $loop->iteration }}</td>
                                        <td><b>{{ $row->product_item->product_name ?? '-' }}</b></td>
                                        <td>{{ $row->product_item->item_code ?? '-' }}</td>
                                        <td>{{ $row->niwar->label ?? '-' }}</td>
                                        <td style="text-align:center;">
                                            <a href="javascript:void(0)" onclick="openRevisions({{ $row->id }})" title="Version history">V{{ $row->version }}</a>
                                        </td>
                                        <td style="text-align:center;">{{ $row->items_count }}</td>
                                        <td style="text-align:center;">
                                            @if($row->status == 'inactive')
                                                <span class="label label-default">Inactive</span>
                                            @else
                                                <span class="label label-success">Active</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;white-space:nowrap;">
                                            <a href="javascript:void(0)" onclick="openRevisions({{ $row->id }})" class="btn btn-sm btn-default">History</a>
                                            @can('roll_formula_update')
                                                <a href="{{ route('admin.roll_formula.edit', $row->id) }}" class="btn btn-sm btn-default">Edit</a>
                                            @endcan
                                            @can('roll_formula_delete')
                                                <a href="{{ route('admin.roll_formula.delete', $row->id) }}" class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Delete this roll formula?')">Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center" style="padding:30px;color:#999;">
                                            No roll formula yet &mdash; add one before starting roll production
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $data->links() }}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Version History Modal -->
    <div id="revisionModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; overflow:auto;">
        <div style="background:#fff; max-width:700px; margin:5% auto; padding:20px; border-radius:4px;">
            <h4>Formula Version History</h4>
            <p class="text-muted">Every version this formula has been saved in. Batches keep the version they were woven on.</p>
            <div id="revision_content">Loading...</div>
            <button type="button" class="btn btn-default" style="margin-top:10px;" onclick="document.getElementById('revisionModal').style.display='none'">Close</button>
        </div>
    </div>

    <script>
        function openRevisions(id) {
            document.getElementById('revision_content').innerHTML = 'Loading...';
            document.getElementById('revisionModal').style.display = 'block';
            $.get('{{ route("admin.roll_formula.revisions") }}', {id: id}, function (res) {
                document.getElementById('revision_content').innerHTML = res.html;
            });
        }
    </script>

@endsection
