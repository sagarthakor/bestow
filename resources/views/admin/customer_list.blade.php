@extends('admin.layout.table_master')

@section('title', 'List | Customer')

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
                            <h4 class="page-title">Customers List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    Customer
                                </li>
                                <li class="active">
                                    List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->



                {{Form::open(['method'=>'get','id'=>'formid'])}}
                <div class="row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                        @can('customer_create')
                            <a class="btn btn-primary" href="{{route('admin.customer.add')}}">Add New</a>
                        @endcan
                        {{-- <button class="btn btn-primary" value="export" name="export">Export</button>--}}

                    </div>
                </div>

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


                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th >#</th>
                                    <th>


                                        Customer Name

                                    </th>
                                    <th>

                                        Office Phone

                                    </th>
                                    <th>

                                        Office Email


                                    </th>
                                    <th>

                                        Owner Name

                                    </th>
                                    <th>

                                        Owner Phone

                                    </th>
                                    <th>

                                        Owner Email


                                    </th>
                                </tr>
                                </thead>
                                <tr>
                                    <th><button>search</button></th>
                                    <th><input type="text" class="listSearchContributor inputElement" value="<?php if(isset($_GET['customer_name'])){echo $_GET['customer_name'];} ?>" name="customer_name"></th>
                                    <th>
                                        <input type="text" name="primary_phone" value="<?php if(isset($_GET['primary_phone'])){echo $_GET['primary_phone'];} ?>" class="listSearchContributor inputElement">
                                    </th>
                                    <th>
                                        <input type="text"  class="listSearchContributor inputElement" name="primary_email" value="<?php if(isset($_GET['primary_email'])){echo $_GET['primary_email'];} ?>">
                                    </th>
                                    <th>
                                        <input type="text"  class="listSearchContributor inputElement" name="owner_name" value="<?php if(isset($_GET['owner_name'])){echo $_GET['owner_name'];} ?>">
                                    </th>
                                    <th> <input type="text" class="listSearchContributor inputElement" name="owner_mobile" value="<?php if(isset($_GET['owner_mobile'])){echo $_GET['owner_mobile'];} ?>">
                                    </th>
                                    <th><input type="text"      class="listSearchContributor inputElement" name="owner_email" value="<?php if(isset($_GET['owner_email'])){echo $_GET['owner_email'];} ?>"></th>

                                </tr>

                                <tbody>
                                <?php $srno=0; ?>
                                @foreach($cdata as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="vertical-align: top;text-align:center"> {{($cdata->currentPage() - 1) * $cdata->perPage() + $loop->iteration}}</td>
                                        <td style="vertical-align: top;">
                                            <a href="{{route('admin.customer.preview',['id' => $data->id])}}">{{$data->customer_name}}</a>

                                        </td>

                                        <td  style="vertical-align: top;text-align: center;">
                                            {{$data->primary_phone}}</td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td  style="vertical-align: top;text-align: center;"><a href="mailto:{{$data->primary_email}}">{{$data->primary_email}}</a></td>

                                        <td  style="vertical-align: top;text-align: center;">{{$data->owner_name}}</td>
                                        <td  style="vertical-align: top;text-align: center;">{{$data->owner_mobile}}</td>
                                        <td  style="vertical-align: top;text-align: center;">{{$data->owner_email}}
                                        </td>

                                        <!-- <td  style="vertical-align: top;"></td> -->


                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>

                                </tfoot>

                            </table>

                            {{$cdata->appends(request()->input())->links()}}
                            {{Form::close()}}
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            // Using jQuery.

            // $(function() {
            //     $('form').each(function() {
            //         $(this).find('input').keypress(function(e) {
            //             // Enter pressed?
            //             if(e.which == 10 || e.which == 13) {
            //                 this.form.submit();
            //             }
            //         });

            //         $(this).find('input[type=submit]').hide();
            //     });
            // });

            $( ".ascc" ).click(function() {
                //alert("sd");
                $( "#formid" ).submit();
            });
        </script>

@endsection
