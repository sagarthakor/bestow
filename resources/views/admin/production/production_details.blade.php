@extends('admin.layout.master_material')

@section('title', 'Add New | Machine')

@section('sidebar')
    @parent

@endsection

@section('content')
<style>
    body{
        font-weight: 800 !important;
    }
</style>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Batch - {{$production->batch_no}} &nbsp; &nbsp; &nbsp; Machine - {{$production->machine_name}}</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.production.allocate_machines')}}">Production Machines List </a>
                                </li>
                                <li>
                                   Batch Details
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
                                    {{Form::open(['method'=>'post','route'=>'admin.production.record.save'])}}
                                    {{Form::hidden("batch_no",$production->batch_no)}}
                                    {{Form::hidden("production_id",$production->id)}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Customer Name</td>
                                                    <td>{{$production->customer_name ?? ""}}</td>
                                                </tr>
                                            </table>

                                        </div>

                                        <div class="col-md-5">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Product Name</td>
                                                    <td><x-product-name :row="$production" /></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-1" style="padding-left: 10px">
                                            <img class="photo img-responsive" style="width: 50px;" src="{{asset('/product_image/'.$production->product_image)}}">
                                        </div>

                                        <div id="myModal" class="modal">
                                            <span onclick="closmodal()" style="top:60px;color: red !important;" class="close"><i class="mdi mdi-close-box"></i></span>
                                            <img class="modal-content" id="popup_img">
                                            <div id="caption"></div>
                                        </div>

                                       <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Batch No.</td><td>{{$production->batch_no}}</td>
                                            </tr>
                                            <tr>
                                                <td>Socks Nos.</td><td><span id="production_nos">{{$production->nos}}</span></td>
                                            </tr>
                                            <tr>
                                                <td>Socks Size.</td><td>{{$production->size}}</td>
                                            </tr>
                                        </table>
                                       </div>
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr><td>Total Material Used</td>
                                                    <td><span id="production_material">{{$production->total_material}}</span></td></tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                @foreach($production_material as $mat)
                                                                    <td><x-product-name :row="$mat" /> : {{$mat->required_qty}} Gram</td>
                                                                @endforeach

                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                    </div>




                                            @if($production->status=="Finished" || $production->status=="Move to Stitching")
                                            <div class="row" id="finished_record" style="margin-top: 15px;display: block">
                                                @else
                                                    <div class="row" id="finished_record" style="margin-top: 15px;display: none">
                                                @endif
                                                <div class="col-md-12">
                                                <h2>Finished Process</h2>
                                                </div>
                                       <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Total Production Socks No.</td>

                                                        <td><input @if($production->production_status == "Y") readonly @endif value="{{$production->total_production ?? ''}}" type="text" oninput="cal_product(this.value)" class="form-control" name="total_production"></td>


                                                </tr>

                                                <tr>
                                                    <td>Total Wastage Nos.</td>

                                                        <td><input @if($production->production_status == "Y") readonly @endif type="text" value="{{$production->total_wastage_nos ?? ''}}" id="total_wastage_nos" class="form-control" name="total_wastage_nos"></td>


                                                </tr>

                                            </table>
                                        </div>

                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>Total Material Used</td>
                                                    <td>

                                                            <input @if($production->production_status == "Y") readonly @endif type="text" value="{{$production->total_material_used ?? ''}}" class="form-control" id="total_material_used" name="total_material_used">

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Total Wastage Material</td>
                                                    <td>

                                                            <input @if($production->production_status == "Y") readonly @endif type="text" value="{{$production->total_wastage_used ?? ''}}" class="form-control" id="total_wastage_used" name="total_wastage_used">

                                                        </td>
                                                </tr>


                                            </table>
                                        </div>
                                                @if($production->status=="Finished" OR $production->status=="Move to Stitching")
                                                @else
                                                            <div class="row" style="margin-top: 10px">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Remarks</label>
                                                            {{Form::text("remarks",null,['class'=>'form-control'])}}
                                                        </div>
                                                    </div>
                                                </div>
                                                    @endif

                                    </div>
                                            @if($production->status=="Finished")
                                                <div class="form-group">
                                            <button name="process" value="Move to Stitching" class="btn btn-info">Move to Stitching <i class="mdi mdi-arrow-right mdi-18px"></i></button>
                                                </div>
                                                    @else

                                            @endif


                                    <div class="row" id="finished_submit" style="display:none;">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                        <button class="btn btn-success" name="process" value="Finished">Submit</button>
                                            </div>
                                            </div>
                                    </div>


                                            @if($production->production_status=="Y")

                                            @else

                                                <div class="row">
                                               @if($last_record=="no")
                                             <div class="col-md-3 col-sm-3">
                                                        <button value="start" name="process" class="btn btn-success"><i class="mdi mdi-play"></i> Start Production</button>
                                             </div>

                                              <div class="col-md-3 col-sm-3">
                                                        <button value="pause" name="process" class="btn btn-danger"><i class="mdi mdi-pause"></i> Pause Production</button>
                                                    </div>

                                                     <div class="col-md-3 col-sm-3">
                                                        <button  value="end" name="process"  class="btn btn-danger"><i class="mdi mdi"></i> End Production</button>
                                                    </div>

                                            @else
                                                @if($last_batch_record->process=="start")
                                                <div class="col-md-3 col-sm-3">
                                                        <button disabled value="start" name="process" class="btn btn-success"><i class="mdi mdi-play"></i> Start Production</button>
                                                    </div>
                                                @else
                                                    <div class="col-md-3 col-sm-3">
                                                        <button value="start" name="process" class="btn btn-success"><i class="mdi mdi-play"></i> Start Production</button>
                                                    </div>
                                                @endif



                                            @if($last_batch_record->process =="pause")
                                                    <div class="col-md-3 col-sm-3">
                                                        <button disabled value="pause" name="process" class="btn btn-danger"><i class="mdi mdi-pause"></i> Pause Production</button>
                                                    </div>
                                                @else
                                                 <div class="col-md-3 col-sm-3">
                                                        <button value="pause" name="process" class="btn btn-danger"><i class="mdi mdi-pause"></i> Pause Production</button>
                                                    </div>
                                                 @endif
                                                  @if($last_batch_record->process  =="end")
                                                   <div class="col-md-3 col-sm-3">
                                                        <button disabled value="end" name="process"  class="btn btn-danger"><i class="mdi mdi"></i> End Production</button>
                                                    </div>
                                                  @else
                                                   <div class="col-md-3 col-sm-3">
                                                        <button  value="end" name="process"  class="btn btn-danger"><i class="mdi mdi"></i> End Production</button>
                                                    </div>
                                                  @endif
                                           @endif

                                                    <div class="col-md-3 col-sm-3">
                                                        <button id="finished" class="btn btn-danger"><i class="mdi mdi-play-protected-content"></i> Finished Production</button>
                                                    </div>

                                                </div>
                                            @endif

                                    <div class="row" style="margin-top: 15px">
                                        <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>#.</th><th>Process</th><th>Time & Date</th><th>Operator Name</th>
                                            <?php
                                            $srno=0;
                                            ?>
                                            @foreach($batch_record as $record)
                                                <?php
                                                $srno++;
                                                ?>
                                                <tr>
                                                    <td style="width: 5%;text-align: center">{{$srno}}</td>
                                                    <td style="text-align: center">{{$record->process}}</td>
                                                    <td style="text-align: center">{{$record->time}} | {{date('d-m-Y',strtotime($record->date))}}</td>
                                                    <td style="text-align: center">{{$record->first_name}} {{$record->last_name}}</td>
                                                </tr>
                                                @endforeach
                                            </tr>
                                        </table>
                                        </div>
                                    </div>

                                    {{Form::close()}}

                                <!-- end row -->


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
            <style>
                #myImg {
                    border-radius: 5px;
                    cursor: pointer;
                    transition: 0.3s;
                }

                #myImg:hover {opacity: 0.7;}

                /* The Modal (background) */
                .modal {
                    display: none; /* Hidden by default */
                    position: fixed; /* Stay in place */
                    z-index: 1; /* Sit on top */
                    padding-top: 100px; /* Location of the box */
                    left: 0;
                    top: 0;
                    width: 100%; /* Full width */
                    height: 100%; /* Full height */
                    overflow: auto; /* Enable scroll if needed */
                    /*background-color: rgb(0,0,0); !* Fallback color *!*/
                    /*background-color: rgba(0,0,0,0.9); !* Black w/ opacity *!*/
                }

                /* Modal Content (image) */
                .modal-content {
                    margin: auto;
                    display: block;
                    width: 80%;
                    max-width: 700px;
                }

                /* Caption of Modal Image */
                #caption {
                    margin: auto;
                    display: block;
                    width: 80%;
                    max-width: 700px;
                    text-align: center;
                    color: #ccc;
                    padding: 10px 0;
                    height: 150px;
                }

                /* Add Animation */
                .modal-content, #caption {
                    -webkit-animation-name: zoom;
                    -webkit-animation-duration: 0.6s;
                    animation-name: zoom;
                    animation-duration: 0.6s;
                }

                @-webkit-keyframes zoom {
                    from {-webkit-transform:scale(0)}
                    to {-webkit-transform:scale(1)}
                }

                @keyframes zoom {
                    from {transform:scale(0)}
                    to {transform:scale(1)}
                }

                /* The Close Button */
                .close {
                    position: absolute;
                    top: 15px;
                    right: 35px;
                    color: #f1f1f1;
                    font-size: 40px;
                    font-weight: bold;
                    transition: 0.3s;
                }

                .close:hover,
                .close:focus {
                    color: #bbb;
                    text-decoration: none;
                    cursor: pointer;
                }

                /* 100% Image Width on Smaller Screens */
                @media only screen and (max-width: 700px){
                    .modal-content {
                        width: 100%;
                    }
                }
            </style>
        <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.photo').click(function() {

                var path = $(this).attr('src');
                var id=$(this).attr("data-id");
                $("#myModal").show();
                $("#popup_img").attr("src",path);
                //captionText.innerHTML = this.alt;
                //alert(id);
            });
        });

        $("#finished").click(function (e){
           e.preventDefault();
            $("#finished_record").show();
            $("#finished_submit").show();
        });

        function closmodal()
        {
            $("#myModal").hide();
        }

        function cal_product(socks)
        {

            var production_nos=$("#production_nos").text();
            if(Number(socks)>Number(production_nos))
            {
                 $("#finished_submit").hide();
                alert("do not enter more qty");
            }else{
                var produce=Number(production_nos)-Number(socks);
            $("#total_wastage_nos").val(produce);

            var production_material=$("#production_material").text();
            //alert(production_nos);
            var totalmaterialused=Number(production_material)/Number(production_nos);
            var totalwastage=Number(production_material)/Number(produce);
            //alert(totalmaterialused);
            $("#total_material_used").val(Number(totalmaterialused)*Number(socks));

            $("#total_wastage_used").val(Number(totalmaterialused)*Number(produce));

            $("#finished_submit").show();
            }

        }
    </script>
@endsection
