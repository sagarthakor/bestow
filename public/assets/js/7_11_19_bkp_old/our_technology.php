<!DOCTYPE html>
<html lang="en">
   <?php include('include/headerscript.php'); ?>
   <style type="text/css">
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
   </style>
   <body>
      <div class="wrapper">
         <?php include('include/header.php'); ?>
         <!--page title start-->
         <section class="page-title">
            <div class="container">
               <div class="row">
                  <div class="col-md-12">
                     <h4 class="text-uppercase"> OUR TECHNOLOGY</h4>
                     <ol class="breadcrumb">
                        <li><a href="#">Home</a>
                        </li>
                        
                        <li class="active">OUR TECHNOLOGY</li>
                     </ol>
                  </div>
               </div>
            </div>
         </section>
         <!--page title end-->
         <!--body content start-->
         <section class="body-content ">
               <div class="page-content">
                <div class="container">
                    <!--feature border box start-->
                    <div class="row">
                        <div class="heading-title text-center">
                            <h3 class="text-uppercase">&nbsp;</h3>
                          
                        </div>

                        <div class="col-md-6">
                            <div class="featured-item feature-border-box text-left">
                                <div class="icon">
                                    <i class="icon-webweb-design"></i>
                                </div>
                                <div class="title text-uppercase">
                                    <h4>Web Designing Technology</h4>
                                </div>
                                <div class="desc">
                                    <ul class="list-icon star-o">
                                        <li>Bootstrap 4</li>
                                                <li>CSS3/ css5</li>
                                                <li>HTML5</li>
                                                <li>JavaScript</li>
                                                <li>jQuery</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="featured-item feature-border-box text-left">
                                <div class="icon">
                                    <i class="icon-webhtml"></i>
                                </div>
                                <div class="title text-uppercase">
                                    <h4>Web Development Technology</h4>
                                </div>
                                <div class="desc">
                                     <ul class="list-icon star-o">
                                       <li>PHP</li>
                                                   <li>Laravel</li>
                                                   <li>Wordpress</li>
                                                   <li>WooCommerce</li>
                                                   <li>Magento</li>
                                    </ul>
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