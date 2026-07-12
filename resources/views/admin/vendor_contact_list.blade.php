@extends('admin.layout.table_master')

@section('title', 'List of Vendor Contact')

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
                                <h4 class="page-title">Vendor Contact List </h4>
                                <ol class="breadcrumb p-0 m-0">
                                    <li>
                                        <a href="#">{{Session::get('software_title')}}</a>
                                    </li>
                                    <li>
                                        Vendor
                                    </li>
                                    <li class="active">
                                        Vendro Contact List
                                    </li>
                                </ol>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->




                    <div class="row">
                       <div class="col-sm-4">
                       </div>
                       <div class="col-sm-4">
                       </div>
                       <div class="col-sm-4" style="text-align: right;margin-bottom: 5px">
                        <a class="btn btn-primary" href="{{url('client/vendor/contact/add')}}">Add New</a>
                    </div>




                        </div>
                <div class="row">
                    @if(session()->has('message'))
                    <div class="col-sm-12">
                        <div class="alert alert-info" style="background-color: #188ae2 !important">
                            <strong style="color: #fff">{{session()->get('message')}}</strong>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-12">

                        <div class="card-box table-responsive">

                          {{Form::open(['method'=>'get'])}}
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                      <th >Sr.</th>
                                      <th>Contact Name</th>
                                      <th>Primary Phone</th>
                                      <th>Primary Email</th>
                                      <th>Vendor Name</th>
                                      <th>Department Name</th>
                                      <th>Designation Name</th>

                                  </tr>
                                    <tr>
                                        <td><button>Search</button></td>
                                        <td><input type="text"  class="listSearchContributor inputElement" value="<?php if(isset($_GET['contact_name'])){echo $_GET['contact_name'];} ?>" name="contact_name"></td>
                                        <td><input type="text"  class="listSearchContributor inputElement" value="<?php if(isset($_GET['primary_phone'])){echo $_GET['primary_phone'];} ?>" name="primary_phone"></td>
                                        <td><input type="text"  class="listSearchContributor inputElement" value="<?php if(isset($_GET['primary_email'])){echo $_GET['primary_email'];} ?>" name="primary_email"></td>

                                        <td><input type="text"  class="listSearchContributor inputElement" name="customer_name" value="<?php if(isset($_GET['customer_name'])){echo $_GET['customer_name'];} ?>"></td>
                                        <td> <input type="text" class="listSearchContributor inputElement" name="department" value="<?php if(isset($_GET['department'])){echo $_GET['department'];} ?>"></td>
                                        <td><input type="text" class="listSearchContributor inputElement" name="designation" value="<?php if(isset($_GET['designation'])){echo $_GET['designation'];} ?>"></td>
                                    </tr>
                              </thead>


                              <tbody>

                                <?php $srno=0; ?>
                                @foreach($cdata as $data)
                                <?php $srno++; ?>
                                <tr>
                                  <td style="width: 2%"> {{($cdata->currentPage() - 1) * $cdata->perPage() + $loop->iteration}}</td>
                                  <td  style="vertical-align: top;"><a href="{{route('admin.vendor.contact.view',['id' => $data->id])}}">{{$data->contact_name}}</a></td>
                                    <td  style="vertical-align: top;">{{$data->primary_phone}}</td>
                                    <td  style="vertical-align: top;">{{$data->primary_email}}</td>

                                    <td  style="vertical-align: top;">{{$data->vendor_name}}</td>
                                  <td  style="vertical-align: top;">{{$data->department}}</td>
                                  <td  style="vertical-align: top;">{{$data->designation}}</td>

                        <!-- <td  style="vertical-align: top;"></td> -->

                  </tr>
                  @endforeach
              </tbody>
          </table>
        </form>
            {{$cdata->links()}}

      </div>
  </div>
</div>



<!-- end row -->



</div> <!-- container -->

</div> <!-- content -->



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

@endsection
