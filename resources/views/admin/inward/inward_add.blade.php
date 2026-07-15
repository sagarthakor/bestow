@extends('admin.layout.master_material')

@section('title', 'Add Inward')

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
                            <h4 class="page-title">Inward</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin/index') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li class="active">
                                    Purchase List
                                </li>
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->


                {{Form::open(['method'=>'post','route'=>'post.stock_insert'])}}

                <div class="panel">

                    <div class="panel-body">
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
                        <!-- <div class="col-md-4" style="display: none;" id="qno">
                                                          <div class="form-group">
                                                                    <label class="control-label">Quotation No</label>
                                                                    {{Form::text('quot_no',null,['class'=>'form-control','id'=>"quot_no"])}}

                            </div>
                        </div> -->
                            <div class="col-md-6">

                                <div class="form-group">
                                    <?php
                                    $inward_from = ['' => 'select an Option', 'Customer Material Inward(Customer Place)' => 'Customer Material Inward(Customer Place)', 'Buy Back Material Inward(Customer Place)' => 'Buy Back Material Inward(Customer Place)', '3rd Party Material Inward(Third Party Vendor/Service Center)' => '3rd Party Material Inward(Third Party Vendor/Service Center)', 'Demo Material Inward(Returnable Basis)' => 'Demo Material Inward(Returnable Basis)',"Physical Stock Transfer"=>"Physical Stock Transfer","Production"=>"Production"]
                                    ?>
                                    <label class="control-label">Inward From<span style="color: red">*</span></label>
                                {{Form::select('inward_from',$inward_from,null,["required",'class'=>'form-control js-example-basic-single','id'=>'inward_from'])}}

                                <!--   <div class="input-group-btn" style="top: 10px">
     <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_customer()"></span>
    </div> -->
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label">Customer </label>
                                {{Form::select('customer',$customer,null,['class'=>'form-control js-example-basic-single','id'=>'customer'])}}

                                <!--   <div class="input-group-btn" style="top: 10px">
     <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_customer()"></span>
    </div> -->
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label">Vendor</label>
                                {{Form::select('vendor',$vendor,null,['class'=>'form-control js-example-basic-single','id'=>'customer'])}}

                                <!--   <div class="input-group-btn" style="top: 10px">
     <span class="input-group-addon" id="start-date"><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="add_customer()"></span>
    </div> -->
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Inward Date <span style="color: red">*</span></label>
                                    {{Form::text('inward_date',date('d-m-Y'),["required",'class'=>'form-control input-daterange-datepicker','id'=>"quot_date",'autocomplete'=>'off'])}}

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Type Of Inward<span style="color: red">*</span></label>
                                    <?php
                                    $inwardtype = ['' => 'select an Option', 'Returnable' => 'Returnable', 'Non Returnable' => 'Non Returnable', 'New Material Supplied' => 'New Material Supplied', 'New Material Purchased' => 'New Material Purchased',"Stock Transfer"=>"Stock Transfer","Production"=>"Production"];
                                    ?>
                                    {{Form::select('inward_type',$inwardtype,null,["required",'class'=>'form-control js-example-basic-single','id'=>"inward_type"])}}

                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes (For Internal Use)</label>
                                    {{Form::text('remark',null,['class'=>'form-control'])}}

                                </div>
                            </div>

                            <!--   <div class="col-sm-6">
                                  <div class="m-b-30">
                                      <button id="addToTable" class="btn btn-success waves-effect waves-light">Add <i class="mdi mdi-plus-circle-outline"></i></button>
                                  </div>
                              </div> -->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" id="poitem">
                                </div>
                                <div class="table-responsive" id="poitem1">
                                    <table id="caltable" class="table table-striped add-edit-table table-bordered">
                                        <tr>
                                            <th>Bar Code</th><th>Product</th><th>Inward Qty</th><th>Remove</th>
                                        </tr>
                                        <tr>
                                            <td width="10%"><input type="text" onfocusout="search_product(this)" class="itemname form-control"></td>
                                            <td width="70%">

                                                <select class="form-control js-example-basic-single product" name="product[]">
                                                    <option value="">select product</option>
                                                    @foreach($product1 as $prod)
                                                        <option value="{{$prod->id}}">{{$prod->item_code}} - {{$prod->product_name}}</option>
                                                    @endforeach
                                                </select>

                                            </td>

                                            <td>
                                                <input type="text" name="received[]" class="form-control">
                                            </td>
                                            <td class="actions" style="vertical-align: top !important;text-align:center">
                                                <a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-default" id="add_product">+ Add Product</a>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-default" id="add_bom">+ Add BOM</a>
                            </div>
                        </div>

                        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">BOM Sub Products List</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive" id="subproducts">

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end: page -->

                </div> <!-- end Panel -->
                <div class="row">
                    <div class="col-md-12" style="text-align: center;">
                        <button onClick="this.form.submit(); this.disabled=true; this.value='Sending…'; " style="text-align: center;" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                {{Form::close()}}
            </div> <!-- container -->

        </div> <!-- content -->


        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


        <script type="text/javascript">

        function search_product(ele){
                    $(ele).closest('tr').find('.product option').remove();
                    var itemname= $(ele).closest('tr').find('.itemname').val();
                    var appurl = "{{url('/')}}";
                    $.ajax({
                        url:appurl+'/api/get_product',
                        data:{itemname:itemname},
                        method:'get',
                        success:function(response){

                            $(ele).closest('tr').find('.product').html(response);
                        }
                    });
                }


            $("#module").change(function () {
                var modules = $("#module").val();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/get_terms',
                    data: {module: modules},
                    method: 'get',
                    success: function (data) {
                        $("#terms").html(data);

                        ClassicEditor
                            .create(document.querySelector('#term_condition'))
                            .catch(error => {
                                console.error(error);
                            });

                    }
                })
            })

            function getpo(vendor) {

                var appurl = "{{url('/')}}";

                $.ajax({
                    url: appurl + '/ajax/get_po',
                    data: {vendor: vendor},
                    method: 'get',
                    success: function (data) {
                        $("#purchase").html(data);
                    }
                });
            }

            function get_item(purchase_no) {
                var purchase = $("#purchase").val();
                //alert(purchase);
                if (purchase == "") {
                    $("#poitem1").show();
                    $("#poitem").hide();
                } else {
                    var appurl = "{{url('/')}}";

                    $.ajax({
                        url: appurl + '/ajax/get_po_item',
                        data: {purchase_no: purchase_no},
                        method: 'get',
                        success: function (data) {
                            $("#poitem").html(data);
                            $("#poitem1").hide();
                            $("#poitem").show();
                        }
                    });
                }

            }

            function get_product(product, srno) {
                var customer = $("#customer").val();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/get_po_product',
                    data: {product: product, customer: customer},
                    method: 'get',
                    dataType: 'json',
                    success: function (data) {
                        var len = data.length;
                        if (len > 0) {
                            $("#qty" + srno).val("");
                            $("#price" + srno).val("");
                            $("#total_amount" + srno).val("");
                            $("#discount_per" + srno).val("");
                            $("#discount_amount" + srno).val("");
                            $("#gst_per" + srno).val("");
                            $("#gst_amount" + srno).val("");

                            $("#description" + srno).val(data[0]['description']);
                            $("#price" + srno).val(data[0]['price']);
                            $("#gst_per" + srno).val(data[0]['gst']);
                            $("#inner_diamitter" + srno).val(data[0]['inner_diameter']);
                            $("#outer_diamitter" + srno).val(data[0]['outer_diameter']);
                            $("#thikness" + srno).val(data[0]['thikness']);
                            $("#hsn" + srno).val(data[0]['hsn']);
                            //$("#make"+srno).val(data[0]['make']);
                        }
                    }
                });
            }

            function checkstock(ele) {
                var customer = $("#customer").val();
                var product = $(ele).closest('tr').find('.bom').val();
                var bom_qty = $(ele).closest('tr').find('.bom_qty').val();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/bominward',
                    data: {product: product,bom_qty:bom_qty},
                    method: 'get',
                    beforeSend: function(){
                        $("#loader").show();
                    },
                    success: function (data) {
                        //$("#exampleModalLong").show();
                        $("#subproducts").html(data);
                        $('#exampleModalLong').modal();
                        var substock=$(".substock").text();
                        if(substock > 0)
                        {
                           // alert("This item not in stock first add in stock after you will make this item delivery challan");

                            setTimeout(function() {   //calls click event after a certain time
                                $(ele).closest('tr').remove();
                            }, 1000);
                        }
                    },
                    complete:function(data){
    // Hide image container
                    $("#loader").hide();
                }
                });

        }

            function get_service(product, srno) {
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/admin/get_product',
                    data: {product: product},
                    method: 'get',
                    dataType: 'json',
                    success: function (data) {
                        var len = data.length;
                        if (len > 0) {
                            $("#description" + srno).val(data[0]['description']);
                            $("#price" + srno).val(data[0]['price']);
                            $("#gst_per" + srno).val(data[0]['gst']);

                            //$("#make"+srno).val(data[0]['make']);
                        }
                    }
                });
            }

            function cal(ele) {
                var qty = $(ele).closest('tr').find('.qty').val();
                var received = $(ele).closest('tr').find('.received').val();

                var remain = Number(qty) - Number(received);
                if (remain >= 0) {
                    $(ele).closest('tr').find('.remaining').val(remain);
                } else {
                    alert("do not enter more qty");
                    $(ele).closest('tr').find('.received').val(0);
                    $(ele).closest('tr').find('.remaining').val(qty);
                }

            }


            function model_close() {
                $("#product_model").hide();
                $("#service_model").hide();
                $("#myModal").hide();
            }

            function customer_save() {
                var organization_name = $("#organization_name").val();
                var website = $("#website").val();
                var primary_phone = $("#primary_phone").val();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/client/ajax_customer_save',
                    data: {organization_name: organization_name, website: website, primary_phone: primary_phone},
                    method: 'get',
                    dataType: 'json',
                    success: function (data) {
                        var len = data.length;
                        if (len > 0) {
                            $("#primary_phone").val(data[0]['primary_phone']);
                            $("#website").val(data[0]['website']);
                            $("#customer").html(data[0]['str']);

                        }
                        $("#myModal").hide();
                    }
                });
            }

            function customer_form() {
                window.location = "{{url('customer_add')}}";
            }

            function product_search(srno) {
                $("#product_model").show();
                $("#srid").val(srno);
            }

            function service_search(srno) {
                $("#service_model").show();
                $("#servicesrid").val(srno);
            }
        </script>

        <script>

            $("#add_product").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;
                var data = "<tr id='row" + i + "'><td style='vertical-align: top !important;text-align: center;width:10%'><input type='text'  onfocusout='search_product(this)' class='itemname form-control'></td><td><select class='product form-control js-example-basic-single' onchange='get_product(this.value," + i + ")' name='product[]' id='product" + i + "'> <option value=''>select</option>@foreach($product1 as $prod)<option value='{{$prod->id}}'>{{$prod->item_code}} - {{$prod->product_name}}</option>@endforeach</select></td><td><input type='text' name='received[]' class='form-control' autocomplete='off'></td>";
                data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';
                $("#caltable").append(data);
                $(document).ready(function () {
                    $('.js-example-basic-single').select2();
                });
                $("#totrow").val(i);
            });

            $("#add_bom").click(function (e) {
                e.preventDefault();
                var i = $("#totrow").val();
                i++;
                var data = "<tr id='row" + i + "'><td style='vertical-align: top !important;text-align: center;width:10%'><input type='text'  onfocusout='search_product(this)' class='itemname form-control'></td><td><select class='product form-control js-example-basic-single bom'  name='bom[]' id='product" + i + "'> <option value=''>select bom</option>@foreach($bom as $bomprod)<option value='{{$bomprod->id}}'>{{$bomprod->product_name}}</option>@endforeach</select></td><td><input oninput='checkstock(this)' type='text' name='bom_qty[]' class='form-control bom_qty' autocomplete='off'></td>";
                data += '<td class="actions" style="vertical-align: top !important;text-align:center"><a style="customer:pointer" onclick="remove_row(this)" class="rowremove"><i class="fa fa-trash" style="font-size: 22px"></i></a></td></tr>';
                $("#caltable").append(data);
                $(document).ready(function () {
                    $('.js-example-basic-single').select2();
                });
                $("#totrow").val(i);
            });

                $("#datatable-buttons").on('click', 'tr', function (e) {
                e.preventDefault();
                var id = $(this).attr('value');
                var srno = $("#srid").val();
                get_product(id, srno)
                $("#product_model").hide();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/client/ajax_getproduct',
                    data: {product: id},
                    method: 'get',
                    success: function (data) {
                        $("#product" + srno).html(data);
                    }
                });
            });


            $("#service_datatable-buttons").on('click', 'tr', function (e) {
                e.preventDefault();
                var id = $(this).attr('value');
                var srno = $("#servicesrid").val();
                get_product(id, srno)
                $("#service_model").hide();
                var appurl = "{{url('/')}}";
                $.ajax({
                    url: appurl + '/client/ajax_getservice',
                    data: {product: id},
                    method: 'get',
                    success: function (data) {
                        $("#product" + srno).html(data);
                    }
                });
            });


            $(".service_btn").click(function (e) {
                e.preventDefault();
            });
            $(".product_btn").click(function (e) {
                e.preventDefault();
            });
            $("#product_btn1").click(function (e) {
                e.preventDefault();
            });

            function add_customer() {
                $("#myModal").show();
            }

            function remove_row(ele) {
                if (confirm("Are you sure you want to delete this?")) {

                    $(ele).closest('tr').remove();

                    var totalss = $(".total");
                    var item_total = 0;
                    for (var i = 0; i < totalss.length; i++) {
                        item_total = Number(item_total) + Number($(totalss[i]).val());
                    }

                    var discount_amount = $(".discount_amount");
                    var discount_total = 0;
                    for (var i = 0; i < discount_amount.length; i++) {
                        discount_total = Number(discount_total) + Number($(discount_amount[i]).val());
                    }

                    var gst_amounts = $(".gst_amount");
                    var gst_total = 0;
                    for (var i = 0; i < gst_amounts.length; i++) {
                        gst_total = Number(gst_total) + Number($(gst_amounts[i]).val());
                    }

                    var netprices = $(".netprice");
                    var netpricestotal = 0;
                    for (var i = 0; i < netprices.length; i++) {
                        netpricestotal = Number(netpricestotal) + Number($(netprices[i]).val());
                    }


                    $("#item_total").val(item_total);
                    $("#discount_total").val(discount_total);
                    $("#gsttotal").val(gst_total);
                    $("#grand_total").val(netpricestotal);
                } else {
                    return false;
                }
            }

            $(".rowremove").click(function () {
                // alert("sdd");
                $(this).parent().parent().remove();
            });
        </script>

        <script type="text/javascript">
            $.noConflict();
            jQuery(document).ready(function ($) {
                $("#quot_date").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
                $("#valid_until").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'dd-mm-yy'
                });
            });


        </script>
@endsection
