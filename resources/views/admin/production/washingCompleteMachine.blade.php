@extends('admin.layout.table_master_material')

@section('title', 'Complete Washing')

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
                            <h4 class="page-title">Washing Complete List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{ url('admin/production/washing/dashboard') }}">Washing Dashboard</a>
                                </li>

                                <li class="active">
                                    Washing Complete List
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
                <div class="row text-center">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12">

                        @foreach($machine as $mlist)
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <a href="{{route('admin.production.washing.machine.complete',['id' => $mlist->id])}}">
                                    <div class="card-box widget-box-one bg-secondary">
                                        <div class="wigdet-one-content">
                                            <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">{{$mlist->machine_name}} </p>
                                            <h1 class="text-dark"><span data-plugin="counterup">
                                                    <?php
                                                    $i=0;
                                                    ?>
                                                    @foreach($production as $data)
                                                        @if($data->machine == $mlist->id)
                                                            <?php
                                                            $i++;
                                                            ?>
                                                        @endif
                                                    @endforeach
                                                    <?=$i?>
                                                </span>
                                            </h1>
                                            <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach

                        {{--                        <div class="card-box table-responsive">--}}
                        {{--                            {{Form::open(['method'=>'get'])}}--}}
                        {{--                            <table class="table table-striped table-bordered">--}}
                        {{--                                <thead>--}}
                        {{--                                <tr>--}}
                        {{--                                    <th>#.</th>--}}
                        {{--                                    <th>Machine</th>--}}
                        {{--                                    <th>Batch No</th>--}}
                        {{--                                    <th>Customer</th>--}}
                        {{--                                    <th>Finish Product</th>--}}
                        {{--                                    <th>Nos</th>--}}
                        {{--                                    <th>Size</th>--}}
                        {{--                                    <th>Status</th>--}}
                        {{--                                    <th>Action</th>--}}
                        {{--                                </tr>--}}
                        {{--                                <tr>--}}
                        {{--                                    <td>--}}



                        {{--                                    </td>--}}
                        {{--                                    <td>--}}
                        {{--                                        <input type="text" value="<?php if(isset($_GET['purchase_no'])){echo $_GET['purchase_no'];} ?>" name="purchase_no" class="listSearchContributor inputElement">--}}
                        {{--                                    </td>--}}
                        {{--                                    <td>--}}
                        {{--                                        <input type="text" value="<?php if(isset($_GET['po_date'])){echo $_GET['po_date'];} ?>" name="po_date"  class="listSearchContributor inputElement" id="start_date" autocomplete="off">--}}
                        {{--                                    </td>--}}
                        {{--                                    <td>--}}
                        {{--                                        <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name"  class="listSearchContributor inputElement">--}}
                        {{--                                    </td>--}}
                        {{--                                    <td>--}}
                        {{--                                        <input type="text" name="subject" class="listSearchContributor inputElement" value="<?php if(isset($_GET['subject'])){echo $_GET['subject'];} ?>" >--}}
                        {{--                                    </td>--}}
                        {{--                                    <td>--}}
                        {{--                                        <input type="text" name="amount" style="width:85px;border-radius: 1px;--}}
                        {{--                    box-shadow: none;--}}
                        {{--                    border: 1px solid #cccccc;height: 30px;padding: 3px 8px;"   value="<?php if(isset($_GET['amount'])){echo $_GET['amount'];} ?>">--}}
                        {{--                                    </td>--}}
                        {{--                                    <td>--}}
                        {{--                                        <input type="text" name="status" class="listSearchContributor inputElement" value="<?php if(isset($_GET['status'])){echo $_GET['status'];} ?>">--}}
                        {{--                                    </td>--}}
                        {{--                                    <td></td>--}}
                        {{--                                    <td><button class="btn btn-brown">search</button></td>--}}
                        {{--                                </tr>--}}
                        {{--                                </thead>--}}


                        {{--                                <tbody>--}}



                        {{--                                <?php $srno=0; ?>--}}
                        {{--                                @foreach($production as $data)--}}
                        {{--                                    <?php $srno++; ?>--}}
                        {{--                                    <tr>--}}
                        {{--                                        <td style="vertical-align: top;text-align: center">{{$srno}}</td>--}}
                        {{--                                        <td width="12%"  style="vertical-align: top;">--}}
                        {{--                                            {{$data->machine_name}}</td>--}}

                        {{--                                        <td width="10%"  style="vertical-align: top;">--}}
                        {{--                                            <a href="{{url("production/details/".$data->batch_no)}}">{{$data->batch_no}} </a>--}}
                        {{--                                        </td>--}}

                        {{--                                        <td  style="vertical-align: top;text-align: center">{{$data->customer_name}}</td>--}}
                        {{--                                        <td  style="vertical-align: top;text-align: center">{{$data->product_name}}</td>--}}
                        {{--                                        <td  style="vertical-align: top;text-align: center">{{$data->nos}}</td>--}}
                        {{--                                        <td  style="vertical-align: top;text-align: center;width: 10%">{{$data->size}}</td>--}}
                        {{--                                        <td  style="vertical-align: top;text-align: center;width: 10%">@if($data->production_status=="Y") Complete @else <i class="mdi mdi-close-box" style="color: green"></i> Pending @endif</td>--}}
                        {{--                                        <td>--}}
                        {{--                                            <a href="{{url("production/details/".$data->batch_no)}}">View</a>--}}
                        {{--                                        </td>--}}
                        {{--                                    </tr>--}}
                        {{--                                @endforeach--}}
                        {{--                                </tbody>--}}
                        {{--                            </table>--}}
                        {{--                            </form>--}}
                        {{--                            {{$list->appends(request()->input())->links()}}--}}

                        {{--                        </div>--}}
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
