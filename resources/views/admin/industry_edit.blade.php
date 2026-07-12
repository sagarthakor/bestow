@extends('admin.layout.master')

@section('title', 'Edit Industry')
  <script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
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
                                    <h4 class="page-title">Industry Edit </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{Session::get('software_title')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.industry.list')}}">Industry List </a>
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
                                                {{Form::model($data,['method'=>'post','route'=>'admin.industry.update'])}}
                                                {{Form::hidden('id',null)}}
                                                <div class="col-md-12">
                                                    <div class="demo-box">

                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Industry Edit</label>
                                        {{Form::text('industry_name',null,['class'=>'form-control'])}}

                                    </div>
                                </div>


                                <div class="col-sm-4">
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
