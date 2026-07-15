@extends('admin.layout.table_master_material')

@section('title', 'List of Type')

@section('sidebar')
    @parent

@endsection

@section('content')
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">


                        <div class="row">
                         <div class="col-xs-12">
                            <div class="page-title-box">
                                <h4 class="page-title">Type</h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">{{Session::get('software_title')}}</a>
                                    </li>

                                    <li class="active">
                                        Type List
                                    </li>
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{route('admin.type.add')}}">Add New</a>
                                    </li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                <div class="row">
                    @if(session()->has('message'))
                    <div class="col-sm-12">
                        <div class="alert alert-info">
                            <strong>{{session()->get('message')}}</strong>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-12">

                        <div class="card-box table-responsive">

                            <table  class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                      <th >Sr.</th>
                                      <th>Type Name</th>
                                      <th></th>
                                  </tr>
                              </thead>


                              <tbody>
                                <?php $srno=0; $pagi=$data; ?>
                                @foreach($data as $data)
                                <?php $srno++; ?>
                                <tr>
                                  <td  style="vertical-align: top;width: 5%">{{$srno}}</td>
                                  <td  style="vertical-align: top;">{{$data->type_name}}</td>

                            <td  style="vertical-align: top;width: 5%">
                                @can('customer_type_update')
                                    <a href="{{route('admin.type.edit',['id' => $data->id])}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                @endcan
                                @can('customer_type_delete')
                                        <a href="{{route('admin.type.delete',['id' => $data->id])}}" class="btn btn-xs btn-danger waves-effect" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</a>
                                @endcan
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
              <div class="text-center">{{ $pagi->appends(request()->input())->links() }}</div>
          </div>
      </div>
  </div>



  <!-- end row -->



</div> <!-- container -->

</div> <!-- content -->

@endsection
