<?php 
session_start();include('acess_admin/include/connection.php');
function generateRandomString($length = 10) 
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
       $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
     return $randomString;
}

$randstring = generateRandomString();

$id = $_SESSION['customerID'];

if(isset($_POST['submit']))
{
  $ticketID = $randstring;
    
    $fname = $_POST['fname'];
    $lname =  $_POST['lname'];
    $emailID =  $_POST['emailID'];
    $homeaddress = $_POST['homeaddress'];
    $zipcode =  $_POST['zipcode'];
    $phone = $_POST['phone'];
    $referredby =  $_POST['referredby'];
    $Querysubject = $_POST['Querysubject'];
    $Query =  $_POST['Query'];
    $attachement = $_FILES['attachement']['name'];
      
     $target_folder='../QueryScreenShots/';

      if(isset($_FILES['attachement']))
        {
           $errors= array();
           $file_name = $_FILES['attachement']['name'];
           $file_size =$_FILES['attachement']['size'];
           $file_tmp =$_FILES['attachement']['tmp_name'];
           $file_type=$_FILES['attachement']['type'];
           $file_ext = @end(explode('.', $file_name));

           $extensions= array("jpeg","jpg","png");

           if(in_array($file_ext,$extensions)=== false)
           {
              $errors[]='File size must be excately 2 MB';
              echo '<script language="javascript">';
              echo 'alert("Extension not allowed, please choose a JPEG or PNG file.")';
              echo '</script>';
           }
           
           if($file_size > 2097152)
           {
              $errors[]='File size must be excately 2 MB';
              echo '<script language="javascript">';
              echo 'alert("File size must be excately 2 MB.")';
              echo '</script>';
           }

           if(empty($errors)==true) 
           {
              move_uploaded_file($file_tmp,"acess_admin/assets/images/QueryScreenShots/".$file_name);
           }
           else
           {
              print_r($errors);
           }
        }


      $sql = "insert into `tickets`(`ticketID`,`customerID`,`firstName`,`lastName`,`emailID`,`homeAddress`,`zipCode`,
                                `phoneNo`,`referBy`,`querySubject`,`query`,`attachment`)
              values('$ticketID','$id','$fname','$lname','$emailID','$homeaddress','$zipcode','$phone','$referredby',
                                '$Querysubject','$Query','$file_name')";
      echo $sql;

      if(mysqli_query($con,$sql))
        {
            echo '<script language="javascript">';
            echo "alert('Thank you For submitting Your Query!')";
            echo '</script>';

           include("include/supportEmailFormat.php");
           $headers  = 'From: info@dwarkeshit.com' . "\r\n";           
           $headers = "MIME-Version: 1.0" . "\r\n";
           $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
           mail($emailID,'Support Ticket',$messaage,$headers);
           header("Location:".$usertpath."dwarkesh_web/support_form.php?success=1");
        }
      else
        {
            echo '<script language="javascript">';
            echo 'alert("Fail To Submitting Your Query!")';
            echo '</script>';
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
                     <h4 class="text-uppercase"> Raise Ticket</h4>
                     <ol class="breadcrumb">
                        <li><a href="#">Home</a>
                     </li>
                     
                     <li class="active">Raise Ticket</li>
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
                     <h3 class="text-uppercase">Your Details </h3>
                  </div>
               </div>
               </div> <!--end row-->
               <div class="row">
                  <div class="col-md-12">
                     <div class="col-md-2"></div>
                     <div class="col-md-8">
                        <form role="form" action="support_form.php" method="POST" enctype="multipart/form-data">
                           <div class="box">
                              <div class="form-group">
                                 <label for="exampleInputName">First Name *</label>
                                 <input type="text" class="form-control" name="fname" id="exampleInputName" placeholder="First name" required>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputName">Last Name *</label>
                                 <input type="text" class="form-control" name="lname" id="exampleInputName" placeholder="Last name" required>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputEmail1">Email address *</label>
                                 <input type="email" class="form-control" name="emailID" id="exampleInputEmail1" placeholder="Enter email" required>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputAddress">Street Address *</label>
                                 <input type="text" class="form-control" name="homeaddress" id="exampleInputAddress" placeholder="Enter address" required>
                              </div>

                              <div class="form-group">
                                 <label for="exampleInputZipcode">Zip Code *</label>
                                 <input type="tel" class="form-control"  name="zipcode" pattern="[0-9]{3}[0-9]{3}" id="exampleInputZipcode" placeholder="Enter zipcode" required>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputNo">Phone Number *</label>
                                 <input type="tel" name="phone" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" class="form-control" id="exampleInputNo" placeholder="Enter contact number" required>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputReferred">Referred By </label>
                                 <input type="text" class="form-control" name="referredby" id="exampleInputReferred">
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputReferred">Subject </label>
                                 <input type="text" class="form-control" name="Querysubject" id="exampleInputReferred">
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputReferred">Query Description </label>
                                 <textarea rows="5" cols="5" name="Query" class="form-control" id="Query" placeholder="Type Your Query Here..."></textarea>
                              </div>
                              <div class="form-group">
                                 <label for="exampleInputReferred">Attachement</label>
                                 <input type="file" name="attachement" class="form-control" id="fileupload" accept="image/*">
                              </div>
                           </div>
                           <div class="col-md-12 m-t-10 m-b-10">
                              <button type="submit" class="btn btn-small btn-rounded btn-dark-solid" name="submit">Submit</button>
                           </div>
                        </form>
                     </div>
                     <div class="col-md-2"></div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!--body content end-->
      <?php include('include/footer.php'); ?>
   </div>
   <?php include('include/footerscript.php'); ?>

   <?php if($_GET["success"]){ ?>
      <script>
       toastr.info("Thank you for your Enquiry..!! Our team will look into it and will get back to you withing 24 working hours.!");
      </script>
      <?php }?>
</body>
</html>