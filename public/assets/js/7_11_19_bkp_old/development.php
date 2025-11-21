
<!DOCTYPE html>
<html lang="en">
<?php include('include/headerscript.php'); 
include('acess_admin/include/connection.php'); 
?>

<body>

   
 <div class="wrapper">

        <!--header start-->
         <?php include('include/header.php'); ?>
        <!--header end-->

        <!--page title start-->
    <!--hero section-->

     <!--hero section-->
      
        <!--hero section-->
        <div id="ei-slider" class="ei-slider">
            <ul class="ei-slider-large">
              <?php 
                $sql = "SELECT * FROM innerpage_slider WHERE page_id='1' ORDER BY id DESC";
                                    $getdata = mysqli_query($con,$sql);
                                    if(mysqli_num_rows($getdata)>0){
                                       $i =0;
                                    while ($result = mysqli_fetch_assoc($getdata)){
                                       $i++;
               ?>
                <li><a href="#"></a>
                    <img src="<?php echo user_image_view.'innerpage_slider/'.$result['image']; ?>" alt="<?php echo $result['alt']; ?>" />
                </li>
              <?php }
                } 
                ?>
                
             
            </ul>
            <ul class="ei-slider-thumbs">
                  <?php 
                $sql = "SELECT * FROM innerpage_slider WHERE page_id='1' ORDER BY id DESC";
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

        <section class="page-title_menu mini-title">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <ul class="breadcrumb op-nav ">
                     <li class="active"><a href="#" class="icon-webhouse" style="font-size:20px;"></a></li>
                     <li><a href="#section1">web Development</a></li>
                     
                     <li><a href="#section3">Web Designing</a></li>
                     <li><a href="#section4">Webbase ERP System Solutions</a></li>
                     <li><a href="#section2">Web Maintenance </a></li>
                     <li><a href="#section5">Customize Application Solutions</a></li>
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
                        <h3 class="text-uppercase color-title carsue">Web Development</h3>
                     </div>
                     <!--title-->
                     <div class="row">
                        <div class="col-md-12 p-t-30 ">
                          
                         
                              <div class="product_img">
                                 <img src="assets/img/company_web/2.gif" class="product_img_res" alt="">  
                              </div>
                          
                        </div>
                        <div class="col-md-12">
                           <p >We blend our web designing experience with web development techniques to create stunning websites. We’re passionate about developing websites that not only function superbly but, in addition, help our clients benefit through increased ROIs. We always stay updated with the latest trending web development technologies emerging in the market, and try to give the best combination in terms of web site functionality versus costs. We strive to help our clients succeed in their business.</p>
                           <ul class="list-icon star-o">
                              <li> <b>Web development</b> </li>
                              <li> Turnkey web development solutions </li>
                              <li> Development using latest designing tools </li>
                              <li> Converting your ideas into reality </li>
                              <li> SEO friendly development </li>
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
       
         <!--feature--><!--feature-->
         <div id="section3" class="page-content_tab home-list-pop bg_light_1">
            <div class="container">
               <!--feature border box start-->
               <div class="row">
                  <div class=" inline-block">
                     <!--title-->
                     <div class="heading-title-alt border-short-bottom text-center">
                        <h3 class="text-uppercase color-title carsue">web Designing</h3>
                     </div> 
                     <div class="row">
                        <div class="col-md-12">
                          
                              <div class="product_img">
                                 <img src="assets/img/company_web/bg3.gif" class="product_img_res" alt="">  
                              </div>
                          
                               
                          
                           
                          
                        </div>
                        <div class="col-md-12 p-t-30">
                          <p>Deltaweb Solution is a premier web design company offering solutions for all types of websites covering segments such as businesses, start-ups, e-commerce, and entrepreneurships. </p>
                           <p> We value our clients. We believe user experience to be a very important factor while designing websites. Our team of innovative web designers can create a visually stunning website that can excite end-users to engage with your business process.</p>
                           <p>
                              Our aim is to provide your business with a professional, unique, and trendy look that can help you stand out from your competitors. We design websites which can engage with your unique audience, help to drive speedy growth, and create brand awareness.
                           </p>
                           <p>
                              We are experienced in designing enticing layouts using a combination of content, images, fonts, and colours that can make your business grow.
                           </p>
                            <ul class="list-icon star-o">
                               <li> Results-based web design services </li>
                               <li> Using latest trending technologies to create innovative designs  </li>
                               <li> Results-based web design services </li>
                            </ul>
                        </div>
                     </div>  
                        
                  </div>
                  <!--feature border box end-->
               </div>
            </div>
            <!--feature-->
            <!--feature--><!--feature-->
            <div id="section4" class="page-content_tab  home-list-pop">
               <div class="container">
                  <!--feature border box start-->
                  <div class="row">
                     <div class=" inline-block">
                        <!--title-->
                        <div class="heading-title-alt border-short-bottom text-center">
                           <h3 class="text-uppercase color-title carsue">Webbase ERP System Solutions</h3>
                        </div>
                        
                         <div class="col-md-12">
                           
                               <div class="product_img">
                                 <img src="assets/img/dwarkesh/webdevelopment/4.jpg" class="product_img_res" alt="">  
                              </div>
                          
                           
                          
                        </div>
                        <div class="col-md-12">
                           <p>The web-based GST compliant and secure ERP software is enabled with advanced features that help businesses attain process improvement. It automates all crucial processes functioning in an establishment including financial accounting, sales and marketing, human resource, and many more. This automation significantly simplifies the operations and enables the management to invest time in focusing on greater market opportunities and challenges.</p>
                           <p> ERP system is designed to integrate customer relationship management (CRM) and human capital management (HCM) modules to create a smooth information flow, better visibility, and control over all critical business operations. This enables organizations to replace multiple complex and isolated systems with a single platform application to connect every aspect of a business.</p>
                        </div>
                        <!--title-->
                     </div>
                  </div>
                  <!--feature border box end-->
               </div>
            </div>

              <div id="section2" class="page-content_tab bg_light_1 home-list-pop">
            <div class="container">
               <!--feature border box start-->
               <div class="row">
                  <div class=" inline-block">
                     <!--title-->
                     <div class="heading-title-alt border-short-bottom text-center">
                        <h3 class="text-uppercase color-title carsue">Web Maintenance</h3>
                     </div>
                     <div class="col-md-12">
                           
                               <div class="product_img">
                                 <img src="assets/img/dwarkesh/webdevelopment/2.jpg" class="product_img_res" alt="">  
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
            <!--feature-->
            <!--feature--><!--feature-->
            <div id="section5" class="page-content_tab home-list-pop">
               <div class="container">
                  <!--feature border box start-->
                  <div class="row">
                     <div class=" inline-block">
                        <!--title-->
                        <div class="heading-title-alt border-short-bottom text-center">
                           <h3 class="text-uppercase color-title carsue">Customize Application Solutions</h3>
                           </div>
                        
                        <div class="col-md-12">
                           
                               <div class="product_img">
                                 <img src="assets/img/dwarkesh/webdevelopment/5.jpg" class="product_img_res" alt="">  
                              </div>
                          
                           
                          
                        </div>
                        <div class="col-md-12">
                           <p>
                              We design highly versatile iPhone & Android apps for enterprises, small to medium size companies, and start-ups. We have a passion for taking ideas and converting them into robust apps. We focus upon branding, and strive to design apps that complement our client's brand identity, and, at the same time not compromise upon the functionality aspects of the app. We are passionate, innovative, and have a flair for designing stunning apps.
                           </p>
                           <ul class="list-icon star-o p-l-0">
                              <li>Requirement gathering </li>
                              <li>Business analysis </li>
                              <li>UI/UX design process  </li>
                              <li>Mobile application development - native and cross platform development  </li>
                              <li>App delivery - publishing on the App Store, Windows Store, or Google Play   </li>
                           </ul>
                        </div>
                        <!--title-->
                     </div>
                  </div>
                  <!--feature border box end-->
               </div>
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
