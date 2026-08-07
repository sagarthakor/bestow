@extends('admin.layout.master_material')

@section('title', 'Update | Formula')

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
                            <h4 class="page-title">Formula Update </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="{{ url('admin') }}">{{Session::get('software_title')}}</a>
                                </li>
                                <li>
                                    <a href="{{route('admin.production.formula_list')}}">Formula List </a>
                                </li>
                                <li>
                                    Update Formula
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


                                        {{Form::model($data,['method'=>'post','route'=>'admin.production.formula_update'])}}
                                        {{Form::hidden('id',null)}}
                                        <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-12">
                                            <div class="demo-box">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Nos.</label>
                                                        {{Form::text('nos',null,['class'=>'form-control'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Size</label>
                                                        {{Form::text('size',null,['class'=>'form-control'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Total Material</label>
                                                        {{Form::text('required_qty',null,['oninput'=>'cal()','class'=>'form-control total_mat'])}}

                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <h3>Required Material for production</h3>
                                                </div>

                                                <table id="caltable1" class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Category</th>
                                                            <th>Raw Material</th>
                                                            <th>Qty (grams)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($categoryRows as $row)
                                                        <tr>
                                                            <td>{{ $row['label'] }}</td>
                                                            <td>
                                                                <select name="material[]" class="form-control" onchange="updateUomHint(this)">
                                                                    <option value="">Select {{ $row['label'] }}</option>
                                                                    @foreach($row['materials'] as $mat)
                                                                        <option value="{{ $mat->id }}" data-uom="{{ strtoupper($mat->uom_name ?? '') }}" {{ $mat->id == $row['selected_material'] ? 'selected' : '' }}>{{ \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input value="{{ $row['selected_qty'] }}" style="text-align: right" oninput="cal()" type="text" class="form-control qty required_qty_per" name="qty[]">
                                                                <small class="uom-hint text-muted"></small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>

                                                </table>
                                                <div class="col-md-2">
                                                    <button id="btnsave" class="btn btn-primary">Save</button>
                                                </div>

                                                {{Form::close()}}





                                            </div>

                                        </div>




                                        </div>

                                    </div>


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
        <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
        <script>
            $(document).ready(function () {
                $('.js-example-basic-single').select2();

                $('select[name="material[]"]').each(function () {
                    updateUomHint(this);
                });

                cal();
            });

            function updateUomHint(selectEl) {
                var $hint = $(selectEl).closest('tr').find('.uom-hint');
                var uom = $(selectEl).find(':selected').data('uom');
                if (!uom) {
                    $hint.text('');
                } else if (uom === 'KG') {
                    $hint.text('Enter in grams');
                } else {
                    $hint.text('Unit: ' + uom);
                }
            }

            function cal() {
                const totalMat = Math.round(Number($(".total_mat").val()) || 0);

                const itemTotal = $(".required_qty_per")
                    .map((_, el) => Number($(el).val()) || 0)
                    .get()
                    .reduce((a, b) => a + b, 0);

                const roundedItemTotal = Math.round(itemTotal);

                $("#btnsave").toggle(roundedItemTotal === totalMat);
            }
        </script>
@endsection
