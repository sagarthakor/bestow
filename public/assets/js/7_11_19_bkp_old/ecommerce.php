<!DOCTYPE html>
<html lang="en">
   <?php include('include/headerscript.php'); ?>
   <body>
      <div class="wrapper">
      <!--header start-->
      <?php include('include/header.php'); ?>
      <!--header end-->
      <!--page title start-->
      <!--hero section-->
      <!--hero section-->
      <!--hero section-->
         <!--hero section-->
        <div id="ei-slider" class="ei-slider">
            <ul class="ei-slider-large">
               <?php 
                $sql = "SELECT * FROM innerpage_slider WHERE page_id='6' ORDER BY id DESC";
                                    $getdata = mysqli_query($con,$sql);
                                    if(mysqli_num_rows($getdata)>0){
                                       $i =0;
                                    while ($result = mysqli_fetch_assoc($getdata)){
                                       $i++;
               ?>
                <li><a href="#">Slide 1</a>
                    <img src="<?php echo user_image_view.'innerpage_slider/'.$result['image']; ?>" alt="<?php echo $result['alt']; ?>" />
                </li>
              <?php }
                } 
                ?>
               
               
            </ul>
            <ul class="ei-slider-thumbs">
                  <?php 
                $sql = "SELECT * FROM innerpage_slider WHERE page_id='6' ORDER BY id DESC";
                                    $getdata = mysqli_query($con,$sql);
                                    if(mysqli_num_rows($getdata)>0){
                                       $i =0;
                                    while ($result = mysqli_fetch_assoc($getdata)){
                                       $i++;
               ?>
                <li class="ei-slider-element">Current</li>
                <li><a href="#">Slide 1</a>
                    <img src="<?php echo user_image_view.'innerpage_slider/'.$result['image']; ?>" alt="<?php echo $result['alt']; ?>" />
                </li>
                <?php }
                } 
                ?>
            </ul>
            <!-- ei-slider-thumbs -->
        </div>
        <!--hero section-->
      <!--hero section-->
      <section class="page-title_menu mini-title">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <ul class="breadcrumb op-nav ">
                     <li class="active"><a href="#" class="icon-webhouse" style="font-size:20px;"></a></li>
                     <li><a href="#section1">Shopping cart</a></li>
                     <li><a href="#section2">Order Management </a></li>
                     <li><a href="#section3">Payment Gateway</a></li>
                     <li><a href="#section4">Product management</a></li>
                    
                  </ul>
               </div>
            </div>
         </div>
      </section>
      <!--body content start-->
      <section class="body-content" id="set_p_tag">
         <!--feature-->
         <div id="section1" class="page-content_tab home-list-pop" >
            <div class="container">
               <!--feature border box start-->
               <div class="row">
                  <div class=" inline-block">
                     <!--title-->
                     <div class="heading-title-alt border-short-bottom text-center">
                        <h3 class="text-uppercase color-title carsue">Shopping cart</h3>
                     </div>
                     <!--title-->
                     <div class="row">
                        <div class="col-md-12">
                           <div class="col-md-6">
                               <div class="product_img">
                                 <img src="assets/img/innerslider/development/01.jpg" class="product_img_res" alt="">  
                              </div>
                           </div>
                           <div class="col-md-6">
                               <div class="product_img">
                                 <img src="assets/img/innerslider/development/01_1.png" class="product_img_res" alt="">  
                              </div>
                           </div>
                          
                        </div>
                        <div class="col-md-12">
                           <p >We blend our web designing experience with web development techniques to create stunning websites. We’re passionate about developing websites that not only function superbly but, in addition, help our clients benefit through increased ROIs. We always stay updated with the latest trending web development technologies emerging in the market, and try to give the best combination in terms of web site functionality versus costs. We strive to help our clients succeed in their business.</p>
                           <div class="spantitle">DYNAMIC & USER-FRIENDLY WEBSITES</div>
                           <ul class="list-icon star-o p-l-0">
                              <li> Well-conceived and planned web sites are an integral part of every organization’s communication needs. Offering an attractive, intuitive interface with a logical and easy to use navigation layout will make the difference between a happy visitor (and potential client) and a frustrated web surfer at your website.</li>
                              <li> A successful site (especially a corporate web site) begins by carefully planning a rewarding user experience. Deltaweb Solutions pays special attention to the following aspect of your web site.</li>
                              <li> Your website is an integral part of your image, identification and communication strategy. Barodaweb offers a crucial blend of expertise including creative conception, brand sensitivity, and technical and interactive architecture skill and design execution. Combined with our other services, which include strategy, marketing, technology tools and data integration; we provide effective, cost-efficient and powerful interactive solutions for our clients.</li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
               <!--feature border box end-->
            </div>
         </div>
         <!--feature-->
         <!--feature-->
         <div id="section2" class="page-content_tab bg_light_1 home-list-pop">
            <div class="container">
               <!--feature border box start-->
               <div class="row">
                  <div class=" inline-block">
                     <!--title-->
                     <div class="heading-title-alt border-short-bottom text-center">
                        <h3 class="text-uppercase color-title carsue">Order Management</h3>
                     </div>
                     <div class="col-md-12">
                        <div class="col-md-6">
                           <div class="product_img">
                           <img src="assets/img/innerslider/development/02_1.jpg" class="product_img_res" alt="">  
                        </div>
                        </div>
                        <div class="col-md-6">
                           <div class="product_img">
                           <img src="assets/img/innerslider/development/02.jpg" class="product_img_res" alt="">  
                        </div>
                        </div>
                        
                     </div>
                     <div class="col-md-12">
                        <p>Website maintenance is the act of regularly checking your website for issues and mistakes and keeping it updated and relevant. This should be done on a consistent basis in order to keep your website healthy, encourage continued traffic growth, and strengthen your SEO and Google rankings.  </p>
                        <p>Keeping a website well maintained and attractive is important to companies big and small in order to engage and retain customers.</p>
                        <p > It’s easy for businesses, especially startups, to cut corners and let a few tasks slide. Website maintenance can easily become one of those things as it doesn’t always present immediate issues.</p>
                        <p> However, just like your health can fall apart if you go too long without a regular check up, so can the health of your website.  </p>
                     </div>
                     <!--title-->
                  </div>
               </div>
               <!--feature border box end-->
            </div>
         </div>
         <!--feature--><!--feature-->
         <div id="section3" class="page-content_tab home-list-pop">
            <div class="container">
               <!--feature border box start-->
               <div class="row">
                  <div class=" inline-block">
                     <!--title-->
                     <div class="heading-title-alt border-short-bottom text-center">
                        <h3 class="text-uppercase color-title carsue">Payment Gateway</h3>
                     </div>   
                        <div class="col-md-12">
                           <div class="col-md-6">
                              <div class="product_img">
                                 <img src="assets/img/innerslider/development/03.jpg" class="product_img_res" alt="">  
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="product_img">
                                 <img src="assets/img/innerslider/development/03.jpg" class="product_img_res" alt="">  
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <p>Deltaweb Solution is a premier web design company offering solutions for all types of websites covering segments such as businesses, start-ups, e-commerce, and entrepreneurships. </p>
                           <p> We value our clients. We believe user experience to be a very important factor while designing websites. Our team of innovative web designers can create a visually stunning website that can excite end-users to engage with your business process.</p>
                           <p>
                              Our aim is to provide your business with a professional, unique, and trendy look that can help you stand out from your competitors. We design websites which can engage with your unique audience, help to drive speedy growth, and create brand awareness.
                           </p>
                           <p>
                              We are experienced in designing enticing layouts using a combination of content, images, fonts, and colours that can make your business grow.
                           </p>
                        </div>
                        <!--title-->
                        <ul class="list-icon star-o p-l-0">
                           <li> Results-based web design services </li>
                           <li> Using latest trending technologies to create innovative designs  </li>
                           <li> Results-based web design services </li>
                        </ul>
                     </div>
                  </div>
                  <!--feature border box end-->
               </div>
            </div>
            <!--feature-->
         </div> 

          <!--feature--><!--feature-->
         <div id="section4" class="page-content_tab home-list-pop">
            <div class="container">
               <!--feature border box start-->
               <div class="row">
                  <div class=" inline-block">
                     <!--title-->
                     <div class="heading-title-alt border-short-bottom text-center">
                        <h3 class="text-uppercase color-title carsue">Product management</h3>
                     </div>   
                        <div class="col-md-12">
                           <div class="col-md-6">
                              <div class="product_img">
                                 <img src="assets/img/innerslider/development/03.jpg" class="product_img_res" alt="">  
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="product_img">
                                 <img src="assets/img/innerslider/development/03.jpg" class="product_img_res" alt="">  
                              </div>
                           </div>
                        </div>
                        <div class="col-md-12">
                           <p>Deltaweb Solution is a premier web design company offering solutions for all types of websites covering segments such as businesses, start-ups, e-commerce, and entrepreneurships. </p>
                           <p> We value our clients. We believe user experience to be a very important factor while designing websites. Our team of innovative web designers can create a visually stunning website that can excite end-users to engage with your business process.</p>
                           <p>
                              Our aim is to provide your business with a professional, unique, and trendy look that can help you stand out from your competitors. We design websites which can engage with your unique audience, help to drive speedy growth, and create brand awareness.
                           </p>
                           <p>
                              We are experienced in designing enticing layouts using a combination of content, images, fonts, and colours that can make your business grow.
                           </p>
                        </div>
                        <!--title-->
                        <ul class="list-icon star-o p-l-0">
                           <li> Results-based web design services </li>
                           <li> Using latest trending technologies to create innovative designs  </li>
                           <li> Results-based web design services </li>
                        </ul>
                     </div>
                  </div>
                  <!--feature border box end-->
               </div>
            </div>
            <!--feature-->
         </div> 
            <!--feature-->
      </section>
      <!--footer start 1-->
      <?php include('include/footer.php'); ?>
      <!--footer 1 end-->
      </div>
      <?php include('include/footerscript.php'); ?>
   </body>
</html>