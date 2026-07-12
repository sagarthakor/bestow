  @extends('admin.table_header_script')

          <style type="text/css">
  .listSearchContributor {
    min-height: 28px;
    width: 100%;
    min-width: 100px;
}
.inputElement {
    height: 30px;
    width: 100%;
    border-radius: 1px;
    box-shadow: none;
    border: 1px solid #cccccc;
}
input[type="text"].inputElement, input[type="password"].inputElement {
    padding: 3px 8px;
}
</style>
                <div class="row">
                    @if(session()->has('message'))
                    <div class="col-sm-12">
                        <div class="alert alert-info" style="background-color: #188ae2 !important">
                            <strong style="color: #fff">{{session()->get('message')}}</strong>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-12">

                        <div class="table-responsive">
                               <form method="get">
                                   <table id="datatable" class="table table-striped table-bordered">
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
                                               <td  style="vertical-align: top;"><a target="_blank" href="{{url('client/vendor/contact/view/'.$data->id)}}">{{$data->contact_name}}</a></td>
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
           {{$cdata->links()}}
          </form>



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
      </div>
  </div>
</div>
@extends('admin.table_footer_script')
