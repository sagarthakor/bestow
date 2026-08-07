@extends('admin.layout.table_master_material')

@section('title', 'Pending Pressing')

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
                            <h4 class="page-title">{{$machine->machine_name}} Pending Pressing Batch</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.production.pressing.dashboard') }}">Pressing Dashboard</a>
                                </li>
                                <li class="active">
                                    Pressing Pending List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->




                {{--                <div class="row">--}}
                {{--                    <div class="col-sm-4">--}}
                {{--                    </div>--}}
                {{--                    <div class="col-sm-4">--}}
                {{--                    </div>--}}
                {{--                    <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">--}}
                {{--                        <a class="btn btn-primary" href="{{url('client/purchase/normal/add')}}">Add New</a>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">

                        <div class="card-box">
                            <h4 class="m-t-0 header-title">Filter</h4>
                            <form method="get">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Batch No</label>
                                            <input type="text" value="{{ request('batch_no') }}" name="batch_no" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Customer</label>
                                            <input type="text" value="{{ request('client_name') }}" name="client_name" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Finish Product</label>
                                            <input type="text" value="{{ request('product') }}" name="product" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nos</label>
                                            <input type="text" value="{{ request('nos') }}" name="nos" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Size</label>
                                            <input type="text" value="{{ request('size') }}" name="size" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="hidden-xs">&nbsp;</label>
                                        <button class="btn btn-primary btn-block waves-effect waves-light"><i class="mdi mdi-file-find"></i> Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-box table-responsive">
                            {{Form::open(['method'=>'get'])}}
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#.</th>
                                    {{--                                    <th>Machine</th>--}}
                                    <th>Batch No</th>
                                    <th>Customer</th>
                                    <th>Finish Product</th>
                                    <th>Nos</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>


                                <tbody>



                                <?php $srno=0; ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="vertical-align: top;text-align: center">{{$srno}}</td>
                                        {{--                                        <td width="12%"  style="vertical-align: top;">--}}
                                        {{--                                            {{$data->machine_name}}</td>--}}

                                        <td width="10%"  style="vertical-align: top;">
                                            <a href="{{route("admin.production.pressing.move",['batch_no' => $data->batch_no] )}}">{{$data->batch_no}} </a>
                                        </td>

                                        <td  style="vertical-align: top;text-align: center">{{$data->customer_name}}</td>
                                        <td  style="vertical-align: top;text-align: center"><x-product-name :row="$data" /></td>
                                        <td  style="vertical-align: top;text-align: center">{{$data->nos}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 10%">{{$data->size}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 10%">@if($data->pressing_status=="Y") <i class="mdi mdi-close-box" style="color: green"></i> Complete @else Pending @endif</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{route("admin.production.pressing.move",['batch_no' => $data->batch_no] )}}">View</a>
                                            <a class="btn btn-sm btn-danger" href="{{url("production/pressing/delete/".$data->batch_no)}}">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </form>
                            {{$list->appends(request()->input())->links()}}

                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(".modalopen").click(function(){
                var id=$(this).data("id");
                var primary_email=$("#primary_email"+id).val();
                var secondary_email=$("#secondary_email"+id).val();
                var subject_mail=$("#subject_mail"+id).val();
                var quot_id=$("#quot_id"+id).val();

                $("#to_email").val(primary_email+','+secondary_email);
                $("#qid").val(quot_id);
                $("#to_subject").val(subject_mail);
            });

            ClassicEditor
                .create( document.querySelector( '#to_body' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // Using jQuery.

            $(function() {
                $('form').each(function() {
                    $(this).find('input').keypress(function(e) {
                        // Enter pressed?
                        if(e.which == 10 || e.which == 13) {
                            this.form.submit();
                        }
                    });

                    $(this).find('input[type=submit]').hide();
                });
            });
        </script>
        <script>
            $( function() {
                $( "#start_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            } );
        </script>
@endsection
