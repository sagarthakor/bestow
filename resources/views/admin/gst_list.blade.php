@extends('admin.layout.table_master_material')

@section('title', 'List of GST')

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
                            <h4 class="page-title">GST</h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>

                                <li class="active">
                                    GST List
                                </li>
                                <li style="text-align: right;margin-bottom: 5px">
                                    <a class="btn btn-primary" href="{{route('admin.gst.add')}}">Add New</a>
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
                        <!--  <div class="row">
                            <form method="get">
                            <div class="col-sm-4">
                              <input type="text" class="form-control" placeholder="GST %" name="gst_per" value="@if(isset($_GET['gst_per'])){{$_GET['gst_per']}}@endif">
                            </div>
                          </form>
                          </div> -->

                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>GST Percentage</th>
                                    <th></th>
                                </tr>
                                </thead>


                                <tbody>
                                <?php $srno=0; ?>
                                @foreach($cdata as $data)
                                    <?php $srno++; ?>
                                    <tr>
                                        <td  style="vertical-align: top;width: 5%">{{$srno}}</td>
                                        <td  style="vertical-align: top;text-align: center;">{{$data->gst_per}}</td>

                                        <td  style="vertical-align: top;width: 5%">

                                            <a href="{{route('admin.gst.edit',['id' => $data->id])}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>

                                            <a href="{{route('admin.gst.delete',['id' => $data->id])}}"  onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-xs btn-danger waves-effect"><i class="fa fa-trash-o"></i> Delete</a>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{$cdata->links()}}
                        </div>
                    </div>
                </div>


                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script type="text/javascript">
                    // Using jQuery.

                    $(function() {
                        $('form').each(function() {
                            $(this).find('input').keypress(function(e) {
                                // Enter pressed?
                                if(e.which == 10 || e.which == 13) {
                                    this.form.submit();
                                }
                            });

                            $(this).find('input[type=submit]').hide();
                        });
                    });
                </script>
                <!-- end row -->



            </div> <!-- container -->

        </div> <!-- content -->

@endsection
