@extends('admin.layout.table_master')

@section('title', 'BOM Preview')

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
                <h4 class="page-title">BOM </h4>
                <ol class="breadcrumb p-0 m-0">
                  <li>
                    <a href="#">{{Session::get('software_title')}}</a>
                  </li>
                  <li>
                    <a href="{{url('quotation-list')}}">BOM List </a>
                  </li>
                  <li class="active">
                    BOM Edit
                  </li>
                </ol>
                <div class="clearfix"></div>
              </div>
            </div>
          </div>
          <!-- end row -->

          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Details</a></li>

            <ul class="nav navbar-nav navbar-right">
                @can('product_update')
                    <li><a href="{{url('client/bom/edit/'.$bom->id)}}"><span class="glyphicon glyphicon-pencil"></span>Edit</a></li>
                @endcan
                @can('product_delete')
                        <li><a onclick="return confirm('Are you sure you want to delete this bom?');" href="{{url('client/bom/delete/'.$bom->id)}}"><span class="glyphicon glyphicon-trash"></span>Delete</a></li>
                @endcan
           </ul>
         </ul>
         <div class="tab-content">
          <div id="home" class="tab-pane fade in active">
            <div class="panel">

              <div class="panel-body">
                <div class="row">

                  <table class="table table-borderless" style="border:0px !important">
                    <caption style="color: #222;font-weight: 600">Details</caption>
                    <tr>
                      <td style="width: 10%">BOM Name </td>
                      <td style="color: #222;width:20%"> <x-product-name :row="$bom" /></td>
                        <td style="width: 10%">Category </td>
                        <td style="color: #222;width:20%"> {{$bom->category_name}}</td>
                        <td style="width: 10%">Material </td>
                        <td style="color: #222;width: 20%"> {{$bom->material_name}}</td>
                    </tr>
                  </table>

                    <table class="table table-borderless" style="border:0px !important">
                      <tr>

{{--                          <td>ID </td>--}}
{{--                          <td style="color: #222;"> {{$bom->inner_diameter}}</td>--}}
{{--                          <td style="">od </td>--}}
{{--                          <td style="color: #222;"> {{$bom->outer_diameter}}</td>--}}
{{--                          <td style="width: 10%">Thikness </td>--}}
{{--                          <td style="color: #222;"> {{$bom->thikness}}</td>--}}
                          <td style="">HSN </td>
                          <td style="color: #222;"> {{$bom->hsn}}</td>
                          <td style="">Price </td>
                          <td style="color: #222;"> {{$bom->price}}</td>
                          <td style="">UOM </td>
                          <td style="color: #222;"> {{$bom->uom_name}}</td>
                          <td style="">GST </td>
                          <td style="color: #222;"> {{$bom->gst_per}}</td>
                      </tr>




                  </table>
                  <div class="col-md-4">
                      <div class="form-group">
                      <label>Bom Image</label>
                      <br>
                        <a href="{{asset('public/product_image/'.$bom->product_image)}}" target="_blank"><img height="80px" width="80px" src="{{asset('public/product_image/'.$bom->product_image)}}"></a>
                    </div>
                  </div>


                </div>

                <div class="tabledata">
                 <table class="table table-striped add-edit-table table-bordered" id="caltable">
                  <thead>
                    <tr>
                      <th style="text-align: center;">Product Name</th>
                      <th style="text-align: center;">Photo</th>
{{--                      <th style="text-align: center;">ID</th>--}}
{{--                      <th style="text-align: center;">OD</th>--}}
{{--                      <th style="text-align: center;">THK</th>--}}
                      <th style="text-align: center;">HSN</th>
                      <th style="text-align: center;">Qty</th>
                      <th style="text-align: center;">Price</th>
                      <th style="text-align: center;">Amount</th>

                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $srno=$total=$gsttotal=$grand=$discount_total=0;
                    ?>
                    @foreach($item as $item)
                    <?php
                    $srno++;
                    ?>
                    <?php
                    $total=$total+$item->amount;
                    $gsttotal=$gsttotal+$item->gst_amount;
                    $grand=$grand+$item->grand_total;
                    $discount_total=$discount_total+$item->discount_amount;
                    ?>
                    <tr id="row{{$srno}}">
                      <td style="vertical-align: top !important;width: 20%">
                        <x-product-name :row="$item" />

                      </td>
                      <td style="vertical-align: top !important;">
                        <a href="{{asset('public/product_image/'.$item->product_image)}}" target="_blank"><img height="80px" width="80px" src="{{asset('public/product_image/'.$item->product_image)}}"></a>

                      </td>
{{--                      <td style="vertical-align: top !important;text-align: center;">--}}
{{--                       {{$item->inner_daimitter}}--}}
{{--                     </td>--}}
{{--                     <td style="vertical-align: top !important;text-align: center;">--}}
{{--                      {{$item->outer_daimitter}}--}}
{{--                    </td>--}}
{{--                    <td style="vertical-align: top !important;text-align: center;">--}}
{{--                     {{$item->thikness}}--}}
{{--                   </td>--}}
                   <td style="vertical-align: top !important;text-align: center;">
                     {{$item->hsn}}
                   </td>
                   <td style="vertical-align: top !important;text-align: center;">
                     {{$item->qty}}
                   </td>

                   <td style="vertical-align: top !important;text-align: center;">
                     {{$item->price}}
                   </td>


                   <td style="vertical-align: top !important;text-align: center;">
                     {{$item->amount}}
                   </td>

              </tr>
              @endforeach


            </tbody>

          </table>
          <table class="table table-striped add-edit-table table-bordered" style="margin-left: 62%;width: 38%;">
            <tr>
              <td colspan="10" style="text-align: right;">Item Total (+)</td><td style="text-align: right;">{{$bom->item_total}}</td><td></td>
            </tr>


          </table>
                    <table class="table table-borderless" style="border:0px !important">
                        <tr>
                            <td style="width: 10%">Description</td>
                            <td colspan="13">{!! $bom->description !!}</td>
                        </tr>
                    </table>
        </div>



      </div>
      <!-- end: page -->

    </div> <!-- end Panel -->

  </div> <!-- container -->

</div> <!-- content -->

@endsection
