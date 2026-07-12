@extends('admin.layout.master')

@section('title', 'Add Category')

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
                                    <h4 class="page-title">Category Edit </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{Session::get('software_title')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.category.list')}}">Category List </a>
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
                                                {{Form::model($data,['method'=>'post','route'=>'admin.category.update','files'=>'true'])}}
                                                {{Form::hidden('id',null)}}
                                                <div class="col-md-12">
                                                    <div class="demo-box">
                                                        <div class="col-md-6">
                                                          <div class="form-group">
                                                                    <label class="control-label">Category Name</label>
                                                                    {{Form::text('category_name',null,['class'=>'form-control'])}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                          <div class="form-group">
                                                                    <label class="control-label">Category Image</label>
                                                                    {{Form::file('category_image',['class'=>'form-control'])}}
                                                                   <label>
                                                                       @if($data->category_image !='')
                    <img src="{{asset('public/product_category/'.$data->category_image)}}" onclick="image_show()" height="65px" width="65px">
                    <input type="hidden" name="imgsrc" id="imgsrc" value="{{asset('public/product_category/'.$data->category_image)}}">
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

                                           <div class="modal" id="myModal1" role="dialog" style="left: 16%;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">

        <div class="modal-body">
           <img class="modal-content" id="img01">
        </div>
        <div class="modal-footer">

          <button type="button" class="btn btn-default" onclick="model_close()">Close</button>
        </div>
      </div>
    </div>
  </div>
                        		</div> <!-- end card-box -->
                            </div><!-- end col-->

                        </div>
                        <!-- end row -->


                    </div> <!-- container -->

                </div> <!-- content -->



            @extends("admin.form_fotter")
                <script type="text/javascript">
                    function image_show() {
        // body...
        var img =$("#myImg").val();
        var imgsrc=$("#imgsrc").val();
        // /alert(imgsrc);
        var modal = document.getElementById("myModal1");
        var captionText ="Image";
        var modalImg = document.getElementById("img01");

        modal.style.display = "block";
        modalImg.src = imgsrc;
        captionText.innerHTML = this.alt;

    }
    function model_close()
    {
        $("#myModal1").hide();
    }
                </script>
  @endsection
