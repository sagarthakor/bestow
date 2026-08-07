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

                        <div class="card-box table-responsive">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                      <th>Sr.</th>
                                      <th>Quot No</th>
                                       <th>Client Name</th>
                                      <th>Product Name</th>
                                      <th>Price</th>
                                     
                                  </tr>
                              </thead>


                              <tbody>
                               
                                
                                <?php $srno=0; ?>
                                @foreach($quotitem as $data)
                                <?php $srno++; ?>
                                <tr>
                                  <td style="width: 5%;vertical-align: top;"> {{$srno}}</td>
                                  <td width="12%"  style="vertical-align: top;">
                                    {{$data->quotation_no}}</a></td>

                           <!--      <td  style="vertical-align: top;"></td>
                            <td  style="vertical-align: top;"></td> -->
                            <td  style="vertical-align: top;">{{$data->customer_name}}</td>
                            <td  style="vertical-align: top;"><x-product-name :row="$data" /></td>
                            <td  style="vertical-align: top;width:7%;text-align: left;">{{number_format($data->price)}}
                            </td>
                            
                         
                      </tr>
                      @endforeach
                  </tbody>
              </table>

              

          </div>
      </div>
  </div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mail</h4>
      </div>
      <div class="modal-body">
        {{Form::open(['method'=>'post','route'=>'post.quot_mail_send'])}}
        <input type="hidden" id="qid" name="qid">
        <div class="col-md-12">
          <div class="form-group">
            <label>To</label>
            <input type="text" class="form-control" name="to_email" id="to_email">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>CC</label>
            <input type="text" class="form-control" name="to_cc" id="to_cc">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Subject</label>
            <input type="text" class="form-control" name="to_subject" id="to_subject">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Body</label>
            <textarea class="form-control" name="to_body" id="to_body"></textarea>
          
          </div>
        </div>
        <div class="col-sm-4">
          <button class="btn btn-primary">Send</button>
        </div>
        {{Form::close()}}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@extends('admin.table_footer_script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
  $(".modalopen").click(function(){
    //alert("ds");
    var id=$(this).data("id");
    var primary_email=$("#primary_email"+id).val();
    var secondary_email=$("#secondary_email"+id).val();
   var subject_mail=$("#subject_mail"+id).val();
    var quot_id=$("#quot_id"+id).val();

    $("#to_email").val(primary_email+','+secondary_email);
    $("#qid").val(quot_id);
     $("#to_subject").val(subject_mail);
  });
</script>

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
