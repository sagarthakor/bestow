@extends('admin.layout.table_master')

@section('title', 'List of Vendor')

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
                            <h4 class="page-title">Vendor List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    Vendor List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->




                <div class="row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">
                    </div>
                    @can('vendor_create')
                        <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                            <a class="btn btn-primary" href="{{route('admin.vendor.add')}}">Add New</a>
                        </div>
                    @endcan
                </div>
                <div class="row">
                    @if(session()->has('message'))
                        <div class="col-sm-12">
                            <div class="alert alert-info" style="background-color: #188ae2 !important">
                                <strong style="color: #fff">{{session()->get('message')}}</strong>
                            </div>
                        </div>
                    @endif
                    <form method="get" id="formid">
                        <div class="col-sm-12">

                            <div class="card-box table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Vendor Name</th>
                                        <th>Primary Phone</th>
                                        <th>Primary Email</th>
                                        <th>Contact Name</th>
                                        <th>Contact Phone</th>
                                        <th>Contact Email</th>

                                    </tr>

                                    <tr>
                                        <td><button>Search</button></td>
                                        <td><input type="text" placeholder="Vendor Name" class="listSearchContributor inputElement" value="<?php if(isset($_GET['vendor_name'])){echo $_GET['vendor_name'];} ?>" name="vendor_name"></td>
                                        <td>
                                            <input type="text" placeholder="Primary Phone" class="listSearchContributor inputElement" name="primary_phone" value="<?php if(isset($_GET['primary_phone'])){echo $_GET['primary_phone'];} ?>">
                                        </td>
                                        <td>
                                            <input type="text" placeholder="Primary Email" class="listSearchContributor inputElement" name="primary_email" value="<?php if(isset($_GET['primary_email'])){echo $_GET['primary_email'];} ?>">
                                        </td>
                                        <td>
                                            <input type="text" placeholder="Contact Name" class="listSearchContributor inputElement" name="owner_name" value="<?php if(isset($_GET['owner_name'])){echo $_GET['owner_name'];} ?>">
                                        </td>
                                        <td>
                                            <input type="text" placeholder="Contact Phone" class="listSearchContributor inputElement" name="owner_mobile" value="<?php if(isset($_GET['owner_mobile'])){echo $_GET['owner_mobile'];} ?>">
                                        </td>
                                        <td>
                                            <input type="text" placeholder="Contact Email" class="listSearchContributor inputElement" name="owner_email" value="<?php if(isset($_GET['owner_email'])){echo $_GET['owner_email'];} ?>">
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $srno=0; ?>

                                    @foreach($cdata as $key => $data)

                                        <?php $srno++; ?>
                                        <tr>
                                            <td style="width: 5%;vertical-align: top;"> {{($cdata->currentPage() - 1) * $cdata->perPage() + $loop->iteration}}</td>
                                            <td  style="vertical-align: top;">
                                                <a href="{{route('admin.vendor.preview',['id' =>$data->id])}}">{{$data->vendor_name}}</a>
                                            </td>

                                            <td  style="vertical-align: top;text-align: center;">
                                                {{$data->primary_phone}}
                                            </td>

                                            <!--      <td  style="vertical-align: top;"></td>
                                                 <td  style="vertical-align: top;"></td> -->
                                            <td  style="vertical-align: top;text-align: center;">
                                                <a href="mailto:{{$data->primary_email}}">{{$data->primary_email}}</a>
                                            </td>

                                            <td  style="vertical-align: top;text-align: center;">
                                                {{$data->owner_name}}</td>
                                            <td  style="vertical-align: top;text-align: center;">
                                                {{$data->owner_mobile}}</td>
                                            <td  style="vertical-align: top;text-align: center;">
                                                <a href="mailto:{{$data->owner_email}}">{{$data->owner_email}}</a></td>

                                            <!-- <td  style="vertical-align: top;"></td> -->

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                {{$cdata->appends(request()->input())->links()}}
                            </div>
                        </div>
                </div>
                </form>



                <!-- end row -->



            </div> <!-- container -->

        </div>
    </div><!-- content -->


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

        $( ".ascc" ).click(function() {
            //alert("sd");
            $( "#formid" ).submit();
        });

        $( ".ascc1" ).click(function() {
            //alert("sd");
            $( "#formid1" ).submit();
        });

        $( ".ascc2" ).click(function() {
            //alert("sd");
            $( "#formid2" ).submit();
        });

        $( ".ascc3" ).click(function() {
            //alert("sd");
            $( "#formid3" ).submit();
        });
    </script>
@endsection
