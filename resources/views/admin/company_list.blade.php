@extends('admin.layout.table_master_material')

@section('title', 'List of Company')

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
                            <h4 class="page-title">Company</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    Company
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

                        <div class="card-box table-responsive">

                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th >Sr.</th>
                                    <th>Company Name</th>

                                    <th>Logo</th>

                                    <th>Address</th>

                                    <th></th>
                                </tr>
                                </thead>


                                <tbody>
                                <?php $srno=0; ?>
                                @foreach($data as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td  style="vertical-align: top;width: 5%">{{$srno}}</td>
                                        <td  style="vertical-align: top;">{{$data->company_name}}</td>
                                        <td  style="vertical-align: top;"><img src="/company_logo/{{$data->logo}}" style="height: 100px"></td>
                                        <td  style="vertical-align: top;">{{strip_tags($data->address)}}</td>



                                        <td  style="vertical-align: top;width: 5%">


                                            <a class="btn btn-xs btn-primary waves-effect" href="{{route('admin.company.edit',$data->id)}}" title="edit"><i class="fa fa-pencil"></i> Edit</a>

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
