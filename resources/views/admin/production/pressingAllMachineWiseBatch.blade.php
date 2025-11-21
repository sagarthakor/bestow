@extends('admin.layout.table_master')

@section('title', 'All Pressing Process')

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
                            <h4 class="page-title">All Pressing Batch</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.production.pressing.dashboard') }}">Pressing Dashboard</a>
                                </li>
                                <li class="active">
                                    Pressing All List
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

                        <div class="card-box table-responsive">
                            {{Form::open(['method'=>'get'])}}
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#.</th>
                                    <th>Machine</th>
                                    <th>Batch No</th>
                                    <th>Customer</th>
                                    <th>Finish Product</th>
                                    <th>Nos</th>
                                    <th>Pressing Nos</th>
                                    <th>Size</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <td>



                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['machine'])){echo $_GET['machine'];} ?>" name="machine" class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['batch_no'])){echo $_GET['batch_no'];} ?>" name="batch_no"  class="listSearchContributor inputElement" id="batch_no" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name"  class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" name="product" class="listSearchContributor inputElement" value="<?php if(isset($_GET['product'])){echo $_GET['product'];} ?>" >
                                    </td>
                                    <td>
                                        <input type="text" name="nos" style="width:85px;border-radius: 1px;
                    box-shadow: none;
                    border: 1px solid #cccccc;height: 30px;padding: 3px 8px;"   value="<?php if(isset($_GET['nos'])){echo $_GET['nos'];} ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="total_pressing" style="width:85px;border-radius: 1px;
                    box-shadow: none;
                    border: 1px solid #cccccc;height: 30px;padding: 3px 8px;"   value="<?php if(isset($_GET['total_pressing'])){echo $_GET['total_pressing'];} ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="size" class="listSearchContributor inputElement" value="<?php if(isset($_GET['size'])){echo $_GET['size'];} ?>">
                                    </td>
                                    <td>
                                        <select name="status" class="listSearchContributor inputElement">
                                            <option value="<?php if(isset($_GET['status'])){ echo $_GET['status']; }else{echo "";}?>"><?php if(isset($_GET['status'])){ if($_GET['status']=="Y"){ echo "Complete";}else if($_GET['status']==""){echo "All";}else if($_GET['status']=="N"){echo "Pending";}else{echo "";}}?></option>
                                            <option value="">All</option>
                                            <option value="Y">Complete</option>
                                            <option value="N">Pending</option>
                                        </select>
                                    </td>

                                    <td><button class="btn btn-brown">search</button></td>
                                </tr>
                                </thead>


                                <tbody>



                                <?php $srno=0; ?>
                                @foreach($production as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="vertical-align: top;text-align: center">{{$srno}}</td>
                                        <td width="12%"  style="vertical-align: top;">
                                            {{$data->machine_name}}</td>
                                        {{--                                        <td width="12%"  style="vertical-align: top;">--}}
                                        {{--                                            {{$data->machine_name}}</td>--}}

                                        <td width="10%"  style="vertical-align: top;">
                                            <a href="{{route("admin.production.pressing.move",['batch_no' => $data->batch_no] )}}">{{$data->batch_no}} </a>
                                        </td>

                                        <td  style="vertical-align: top;text-align: center">{{$data->customer_name}}</td>
                                        <td  style="vertical-align: top;text-align: center">{{$data->product_name}}</td>
                                        <td  style="vertical-align: top;text-align: center">{{$data->nos}}</td>
                                        <td  style="vertical-align: top;text-align: center">{{$data->total_pressing}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 10%">{{$data->size}}</td>
                                        <td  style="vertical-align: top;text-align: center;width: 10%">@if($data->pressing_status=="Y") <i class="mdi mdi-checkbox-marked" style="color: green"></i> Complete @else <i class="mdi mdi-close-box" style="color: green"></i> Pending @endif</td>
                                        <td>
                                            <a href="{{route("admin.production.pressing.move",['batch_no' => $data->batch_no] )}}">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            </form>
                            {{$production->appends(request()->input())->links()}}

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
