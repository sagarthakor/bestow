@extends('admin.layout.master_material')

@section('title', 'Update | Formula')

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
                            <h4 class="page-title">Formula Update </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{url('formula_mst/list')}}">Formula List </a>
                                </li>
                                <li>
                                    Update Formula
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

                                    <div class="row">


                                        {{Form::model($data,['method'=>'post','route'=>'admin.production.formula_update'])}}
                                        {{Form::hidden('id',null)}}
                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Nos.</label>
                                                        {{Form::text('nos',null,['class'=>'form-control'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Size</label>
                                                        {{Form::text('size',null,['class'=>'form-control'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Total Material</label>
                                                        {{Form::text('required_qty',null,['oninput'=>'cal(this)','class'=>'form-control total_mat'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <h3>Required Material for production</h3>
                                                </div>

                                                <table id="caltable1" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Raw Material</th>

                                                            <th colspan="2">Required Qty</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $srno=0;
                                                    ?>
                                                    @foreach($data_item as $fm)
                                                        <?php
                                                        $srno++;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <select name="material[]" class="form-control">
                                                                    <option value="{{$fm->material}}">{{$fm->product_name}}</option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input value="{{$fm->qty}}" style="text-align: right" type="text" class="form-control qty" name="qty[]">
                                                            </td>
                                                            <td>
                                                                {{$fm->uom_name}}
                                                            </td>
                                                        </tr>


                                                    @endforeach
                                                    Total <span class="srno">{{$srno}}</span> Material Required
                                                    </tbody>

                                                </table>
                                                <div class="col-md-2">
                                                    <button class="btn btn-primary">Save</button>
                                                </div>

                                                {{Form::close()}}





                                            </div>

                                        </div>








                                            </div>

                                        </div>


                                    </div><!-- end row -->


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
        <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
        <script>
            $(document).ready(function () {
                $('.js-example-basic-single').select2();
            });

            function cal(ele)
            {
                var total_mat =$(".total_mat").val();
                // alert(total_mat);
                var totalss = $(".srno").text();
                var percentage = $(".percentage");
                var qty=$(".qty");
                //alert(totalss);
                //var qty = $(".qty").text();
                var item_total = 0;
                for (var i = 0; i < totalss; i++) {
                    var per =$(percentage[i]).text();

                    var mat=Number(total_mat)*Number(per)/100;
                    //alert(per);
                    //alert(mat);
                    $(qty[i]).val(mat);
                }
            }

            $("#add_rawmaterial").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;

                var data = "<tr>";
                data +='<td><select name="raw_material[]" class="form-control js-example-basic-single"><option value="">select raw material</option>@foreach($rawmaterial as $rmat)<option value="{{$rmat->id}}">{{$rmat->product_name}}</option>@endforeach</select></td>';
                data +='<td><input type="text" name="required_qty_per[]" class="form-control"></td>';
                data +='<td class="actions" style="vertical-align: top !important;text-align: center;">';
                data +='<a onclick="remove_row(this)" style="cursor: pointer;" class="on-editing save-row" title="save"><i class="fa fa-trash" style="font-size: 22px"></i></a>';
                data +='</td>';
                $("#caltable").append(data);
                $("#totrow").val(i);

                $(document).ready(function () {
                    $('.js-example-basic-single').select2();
                });
            });
            function remove_row(ele) {
                if (confirm("Are you sure you want to delete this?")) {
                    $(ele).closest('tr').remove();
                }else {
                    return false;
                }
            }
        </script>
@endsection

