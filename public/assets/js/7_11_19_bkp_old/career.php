<?php
   
      include("acess_admin/include/connection.php");
      ini_set("display_errors", "off");

      if($_REQUEST['submit'])
      {

         $fname = $_POST['fname'];
         $email = $_POST['email'];
         $mobile_no = $_POST['mobile_no'];
         $technical_skill = $_POST['technical_skill'];
         $designation = $_POST['designation'];
         $experience = $_POST['experience'];
         $notice_period = $_POST['notice_period'];
         $resume = $_FILES['resume']['name'];

        $qry = "INSERT INTO career 
         (fname,email,mobile_no,technical_skill,designation,experience,notice_period,resume) 
         VALUES
         ('$fname','$email','$mobile_no','$technical_skill','$designation','$experience','$notice_period','$resume')";

          move_uploaded_file($_FILES['resume']['tmp_name'],"acess_admin/assets/images/resume/".$resume);

         $result = mysqli_query($con,$qry);

         if($result)
         {
            echo "<script>alert('Successfully Submited..')</script>";
         }
         else
         {
            echo "<script>alert('Error..')</script>";
         }
         include("include/careerEmailFormat.php");
         $headers  = 'From: info@dwarkeshit.com' . "\r\n";
         $headers = "MIME-Version: 1.0" . "\r\n";
         $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
         mail($email,'Career',$messaage,$headers);
         header("Location:".$usertpath."career.php?success=1");
      }

?>

<!DOCTYPE html>
<html lang="en">
   <?php include('include/headerscript.php'); ?>
   <style>
      .backgound2 {
      background: #13375e;
      }
      .featured-item .title h4 {
      margin-bottom: 20px;
      font-size: 17px !important;
      /* letter-spacing: 1px; */
      color: #1b2b40;
      font-weight: bold;
      font-weight: 700;
      }
      .breadcrumb>li+li:before {
      padding: 0 5px;
      color: #d8dde2;
      content: "/\00a0";
      }
      .page-content {
      padding: 0px; 
      }
      .career-form .form-control {
           margin-bottom: 0px; 
          border: 1px solid #0a0a0a ;
          box-shadow: none;
      }.back_horn{
         background: url(assets/img/career.jpg) !important;
         background-repeat: no-repeat;
    background-size: cover;
    padding: 80px 0 18px 0px !important;
      }
   </style>
   <body>
      <div class="wrapper">
         <?php include('include/header.php'); ?>
         <!--page title start-->
         <section class="page-title">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <h4 class="text-uppercase text-white"> Career</h4>
                     <ol class="breadcrumb">
                        <li><a href="#">Home</a></li>
                        <li class="active">Career</li>
                     </ol>
                  </div>
               </div>
            </div>
         </section>
         <!--page title end-->
         <!--body content start-->

         <section class="fea_sec" id="section1">
            <div class="fea_img">
               <img class="alignnone size-full wp-image-10335" src="assets/img/career.jpg" width="1200" height="720" alt="web design and development services">
            </div>
            <div class="fea_txt" style="    padding: 0 30px;">
               <div id="application-form">
                           <div class="heading-title text-center">
                              <h3 class="text-uppercase  carsue">Apply Now </h3>
                              <span class="text-uppercase text-black">We'll get back to you soon.</span>
                           </div>
                           <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                              <div class="career-form ">
                                 <div class="form-group col-md-6">
                                    <label class="">First Name *</label>
                                    <input type="text" class="form-control" name="fname">
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label class="">Email ID *</label>
                                    <input type="text" class="form-control" name="email">
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label class="">Mobile No. *</label>
                                    <input type="text" class="form-control" name="mobile_no">
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label class="">Technical Skills *</label>
                                    <textarea name="technical_skill" cols="40" rows="1" class="form-control"></textarea>
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label class="">Designation *</label>
                                    <input type="text" class="form-control" name="designation">
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label class="">Experience *</label>
                                    <input type="text" class="form-control" name="experience">
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label class="">Notice Period *</label>
                                    <input type="text" class="form-control" name="notice_period">
                                 </div>
                                 <div class="form-group col-md-6">
                                    <label class="">Browse CV *</label>
                                    <input type="file" data-buttonname="btn-default" name="resume">
                                    <p class="p_style bhhhh"> Allowed files types (.doc, .docx, .pdf)</p>

                                 </div>
                                 <div class="form-group text-center m-top-30 inline-block">
                                    <input type="submit" class="btn btn-medium  btn btn-warning btn-rounded" name="submit" value="Send Application">
                                 </div>
                              </div>
                           </form>
                        </div>
            </div>
         </section>
         <section class="body-content ">
            
            <div class="full-width promo-box backgound2">
               <div class="container">
                  <div class="col-md-6">
                     <div class="" align="center">
                        <span class="light-txt text-uppercase m-top-0 p-b-5" style="font-size:25px;">Are you Looking for Full-Time Work with us? </span><br><br>
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSe7ztxyHqsPeg4I137hL0r7G0Mne-9sgMfTZ9nuhlhmxtfR4Q/viewform" target="_blank" style="font-size:18px;" class="btn btn-medium btn-light-border btn-transparent" > <i class="icon-documents"></i> Let's Start Work</a>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="" align="center">
                        <span class="light-txt text-uppercase m-top-0 p-b-5" style="font-size:25px;">Are you Looking for Part time Work with us? </span><br><br>
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSeZUJRtObzEKnQbyJASuXyZ1HSJ4Efud1UMpqlTBEP4F7E3FQ/viewform?usp=sf_link" target="_blank" style="font-size:18px;" class="btn btn-medium btn-light-border btn-transparent" > <i class="icon-documents"></i> Let's Start Work</a>
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