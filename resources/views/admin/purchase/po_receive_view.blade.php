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
                      
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Receive Date</th>
                                <th>Notes</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $srno=0;
                            ?>
                            @foreach($po_receive as $precev)
                            <?php
                            $srno++;
                            $totitem=0;
                            ?>
                                <tr>
                                    <td style="width:5%">{{$srno}}</td>
                                    <td style="width:10%">{{date('d-m-Y',strtotime($precev->receive_date))}}</td>
                                    <td>{{$precev->note}}</td>
                                </tr>
                                
                                <tr><td colspan="3">
                                    <table class="table table-bordered">
                                       <tr>
                                           <th style="width:5%">#.</th>
                                           <th>Product Name</th>
                                            <th style="width:8%">Order Qty</th>
                                            <th style="width:10%">Received Qty</th>
                                            <th style="width:10%">Remaining Qty</th>
                                        </tr> 
                                <?php
                                $remaining=0;
                                ?>
                                @foreach($po_receive_item as $recvitem)
                                @if($recvitem->purchase_receive_id == $precev->id)
                                <?php
                                $totitem++;
                                $remaining=$recvitem->order_qty-$recvitem->qty_received;
                                $remaining=$remaining+$recvitem->qty_received;
                                ?>
                                    <tr>
                                        <td>{{$totitem}}</td>
                                        <td>{{$recvitem->product_name}}</td>
                                        <td style="text-align:center">{{$recvitem->order_qty}}</td>
                                        <td style="text-align:center">{{$recvitem->qty_received}}</td>
                                        <td style="text-align:center">{{$recvitem->rem_qty}}</td>
                                    </tr>
                                @endif    
                                @endforeach
                                 </table>
                            @endforeach
                            <tr>
                               
                            </tr>    
                         
                            
                            </tbody>
                        </table>
                        </form>
                        

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

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(".modalopen").click(function () {
            var id = $(this).data("id");
            var primary_email = $("#primary_email" + id).val();
            var secondary_email = $("#secondary_email" + id).val();
            var subject_mail = $("#subject_mail" + id).val();
            var quot_id = $("#quot_id" + id).val();

            $("#to_email").val(primary_email + ',' + secondary_email);
            $("#qid").val(quot_id);
            $("#to_subject").val(subject_mail);
        });

        ClassicEditor
            .create(document.querySelector('#to_body'))
            .catch(error => {
                console.error(error);
            });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        // Using jQuery.

        $(function () {
            $('form').each(function () {
                $(this).find('input').keypress(function (e) {
                    // Enter pressed?
                    if (e.which == 10 || e.which == 13) {
                        this.form.submit();
                    }
                });

                $(this).find('input[type=submit]').hide();
            });
        });
        
       $(function() {
  $('tr.parent td span.btn')
    .on("click", function(){
       
    var idOfParent = $(this).parents('tr').attr('id');
    //alert(idOfParent);
    $('tr.child-'+idOfParent).toggle('slow');
    
    var textbtn=$('.'+idOfParent).text();
   if(textbtn=="Show")
   {
       //alert("hide");
       $('.'+idOfParent).text("Hide");
   }
   if(textbtn=="Hide")
   {
       //alert("show");
       $('.'+idOfParent).text("Show");
   }
    
  });
  $('tr[class^=child-]').hide().children('td');
  
});
    </script>
    <script>
        $(function () {
            $("#start_date").datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'dd-mm-yy'
            });
        });
    </script>
