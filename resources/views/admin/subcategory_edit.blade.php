@extends('admin.layout.master_material')

@section('title', 'Edit SubCategory')

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
                                    <h4 class="page-title">SubCategory Edit </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.subcategory.list')}}">SubCategory List </a>
                                        </li>
                                        <li>
                                            Add SubCategory
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
                                                {{Form::model($subcategory,['method'=>'post','route'=>'admin.subcategory.update','files'=>'true'])}}
                                                {{Form::hidden("id",null)}}
                                                <div class="col-md-12">
                                                    <div class="demo-box">
                                                         <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">Caetgory</label>
                                                                    {{Form::select('category',$category,null,['class'=>'form-control'])}}

                                                            </div>
                                                        </div>

                                                         <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">SubCategory Name</label>
                                                                    {{Form::text('subcategory_name',null,['class'=>'form-control'])}}

                                                            </div>
                                                        </div>

                                                          <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">SubCategory Image</label>
                                                                    {{Form::file('subcategory_image',['class'=>'form-control'])}}
                                                                    <label>
                                                                       @if($subcategory->subcategory_image !='')
                    <img src="{{asset('public/subcategory/'.$subcategory->subcategory_image)}}" onclick="image_show()" height="65px" width="65px">
                    <input type="hidden" name="imgsrc" id="imgsrc" value="/subcategory/{{$subcategory->subcategory_image}}">
                                                                       @endif
                                                                   </label>

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
