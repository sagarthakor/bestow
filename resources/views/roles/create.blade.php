@extends('admin.layout.master_material')

@section('title', 'Add Role')

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
                            <h4 class="page-title">Role Create </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.roles.list')}}">Role List </a>
                                </li>
                                <li>
                                    Add Role
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->



                <div class="row">
                    <div class="col-xs-12">
                        <div class="card-box">

                            <div class="row">
                                @if ($errors->any())
                                    <div class="col-xs-12">
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xs-12">

                                    {!! Form::open(array('route' => 'admin.roles.store','method'=>'POST')) !!}
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <strong>Name:</strong>
                                                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12">

                                            <table border="1" class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Full Access</th>
                                                    <th>Create</th>
                                                    <th>Update</th>
                                                    <th>View</th>
                                                    <th>Delete</th>
                                                    <th>Print/View</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                @foreach (config('rolePermissions') as $area => $entities)
                                                    <tr>
                                                        <td>{{ $area }}</td>
                                                    </tr>
                                                    @foreach ($entities as $entity => $actions)
                                                        <tr>
                                                            <td class="text-right">{{ $entity }}</td>

                                                            <td class="text-center">
                                                                <input type="checkbox" onclick=allowFullAccess("{{strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $entity))}}") class="chk_events {{strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $entity))}}"></td>
                                                            @foreach ($actions as $action => $permission)
                                                                @if(in_array($permission,['quot_report_view', 'sales_report_view', 'invoice_report_view', 'sales_summary_report_view', 'product_wise_sales_report_view', 'salesman_wise_sales_report_view', 'stock_available_report_view', 'raw_material_pending_report_view', 'production_pending_report_view', 'stitching_pending_report_view', 'pressing_pending_report_view', 'packaging_pending_report_view', 'belt_production_view']))
                                                                    <td></td><td></td> <td class="text-center"><input type="checkbox" value="{{$permission}}" name="permission[]" class="chk_events {{strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $entity))}} chk_events"></td>
                                                                @else
                                                                <td class="text-center"><input type="checkbox" value="{{$permission}}" name="permission[]" class="chk_events {{strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $entity))}} chk_events"></td>
                                                                @endif
                                                            @endforeach

                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}


                                </div>

                            </div>
                            <!-- end row -->


                            <!-- end row -->


                        </div> <!-- end card-box -->
                    </div><!-- end col-->

                </div>
                <!-- end row -->


            </div> <!-- container -->

        </div> <!-- content -->
        <script src="/admin/assets/js/jquery.min.js"></script>

        <script>
    function allowFullAccess(className){

        $("."+className).prop('checked', $("."+className).prop("checked"));
        $("."+className).change(function () {

            if($("."+className).length==$("."+className+":checked").length)
                $("."+className).prop('checked', true);
            else
                $("."+className).prop('checked', false);
        });

      /*  $("."+className).is(':checked');

        if($("."+className).is(':checked')){
            $("."+className).prop('checked',true);
        }else{
            $("."+className).prop('checked',false);
        }*/
        /*if(parseFloat(totalClass) == parseFloat(totalCheckedClass)){
        }*/
    }
    /*$(document).ready(function() {
        $(".chk_events").change(function (){
            $('.chk_events').each(function() {
                var classNames = $(this).attr('class');
                console.log('Class attribute(s) for this element:', classNames);
            });

        });
    });*/

</script>
@endsection
