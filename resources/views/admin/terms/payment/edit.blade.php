@extends('admin.layout.master')

@section('title', 'Edit | Payment Terms')

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
                            <h4 class="page-title">Payment Terms Add </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.payment_terms.list')}}">Payment List </a>
                                </li>
                                <li>
                                    Edit Payment Terms
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

                                <div class="col-xs-12">

                                    <div class="row">
                                        {{Form::model($data,['method'=>'post','route'=>'admin.payment_terms.update','files'=>'true'])}}
                                        {{Form::hidden("id",null)}}
                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Terms Name</label>
                                                        {{Form::text('terms_name',null,['class'=>'form-control'.$errors->first('terms_name',' error')])}}
                                                        @if($errors->has('terms_name'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">Number of Days</label>
                                                        {{Form::text('days',null,['class'=>'form-control'.$errors->first('days',' error')])}}
                                                        @if($errors->has('days'))
                                                            <p class="help-block">This field is required</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <button class="btn btn-primary">Save</button>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                        {{Form::close()}}

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

@endsection
