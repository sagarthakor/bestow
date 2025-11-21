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

          <script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>
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
                              <table class="table table-striped table-bordered">
                                <thead>
                                      <tr>
                                      <th>Sr.</th>
                                      <th> {{Form::open(['method'=>'get'])}}
                                    @if(isset($_GET['quotno_asc']))
                                       <button style="background: #fff;border:#fff">Quot No <input type="hidden" name="quotno_desc" value="quotno_desc">
                                        <i class="fa fa-sort"></i>
                                       </button>
                                    @endif
                                     @if(isset($_GET['quotno_desc']))
                                       <button style="background: #fff;border:#fff">Quot No  <input type="hidden" name="quotno_asc" value="quotno_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                    @if(!isset($_GET['quotno_asc']) and !isset($_GET['quotno_desc']))
                                     <button style="background: #fff;border:#fff">Quot No  <input type="hidden" name="quotno_asc" value="quotno_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                </form>
                              </th>

                                      <th>
                                      {{Form::open(['method'=>'get'])}}
                                    @if(isset($_GET['quot_date_asc']))
                                       <button style="background: #fff;border:#fff">Quot Date<input type="hidden" name="quot_date_desc" value="quot_date_desc">
                                        <i class="fa fa-sort"></i>
                                       </button>
                                    @endif
                                     @if(isset($_GET['quot_date_desc']))
                                       <button style="background: #fff;border:#fff">Quot Date <input type="hidden" name="quot_date_asc" value="quot_date_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                    @if(!isset($_GET['quot_date_asc']) and !isset($_GET['quot_date_desc']))
                                     <button style="background: #fff;border:#fff">Quot Date  <input type="hidden" name="quot_date_asc" value="quot_date_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                </form>
                              </th>

                                      <th>
                                      {{Form::open(['method'=>'get'])}}
                                    @if(isset($_GET['client_asc']))
                                       <button style="background: #fff;border:#fff">Client Name<input type="hidden" name="client_desc" value="client_desc">
                                        <i class="fa fa-sort"></i>
                                       </button>
                                    @endif
                                     @if(isset($_GET['client_desc']))
                                       <button style="background: #fff;border:#fff">Client Name <input type="hidden" name="client_asc" value="client_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                    @if(!isset($_GET['client_asc']) and !isset($_GET['client_desc']))
                                     <button style="background: #fff;border:#fff">Client Name <input type="hidden" name="client_asc" value="client_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                </form>
                              </th>

                                      <th>
                                      {{Form::open(['method'=>'get'])}}
                                    @if(isset($_GET['subject_asc']))
                                       <button style="background: #fff;border:#fff">Subject<input type="hidden" name="subject_desc" value="subject_desc">
                                        <i class="fa fa-sort"></i>
                                       </button>
                                    @endif
                                     @if(isset($_GET['subject_desc']))
                                       <button style="background: #fff;border:#fff">Subject <input type="hidden" name="subject_asc" value="subject_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                    @if(!isset($_GET['subject_asc']) and !isset($_GET['subject_desc']))
                                     <button style="background: #fff;border:#fff">Subject <input type="hidden" name="subject_asc" value="subject_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                  </form>
                                  </th>
                                      <th>
                                       {{Form::open(['method'=>'get'])}}
                                    @if(isset($_GET['amount_asc']))
                                       <button style="background: #fff;border:#fff">Amount<input type="hidden" name="amount_desc" value="amount_desc">
                                        <i class="fa fa-sort"></i>
                                       </button>
                                    @endif
                                     @if(isset($_GET['amount_desc']))
                                       <button style="background: #fff;border:#fff">Amount <input type="hidden" name="amount_asc" value="amount_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                    @if(!isset($_GET['amount_asc']) and !isset($_GET['amount_desc']))
                                     <button style="background: #fff;border:#fff">Amount <input type="hidden" name="amount_asc" value="amount_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                  </form>
                                </th>
                                      <th>
                                      {{Form::open(['method'=>'get'])}}
                                    @if(isset($_GET['stage_asc']))
                                       <button style="background: #fff;border:#fff">Stage<input type="hidden" name="stage_desc" value="stage_desc">
                                        <i class="fa fa-sort"></i>
                                       </button>
                                    @endif
                                     @if(isset($_GET['stage_desc']))
                                       <button style="background: #fff;border:#fff">Stage <input type="hidden" name="stage_asc" value="stage_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                    @if(!isset($_GET['stage_asc']) and !isset($_GET['stage_desc']))
                                     <button style="background: #fff;border:#fff">Stage <input type="hidden" name="stage_asc" value="stage_asc"> <i class="fa fa-sort"></i></button>
                                    @endif
                                  </form>
                                  </th>

                                      <th></th>
                                  </tr>
                              </thead>


                              <tbody>
                                 <tr>
                                  <td>
                                    
                                    {{Form::open(['method'=>'get'])}}
                                    <button>search</button>
                                  </td>
                                  <td>
                                   <input type="text" value="<?php if(isset($_GET['quot_no'])){echo $_GET['quot_no'];} ?>" name="quot_no" placeholder='Quot No' class="listSearchContributor inputElement">
                                 </td>
                                 <td>
                                     <input type="text" value="<?php if(isset($_GET['quot_date'])){echo $_GET['quot_date'];} ?>" name="quot_date" placeholder='Quot Date' class="listSearchContributor inputElement" id="start_date" autocomplete="off">
                                 </td>
                                 <td>
                                  <input type="text" value="<?php if(isset($_GET['client_name'])){echo $_GET['client_name'];} ?>" name="client_name" placeholder='Client Name' class="listSearchContributor inputElement">
                                 </td>
                                 <td>
                                   <input type="text" name="subject" class="listSearchContributor inputElement" value="<?php if(isset($_GET['subject'])){echo $_GET['subject'];} ?>" placeholder='Subject'>
                                 </td>
                                 <td>
                                    <input type="text" name="amount" style="width:85px;border-radius: 1px;
    box-shadow: none;
    border: 1px solid #cccccc;height: 30px;padding: 3px 8px;"    value="<?php if(isset($_GET['amount'])){echo $_GET['amount'];} ?>" placeholder='Amount'>
                                 </td>
                                 <td>
                                   <input type="text" name="quot_stage" class="listSearchContributor inputElement" value="<?php if(isset($_GET['quot_stage'])){echo $_GET['quot_stage'];} ?>" placeholder='Quot Stage'>
                                 </td>
                                 <td></form></td>
                                </tr>
                               
                                
                                <?php $srno=0; ?>
                                @foreach($list as $data)
                                <?php $srno++; ?>
                                <tr>
                                  <td   style="vertical-align: top;">{{($list->currentPage() - 1) * $list->perPage() + $loop->iteration}}</td>
                                  <td width="12%"  style="vertical-align: top;">
                                   <a target="_blank" href="{{url('client/quot/normal/view/'.$data->id)}}">{{$data->quotation_no}}</a></td>

                                  <td width="10%"  style="vertical-align: top;">
                                   {{date('d-m-Y',strtotime($data->quot_date))}}
                                  </td>

                           <!--      <td  style="vertical-align: top;"></td>
                            <td  style="vertical-align: top;"></td> -->
                            <td  style="vertical-align: top;">{{$data->customer_name}}</td>
                            <td  style="vertical-align: top;">{{$data->subject}}</td>
                            <td  style="vertical-align: top;width:7%;text-align: left;">{{number_format($data->grand_total)}}</td>
                            <td  style="vertical-align: top;text-align: center;width: 10%">{{$data->quot_stage}}</td>
                            
                            <td  style="vertical-align: top;width: 15%">
                              <a class="table-btn" title="edit" target="_blank"  href="{{url('client/quot/normal/edit/'.$data->id)}}"title="edit"><i class="fa fa-pencil" style="margin:10px;font-size: 10px;"></i></a>

                             <a class="table-btn" title="preview" target="_blank" href="{{url('admin/quot_normal_preview/'.$data->id)}}"><i class="fa fa-eye" style="margin:10px;font-size: 10px;"></i></a>

                               <input type="hidden" name="primary_email" id="primary_email{{$srno}}" value="{{$data->primary_email}}">
                               <input type="hidden" name="secondary_email" id="secondary_email{{$srno}}" value="{{$data->secondary_email}}">

                               <input type="hidden" name="subject_mail" id="subject_mail{{$srno}}" value="{{$data->subject}}">
                               
                               <input type="hidden" name="quot_id" id="quot_id{{$srno}}" value="{{$data->id}}">
                              
                               <a class="table-btn" title="pdf"  href="{{url('admin/quot_normal_print/'.$data->id)}}"><i class="fa fa-file-pdf-o" style="margin:10px;font-size: 10px;"></i></a>

                                <!-- <a class="table-btn" title="mail" href="{{url('admin/quot_mail/'.$data->id)}}"><i class="fa fa-envelope" style="margin:10px;font-size: 10px;"></i></a> -->
                                  <a class="table-btn modalopen" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal"  data-id="{{$srno}}" title="mail" href="#">
                                    <i class="fa fa-envelope" style="font-size: 10px;"></i>
                                  </a>

                                @if($data->so_status=="Y")
                                @else
                               <!--  <a class="table-btn" title="sales order" href="{{url('client/sales_order/create/'.$data->id)}}" >Create SO.</a> -->
                                @endif
                                   <a class="table-btn" title="delete"  href="{{url('admin/quotation_delete/'.$data->id)}}" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa fa-trash" style="margin:10px;font-size: 10px;"></i></a>
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>

              {{$list->appends(request()->input())->links()}}

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
                            {{Form::open(['method'=>'post','route'=>'post.quot_normal_mail_send'])}}
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
                                <textarea class="form-control" name="to_body" id="to_body">
                                 Hello sir/Madam,<br><br>
                                 Thank You For doing business with us.<br>please find the Quotation and lets me know if any query.
                                 <br><br><br>
                                 Thanks & Regards
                                 <br>
                                 {{$company}}
                                 <br><br>
                                 Sales Team
                               </textarea>

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
  ClassicEditor
            .create( document.querySelector( '#to_body' ) )
            .catch( error => {
                console.error( error );
            } );
</script>
