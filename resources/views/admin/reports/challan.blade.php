@extends('admin.layout.master')

@section('title', 'List | Challan')

@section('sidebar')
    @parent

@endsection

@section('content')

    <!-- DataTables -->

    <style type="text/css">

        nav{
            float: right;
        }
    </style>

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Challan List </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    Sales
                                </li>
                                <li class="active">
                                    List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                {{Form::model(request(),['method'=>'get'])}}

                <div class="row">
                    <div class="col-sm-4">
                    </div>
                    <div class="col-sm-4">

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

                        <?php
                        if(isset($_GET['msg']))
                        {
                        ?>
                        <div class="alert alert-success">
                            <strong style="color:#000">Stage Change Successfully</strong>
                        </div>
                        <?php

                        }
                        ?>
                        <div class="card-box table-responsive">



                            <table class="table table-striped table-bordered">
                                <thead>
                                      <tr>
                                        <td colspan="6" class="text-right"><button class='btn btn-primary' type="submit" name="export_excel" value="export_excel">Export Excel</button>&nbsp;<button class='mr-5 btn btn-info'>Search</button></td>
                                      </tr>
                                <tr>
                                    <th>#</th>
                                    <th> Challan No
                                    </th>

                                    <th>
                                        Challan Date
                                    </th>

                                    <th>
                                        Client Name
                                    </th>

                                    <th>
                                        Subject
                                    </th>

                                    <th>
                                        Amount

                                    </th>


                                </tr>

                                <tr>
                                    <td>


                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['quot_no'])){echo $_GET['quot_no'];} ?>" name="quot_no"  class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="date" value="<?php if(isset($_GET['from_date'])){echo $_GET['from_date'];} ?>" name="from_date" class="listSearchContributor inputElement" id="start_date" autocomplete="off">

                                        <input type="date" value="<?php if(isset($_GET['end_date'])){echo $_GET['end_date'];} ?>" name="end_date" class="listSearchContributor inputElement" id="end_date" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name" class="listSearchContributor inputElement">
                                    </td>
                                    <td>
                                        <input type="text" name="subject" class="listSearchContributor inputElement" value="<?php if(isset($_GET['subject'])){echo $_GET['subject'];} ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="amount" class="listSearchContributor inputElement" value="<?php if(isset($_GET['amount'])){echo $_GET['amount'];} ?>">
                                    </td>


                                </tr>

                                </thead>


                                <tbody>


                                <?php $srno=0; ?>
                                <?php
                                $stage = array('Created' => 'Created', 'Sent' => 'Sent', 'Reviewing' => 'Reviewing', 'QuoteRivision' => 'QuoteRivision', 'Accepted' => 'Accepted', 'Invoiced' => 'Invoiced', 'Canceled' => 'Canceled');
                                ?>
                                @foreach($list as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td style="width: 2%;vertical-align: top;text-align: center;">
                                            {{($list->currentPage() - 1) * $list->perPage() + $loop->iteration}}
                                        </td>
                                        <td   style="vertical-align: top;">
                                            @can('delivery_challan_view')
                                                {{$data->challan_number}}
                                            @endcan
                                        </td>

                                        <td   style="vertical-align: top;width: 5%">
                                            {{date('d-m-Y',strtotime($data->invoice_date))}}
                                        </td>

                                        <!--      <td  style="vertical-align: top;"></td>
                                         <td  style="vertical-align: top;"></td> -->
                                        <td  style="vertical-align: top;width: 25%">{{$data->customer_name}}</td>
                                        <td  style="vertical-align: top;">{{$data->subject}}</td>
                                        <td  style="vertical-align: top;width:2%;text-align: left;">{{number_format($data->grand_total)}}
                                        </td>



                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            {{$list->appends(request()->input())->links()}}

                        </div>
                    </div>
                </div>

                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

     <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>



@endsection
