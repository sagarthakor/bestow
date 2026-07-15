@extends('admin.layout.table_master_material')

@section('title', 'List of Category')

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
                <h4 class="page-title">Customer Contact List </h4>
                <ol class="breadcrumb p-0 m-0">
                  <li>
                    <a href="#">{{Session::get('software_title')}}</a>
                  </li>
                  <li>
                    <a href="{{route('admin.customers.list')}}">Customer List </a>
                  </li>
                  <li class="active">
                    Contact List
                  </li>
                  @can('customer_contact_create')
                      <li style="text-align: right;margin-bottom: 5px">
                          <a class="btn btn-primary" href="{{route('admin.contact.add')}}">Add New</a>
                      </li>
                  @endcan
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
              <div class="card-box">
                  <h4 class="m-t-0 header-title">Filter</h4>
                  <form method="get">
                  <div class="row">
                      <div class="col-md-2">
                          <div class="form-group">
                              <label>Contact Name</label>
                              <input type="text" placeholder="Contact Name" class="form-control" value="<?php if(isset($_GET['contact_name'])){echo $_GET['contact_name'];} ?>" name="contact_name">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label>Primary Phone</label>
                              <input type="text" placeholder="Primary Phone" class="form-control" name="primary_phone" value="<?php if(isset($_GET['primary_phone'])){echo $_GET['primary_phone'];} ?>">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label>Primary Email</label>
                              <input type="text" placeholder="Primary Email" class="form-control" name="primary_email" value="<?php if(isset($_GET['primary_email'])){echo $_GET['primary_email'];} ?>">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label>Customer Name</label>
                              <input type="text" placeholder="Customer Name" class="form-control" name="customer_name" value="<?php if(isset($_GET['customer_name'])){echo $_GET['customer_name'];} ?>">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label>Department</label>
                              <input type="text" placeholder="Department" class="form-control" name="department" value="<?php if(isset($_GET['department'])){echo $_GET['department'];} ?>">
                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group">
                              <label>Designation</label>
                              <input type="text" placeholder="Designation" class="form-control" name="designation" value="<?php if(isset($_GET['designation'])){echo $_GET['designation'];} ?>">
                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-2">
                          <button class="btn btn-primary waves-effect waves-light"><i class="fa fa-search"></i> Search</button>
                      </div>
                  </div>
                  </form>
              </div>

              <div class="card-box table-responsive">
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th >Sr.</th>
                    <th>Contact Name</th>
                      <th>Primary Phone</th>
                      <th>Primary Email</th>
                    <th>Customer Name</th>
                    <th>Department Name</th>
                    <th>Designation Name</th>
                    <th></th>
                  </tr>
                  </thead>

                    <tbody>
                      <?php $srno=0; ?>
                      @foreach($cdata as $data)
                      <?php $srno++; ?>
                      <tr>
                        <td style="width: 2%"> {{($cdata->currentPage() - 1) * $cdata->perPage() + $loop->iteration}}</td>
                        <td  style="vertical-align: top;width: 30%"><a href="{{route('admin.contact.view',['id' => $data->id])}}">{{$data->contact_name}}</a></td>
                          <td  style="vertical-align: top;">{{$data->primary_phone}}</td>
                          <td  style="vertical-align: top;">{{$data->primary_email}}</td>

                          <td  style="vertical-align: top;">{{$data->customer_name}}</td>
                        <td  style="vertical-align: top;">{{$data->department}}</td>
                        <td  style="vertical-align: top;">{{$data->designation}}</td>

                        <td class="actions" style="vertical-align: top;white-space: nowrap;">
                            @can('customer_contact_update')
                                <a href="{{route('admin.contact.edit',['id' => $data->id])}}" class="btn btn-xs btn-primary waves-effect"><i class="fa fa-pencil"></i> Edit</a>
                            @endcan
                        </td>

                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                  {{$cdata->appends(request()->input())->links()}}
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
