@extends('admin.layout.master_material')

@section('title', 'Edit Company')

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
                                    <h4 class="page-title">Add Company  </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#">{{Session::get('software_title')}}</a>
                                        </li>
                                        <li>
                                            <a href="{{route('admin.company.list')}}">Company List </a>
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
                                                {{Form::model($data,['method'=>'post','route'=>'admin.company.update','files'=>'true'])}}
                                                {{Form::hidden('id',null)}}
                                                <div class="col-md-12">
                                                    <div class="demo-box">
                                                        <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Company Name</label>
                                                                    {{Form::text('company_name',null,['class'=>'form-control'])}}

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">GST Number</label>
                                                                    {{Form::text('gst',null,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label">PAN Number</label>
                                                                {{Form::text('pan_no',null,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>

                                                         <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Email</label>
                                                                    {{Form::email('email',null,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                         <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Phone No</label>
                                                                    {{Form::text('phone',null,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Mobile No</label>
                                                                    {{Form::text('mobile',null,['class'=>'form-control'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                          <div class="form-group">
                                                                    <label class="control-label">Records Per Page</label>
                                                                    {{Form::number('records_per_page',null,['class'=>'form-control','min'=>1,'max'=>500])}}
                                                                    <p class="text-muted m-b-0"><small>Number of rows shown per page across all listing tables.</small></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">Block No. & Building Name </label>
                                                                    {{Form::textarea('address',null,['class'=>'form-control','id'=>'editor','rows'=>'2'])}}
                                                          </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Street Address </label>
                                                                {{Form::textarea('address1',null,['class'=>'form-control','id'=>'editor2','rows'=>'2'])}}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="control-label">Street 2 / Landmark  </label>
                                                                {{Form::textarea('address2',null,['class'=>'form-control','id'=>'editor3','rows'=>'2'])}}
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Country <span style="color: red">*</span></label>

                                                                    {{Form::select('country',$country,null,['class'=>'form-control'.$errors->first('country',' error'),'id'=>"billing_country"])}}
                                                                    @if($errors->has('country'))
                                                                        <p class="help-block">This field is required</p>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>State  <span style="color: red">*</span></label>

                                                                    {{Form::select('state',$state,null,['class'=>'form-control'.$errors->first('state',' error'),'id'=>"billing_state"])}}
                                                                    @if($errors->has('state'))
                                                                        <p class="help-block">This field is required</p>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>City   <span style="color: red">*</span></label>

                                                                    {{Form::select('city',$city,null,['class'=>'form-control'.$errors->first('city',' error'),'id'=>"billing_city"])}}
                                                                    @if($errors->has('city'))
                                                                        <p class="help-block">This field is required</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Postal Code  <span style="color: red">*</span></label>

                                                                    {{Form::text('pincode',null,['class'=>'form-control'.$errors->first('pincode',' error'),'id'=>"billing_postalcode"])}}
                                                                    @if($errors->has('pincode'))
                                                                        <p class="help-block">This field is required</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>



                                             <div class="col-sm-12">

                                                <h3>Owner Information</h3>

                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Full Name <span style="color: red">*</span></label>
                                                    {{Form::text('owner_name',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                              <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Mobile No <span style="color: red">*</span></label>
                                                    {{Form::text('owner_mobile',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                             <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Alternate No.<span style="color: red">*</span></label>
                                                    {{Form::text('alternate_no',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Personal Email <span style="color: red">*</span></label>
                                                    {{Form::text('personal_email',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Work Email <span style="color: red">*</span></label>
                                                    {{Form::text('work_email',null,['class'=>'form-control'])}}

                                                </div>
                                            </div>

                                                        <div class="col-md-12">
                                                            <h3>Bank Information</h3>
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Account Type</label>
                                                                        {{Form::text('account_type',null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Bank Name</label>
                                                                        {{Form::text('bank_name',null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Account Number</label>
                                                                        {{Form::text('account_no',null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>Bank Branch</label>
                                                                        {{Form::text('bank_branch',null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>IFSC Code</label>
                                                                        {{Form::text('ifsc_code',null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label>MICR Code</label>
                                                                        {{Form::text('micr_code',null,['class'=>'form-control'])}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">Signature Image</label>
                                                                    {{Form::file('signature_image',['class'=>'form-control'])}}
                                                                    <div class="card">
                                                                        <img src="{{asset('/company_logo/'.$data->signature_image)}}">
                                                                    </div>
                                                            </div>
                                                        </div>

                                                           <div class="col-md-12">
                                                          <div class="form-group">
                                                                    <label class="control-label">Logo</label>
                                                                    {{Form::file('logo',['class'=>'form-control'])}}
                                                                    <div class="card">
                                                                        <img src="{{asset('/company_logo/'.$data->logo)}}">
                                                                    </div>
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

                <script src="{{asset('admin/assets/js/jquery.min.js')}}"></script>
                <script>
            // ClassicEditor
            // .create( document.querySelector( '#editor' ) )
            // .catch( error => {
            //     console.error( error );
            // } );
            // ClassicEditor
            //     .create( document.querySelector( '#editor2' ) )
            //     .catch( error => {
            //         console.error( error );
            //     } );
            // ClassicEditor
            //     .create( document.querySelector( '#editor3' ) )
            //     .catch( error => {
            //         console.error( error );
            //     } );


            $("#billing_country").change(function(){
                var country=$("#billing_country").val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/client/get_state',
                    data:{country:country},
                    method:'get',
                    success:function(res)
                    {
                        $("#billing_state").html(res);
                    }
                });
            });

            $("#billing_state").change(function(){
                var state=$("#billing_state").val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/client/get_city',
                    data:{state:state},
                    method:'get',
                    success:function(res)
                    {
                        $("#billing_city").html(res);
                    }
                });
            });

            $("#shipping_country").change(function(){
                var country=$("#shipping_country").val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/client/get_state',
                    data:{country:country},
                    method:'get',
                    success:function(res)
                    {
                        $("#shipping_state").html(res);
                    }
                });
            });

            $("#shipping_state").change(function(){
                var state=$("#shipping_state").val();
                var appurl="{{url('/')}}";
                $.ajax({
                    url:appurl+'/client/get_city',
                    data:{state:state},
                    method:'get',
                    success:function(res)
                    {
                        $("#shipping_city").html(res);
                    }
                });
            });
    </script>
          @endsection
