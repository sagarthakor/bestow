@extends('admin.layout.table_master_material')

@section('title', 'List | Purchase Request')

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
                            <h4 class="page-title">Purchase Request </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li class="active">
                                    Purchase Request
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
                                            <label>Order No</label>
                                            <input type="text" value="{{ request('request_no') }}" name="request_no" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="date" class="form-control" name="from_date" value="@if(isset($_GET['from_date'])) {{date('Y-m-d',strtotime($_GET['from_date']))}} @endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="date" class="form-control" name="to_date" value="@if(isset($_GET['to_date'])) {{$_GET['to_date']}} @endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>User</label>
                                            <input type="text" value="{{ request('user') }}" name="user" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Purchase No.</label>
                                            <input type="text" value="{{ request('purchase_no') }}" name="purchase_no" class="form-control">
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
                                    <th>Sr.</th>
                                    <th>
                                       Order No

                                    </th>

                                    <th>
                                        Order Date & Time
                                    </th>

                                    <th>User</th>
                                    <th>Purchase No.</th>

                                </tr>
                                </thead>


                                <tbody>



                                <?php $srno=0; ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td   style="vertical-align: top;width: 5%;text-align: center">{{$srno}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 5%;">
                                            <a href="{{ route('admin.requirement.view',['id' => $data->id]) }}">{{$data->order_no}}</a></td>

                                        <td width="10%"  style="vertical-align: top;text-align: center;width: 20%">
                                            {{date('d-m-Y h:i:s a',strtotime($data->timestamp))}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td  style="vertical-align: top;text-align: center;width: 20%">{{$data->first_name}} {{$data->last_name}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 20%">{{$data->po_no}}</td>


                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </form>
{{--                            {{$list->appends(request()->input())->links()}}--}}

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
