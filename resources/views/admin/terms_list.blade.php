@extends('admin.layout.table_master')

@section('title', 'List of Terms & Conditions')

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
                            <h4 class="page-title">Terms & Condition</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    Terms & Condition
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

                    <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                        <a class="btn btn-primary" href="{{route('admin.term.add')}}">Add New</a>
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
                                    <th >Sr.</th>
                                    <th>Module</th>
                                    <th>Terms & Condition</th>
                                    <th></th>
                                </tr>
                                </thead>


                                <tbody>
                                <?php $srno=0; ?>
                                @foreach($data as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td  style="vertical-align: top;width: 5%">{{$srno}}</td>
                                        <td  style="vertical-align: top;">{{$data->module}}</td>
                                        <td  style="vertical-align: top;">{{strip_tags($data->description)}}</td>
                                        <td  style="vertical-align: top;width: 5%">

                                            <a class="table-btn"  href="{{route('admin.term.edit',['id' => $data->id])}}"title="edit"><i class="fa fa-pencil" style="font-size: 20px;"></i></a>

                                            <a class="table-btn"  href="{{route('admin.term.delete',['id' => $data->id])}}"title="edit" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash" style="font-size: 20px;" ></i></a>


                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>



                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

@endsection
