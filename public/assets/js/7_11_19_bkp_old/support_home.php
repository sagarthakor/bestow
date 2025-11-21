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
                     <h4 class="text-uppercase"> Support</h4>
                     <ol class="breadcrumb">
                        <li><a href="#">Home</a>
                     </li>
                     
                     <li class="active">Support</li>
                  </ol>
               </div>
            </div>
         </div>
      </section>
      <!--page title end-->
      <!--body content start-->
      <section class="body-content padding_text">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="text-center p-t-30">
                     <h3 class="text-uppercase">Welcome to Support Center </h3>
                     <div class="fea_txt">
                        <p class="m-t-15">In order to streamline support requests and better serve you, we utilize a support ticket system. Every support request is assigned a unique ticket number which you can use to track the progress and responses online. For your reference we provide complete archives and history of all your support requests. A valid email address is required to submit a ticket.</p>
                     </div>
                  </div>
               </div>
               </div> <!--end row-->
               <div class="row">
                  <div class="col-md-12">
                     <div class="col-md-6">
                        <div class="box m-b-10">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="row" align="center">
                                    <div class="category_icon p-b-30" >
                                       <img src="assets/img/ticket.png" alt="" class="category_icon_res">
                                    </div>
                                 </div>
                                 <div class="col-md-12">
                                    <h4 align="center"><b>Open A New Ticket</b></h4>
                                    <p>Please provide as much detail as possible so we can best assist you. To update a previously submitted ticket, please login.</p>
                                 </div>
                              </div>
                              <div class="col-md-12" align="center">
                                 <a href="support_login.php" class="btn btn-small btn-rounded btn-dark-solid  "> Open A New Ticket </a>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="box m-b-10">
                           <div class="row">
                              <div class="col-md-12">
                                 <div class="row" align="center">
                                    <div class="category_icon p-b-30" >
                                    <img src="assets/img/discount.png" alt="" class="category_icon_res">
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <h4 align="center"><b>Check Ticket Status</b></h4>
                                 <p>We provide archives and history of all your current and past support requests complete with responses.</p>
                              </div>
                           </div>
                           <div class="col-md-12" align="center">
                              <a href="check_status.php" class="btn btn-small btn-rounded btn-dark-solid  "> Check Ticket Status </a>
                           </div>
                        </div>
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