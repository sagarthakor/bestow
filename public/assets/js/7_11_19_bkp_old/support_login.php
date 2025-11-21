<?php session_start(); include_once('include/config.php');?>
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
                     <h4 class="text-uppercase"> Support Login</h4>
                     <ol class="breadcrumb">
                        <li><a href="#">Home</a>
                     </li>
                     
                     <li class="active">Support Login</li>
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
                     <h3 class="text-uppercase">Sign in to Dwarkesh Business Solutions </h3>
                     <div class="fea_txt">
                        <p class="m-t-15">To better serve you, we encourage our Clients to use support ticket system.If you have purchased any services from Dwarkesh Business Solutions, kindly request for the support ticket account by sending email to <a href="mailto:info@dwarkeshit.com" target="_top">info@dwarkeshit.com</a></p>
                     </div>
                  </div>
               </div>
               </div> <!--end row-->
               <div class="row">
                  <div class="col-md-12">
                     <div class="col-md-3"></div>
                        <div class="col-md-6">
                           <div class="box m-b-10 border_box">
                              <form role="form" action="support_login.php" method="POST">
                                 <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                     <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="emailID">
                                 </div>
                                 <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                     <input type="password" class="form-control" name="password" id="exampleInputPassword1" placeholder="Password">
                                 </div>
                                 <button type="submit" class="btn btn-small btn-rounded btn-dark-solid" name="submit">Login</button>
                              </form>
                           </div>
                           <div class="col-md-3"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <!--body content end-->
         <?php include('include/footer.php'); ?>
      </div>
      <?php include('include/footerscript.php'); ?>
   </body>
</html>
<?php
if(isset($_POST['submit']))
{
    $emailid = $_POST['emailID'];
    $password = $_POST['password'];
    
    $sql = "SELECT customerID,fullName FROM registration WHERE emailID = '$emailid' and password = '$password'";
    
    if($emailid == null && $password == null)
        {
           echo '<script language="javascript">';
           echo "alert('Please! Enter Right Email and Password');document.location='support_login.php'";
           echo '</script>';
        }
       else
        {
            $result = mysqli_query($conn,$sql);
            $cnt = mysqli_num_rows($result);

                if($cnt > 0)
                {
                    while($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
                    {
                      $name = $row['fullName'];
                      $id = $row['customerID'];
                    }
                      $_SESSION['fullName'] = $name;
                      $_SESSION['customerID'] = $id;
                      if($_SESSION['fullName'] && $_SESSION['customerID'])
                     {
                        echo "<script language='javascript'>;
                              window.location='support_form.php';
                             </script>";
                     }
                }
                else
                {
                    echo '<script language="javascript">';
                    echo "alert('You Enter  Wrong EmailID & Password !');document.location='support_login.php';";
                    echo '</script>';
                }
        }
}
?>