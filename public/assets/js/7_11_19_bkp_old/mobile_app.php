<!DOCTYPE html>
<html lang="en">
   <?php include('include/headerscript.php');  ?>
   <style>
      .portfolio-filter li a:hover, .portfolio-filter li.active a {
      color: #fff;
      padding: 10px;
      background: #1c2c42;
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
   </style>
   <body>
      <div class="wrapper">
         <?php include('include/header.php'); ?>
         <!--page title start-->
         <section class="page-title">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <h4 class="text-uppercase text-white">Mobile Application</h4>
                  </div>
               </div>
            </div>
         </section>
         <section class="body-content page-content">
            <div class="container">
               <div class="text-center">
                  <div class="form-group text-center m-top-30 inline-block">
                     <a href="portfolio.php" class="btn btn-small btn-rounded btn-dark-solid"> &laquo; Back </a>
                  </div>
                  <div class="portfolio portfolio-with-title col-3 gutter ">
                     <div class="portfolio-item 1" style="    background: #1c2c42;">
                        <a href="aqua.php" >
                           <div class="thumb">
                              <img src="assets/img/portfolio/Mobile_app/Aqua_Management/front.jpg" alt="Aqua by Dwarkesh">
                           </div>
                        </a>
                        <div class="portfolio-title">
                           <h4>
                              <a href="aqua.php" class="popup-link" title="Aqua by Dwarkesh">Aqua Management</a>
                           </h4>
                           <p>
                              <a href="aqua.php">
                              Mobile App</a>
                           </p>
                        </div>
                     </div>
                     <div class="portfolio-item 1" style="    background: #1c2c42;">
                        <a href="barcode.php" >
                           <div class="thumb">
                              <img src="assets/img/portfolio/Mobile_app/Barcode_App/front.jpg" alt="Barcode by Dwarkesh">
                           </div>
                        </a>
                        <div class="portfolio-title">
                           <h4>
                              <a href="barcode.php" class="popup-link" title="Barcode by Dwarkesh">Barcode App</a>
                           </h4>
                           <p>
                              <a href="barcode.php">
                              Mobile App</a>
                           </p>
                        </div>
                     </div>
                     <div class="portfolio-item 1" style="    background: #1c2c42;">
                        <a href="deal.php" >
                           <div class="thumb">
                              <img src="assets/img/portfolio/Mobile_app/Deal_Much/front.jpg" alt="Deal by Dwarkesh">
                           </div>
                        </a>
                        <div class="portfolio-title">
                           <h4>
                              <a href="deal.php" class="popup-link" title="Deal by Dwarkesh">Deal Much</a>
                           </h4>
                           <p>
                              <a href="deal.php">
                              Mobile App</a>
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <?php include('include/footer.php'); ?>
      </div>
      <?php include('include/footerscript.php'); ?>
   </body>
</html>