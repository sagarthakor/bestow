@extends('admin.layout.master_material')

@section('title', 'Formula View')

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
                                <h4 class="page-title">Quotation </h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">{{Session::get('software_title')}}</a>
                                    </li>
                                    <li>
                                        <a href="{{url('formula_mst/list')}}">Formula List </a>
                                    </li>
                                    <li class="active">
                                        Formula View
                                    </li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                  
                   <div class="tab-content">
                      <div id="home" class="tab-pane fade in active">
                        <div class="panel">

                            <div class="panel-body">
                               <div class="row">
                                    <div class="col-md-12">
                                       <h3>Formula Details</h3>
                                    </div>
                                    
                                    <div class="row">


                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Nos.</label>
                                                        <br>{{$data->nos}}
                                                        <!--{{Form::text('nos',null,['class'=>'form-control'])}}-->

                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Size</label>
                                                        <br>{{$data->size}}
                                                        <!--{{Form::text('size',null,['class'=>'form-control'])}}-->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Total Material</label>
                                                       <br> {{$data->required_qty}}
                                                        
                                                        <!--{{Form::text('required_qty',null,['oninput'=>'cal(this)','class'=>'form-control total_mat'])}}-->

                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <h3>Required Material for production</h3>
                                                </div>

                                                <table id="caltable1" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Raw Material</th>
                                                            <th>Required Qty</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $srno=0;
                                                    ?>
                                                    @foreach($data_item as $fm)
                                                        <?php
                                                        $srno++;
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <x-product-name :row="$fm" />
                                                                <!--<select name="material[]" class="form-control">-->
                                                                <!--    <option value="{{$fm->material}}"></option>-->
                                                                <!--</select>-->
                                                            </td>
                                                           
                                                            <td>
                                                                {{$fm->qty}} {{$fm->uom_name}}
                                                            </td>
                                                           
                                                        </tr>


                                                    @endforeach
                                                   
                                                    </tbody>

                                                </table>
                                            



                                            </div>

                                        </div>
                                               







                                            </div>

                                        </div>


                                    </div>
                                </div>
                               
                                   
        
</div>
<!-- end: page -->

</div> <!-- end Panel -->

</div> <!-- container -->

</div> <!-- content -->
                    
@endsection

