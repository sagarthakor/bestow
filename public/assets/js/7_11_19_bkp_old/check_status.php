<?php
include('acess_admin/include/connection.php');
if(isset($_POST['submit']))
{
     $ticketid = $_POST['ticketNo'];
     $emailid = $_POST['emailId'];

     echo $ticketid.$emailid;

     if($emailid != null && $ticketid != null)
     {
         $sql = "SELECT ticketID,customerID,firstName FROM tickets WHERE emailID = '$emailid' AND ticketID = '$ticketid'";                       
         $retrive = mysqli_query($con,$sql);
         $cnt = mysqli_num_rows($retrive);

         echo $cnt;

            if($cnt > 0)
              {
                  while($row=mysqli_fetch_array($retrive,MYSQLI_ASSOC))
                    { 
                      echo $id = $row['ticketID'];
                      echo $fname = $row['firstName'];
                    }
                    // $message_body = "Dear ".$fname.",<br><br>Please click on below URL to check your Ticket Status.<br><br><br><br>http://localhost/dwarkesh_web/solutionComplete.php?id=".$id;
                    
                    echo '<script language="javascript">';
                    echo "alert('Thank you For submitting Your Query!')";
                    echo '</script>';

                    include("include/checkTicketStatusEmailFormat.php");
                    $headers  = 'From: info@dwarkeshit.com' . "\r\n";
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    mail($emailid,'Check Support Ticket Status',$messaage,$headers);
                    header("Location:".$usertpath."check_status.php?success=1");
              }
            else
              {
                echo "<script type='text/javascript'>
                        alert('Please entre Wrong Email & Ticket Number!');
                      </script>";   
              }       
     }
     else
     {
         echo "<script type='text/javascript'>
                 alert('Please entre Email & Ticket Number!');
                  window.location='check_status.php';
               </script>";   
      }                   
}
?>
<!DOCTYPE html>
<html lang="en">
   <?php include('include/headerscript.php'); ?>
   <body>
      <div class="wrapper">
         <?php include('include/header.php'); ?>
         <!--page title start-->
         <section class="page-title">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <h4 class="text-uppercase"> Check Status</h4>
                     <ol class="breadcrumb">
                        <li><a href="#">Home</a>
                     </li>
                     
                     <li class="active">Check Status</li>
                  </ol>
               </div>
            </div>
         </div>
      </section>
      <!--page title end-->
      <!--body content start-->
      <section class="body-content ">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="text-center p-t-30">
                     <h3 class="text-uppercase">Check Ticket Status </h3>
                     <div class="fea_txt">
                        <p class="m-t-15">Please provide your email address and a ticket number. An access link will be emailed to you.</p>
                     </div>
                  </div>
               </div>
               </div> <!--end row-->
               <div class="row">
                  <div class="col-md-12">
                     <div class="col-md-3"></div>
                     <div class="col-md-6">
                        <div class="box m-b-10 border_box">
                           <form role="form" action="check_status.php" method="POST">
                              <div class="form-group">
                                 <label for="exampleInputEmail1">Email address</label>
                                 <input type="email" class="form-control" name="emailId" id="exampleInputEmail1" placeholder="Enter email">
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputPassword1">Ticket Number</label>
                                 <input type="text" class="form-control" name="ticketNo" minlength="10" maxlength="10" id="exampleInputNumber" placeholder="e.g. AB12C34D5">
                              </div>
                              <button type="submit" name="submit" class="btn btn-small btn-rounded btn-dark-solid" >Email Access Link</button>
                           </form>
                        </div>
                     </div>
                     <div class="col-md-3"></div>
                  </div>
               </div>
            </section>
            <!--body content end-->
            <?php include('include/footer.php'); ?>
         </div>
         <?php include('include/footerscript.php'); ?>
      </body>
   </html>