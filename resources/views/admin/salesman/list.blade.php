@extends('admin.layout.table_master_material')

@section('title', 'List | Salesman')

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
                            <h4 class="page-title">Salesman List </h4>
                            <ol class="breadcrumb p-0 m-0">

                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    Salesman
                                </li>
                                <li class="active">
                                    List
                                </li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{url('admin/salesman/create')}}">Add New</a>
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

                        <div class="card-box">
                            <h4 class="m-t-0 header-title">Filter</h4>
                            {{Form::open(['method'=>'get'])}}
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Salesman Name</label>
                                        <input type="text" value="<?php if (isset($_GET['salesman_name'])) {
                                            echo $_GET['salesman_name'];
                                        } ?>" name="salesman_name"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Code</label>
                                        <input type="text" value="<?php if (isset($_GET['salesman_code'])) {
                                            echo $_GET['salesman_code'];
                                        } ?>" name="salesman_code"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Area</label>
                                        <input type="text" value="<?php if (isset($_GET['salesman_area'])) {
                                            echo $_GET['salesman_area'];
                                        } ?>" name="salesman_area" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="salesman_email" class="form-control"
                                               value="<?php if (isset($_GET['salesman_email'])) {
                                                   echo $_GET['salesman_email'];
                                               } ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <input type="text" name="salesman_mobile" class="form-control"
                                               value="<?php if (isset($_GET['salesman_mobile'])) {
                                                   echo $_GET['salesman_mobile'];
                                               } ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <button class="btn btn-primary waves-effect waves-light"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </div>

                        <div class="card-box table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>#.</th>
                                    <th>Salesman Name</th>

                                    <th>Code</th>

                                    <th>Area</th>

                                    <th>Email</th>
                                    <th>Mobile</th>

                                    <th></th>
                                </tr>
                                </thead>


                                <tbody>


                                <?php $srno = 0; ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="vertical-align: top;">{{$srno}}</td>
                                        <td width="12%" style="vertical-align: top;">
                                            <a href="{{url('client/sales1/view/'.$data->id)}}">{{$data->salesman_name}}</a>
                                        </td>

                                        <td width="10%" style="vertical-align: top;">
                                            {{$data->salesman_code}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td style="vertical-align: top;"> {{$data->salesman_area}}</td>
                                        <td style="vertical-align: top;"> {{$data->salesman_email}}</td>
                                        <td style="vertical-align: top;width:7%;text-align: left;"> {{$data->salesman_mobile}}</td>

                                        <td style="vertical-align: top;white-space: nowrap;">
                                            @can('customer_update')
                                                <a title="Edit" href="{{url('admin/salesman/edit/'.$data->id)}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                                            @endcan
                                            @can('customer_delete')
                                                <a title="Delete" href="{{url('admin/salesman/delete/'.$data->id)}}" class="btn btn-xs btn-danger waves-effect" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash-o"></i> Delete</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{Form::close()}}
                            {{$list->links()}}

                        </div>
                    </div>
                </div>




                <!-- end row -->


            </div> <!-- container -->

        </div> <!-- content -->


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(".modalopen").click(function () {
                var id = $(this).data("id");
                var primary_email = $("#primary_email" + id).val();
                var secondary_email = $("#secondary_email" + id).val();
                var subjects = $("#subjects" + id).val();
                var quot_id = $("#quot_id" + id).val();

                $("#to_email").val(primary_email + ',' + secondary_email);
                $("#qid").val(quot_id);
                $("#to_subject").val(subjects);
            });

            ClassicEditor
                .create(document.querySelector('#to_body'))
                .catch(error => {
                    console.error(error);
                });
        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // Using jQuery.

            $(function () {
                $('form').each(function () {
                    $(this).find('input').keypress(function (e) {
                        // Enter pressed?
                        if (e.which == 10 || e.which == 13) {
                            this.form.submit();
                        }
                    });

                    $(this).find('input[type=submit]').hide();
                });
            });
        </script>
@endsection
