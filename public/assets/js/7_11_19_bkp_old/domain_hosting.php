
<!DOCTYPE html>
<html lang="en">
<?php include('include/headerscript.php');
include('acess_admin/include/connection.php');  ?>


<body>

   
 <div class="wrapper">

        <!--header start-->
         <?php include('include/header.php'); ?>
        <!--header end-->

        <!--page title start-->
    <!--hero section-->
      <!--hero section-->
        <div id="ei-slider" class="ei-slider">
            <ul class="ei-slider-large">
              <?php 
                $sql = "SELECT * FROM innerpage_slider WHERE page_id='2' ORDER BY id DESC";
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
                
                <!-- <li>
                    <img src="assets/img/slider/slider_01.jpg" alt="image01" />
                    
                </li>
                <li>
                    <img src="assets/img/slider/slider_03.jpg" alt="image01" />
                  
                </li>
                -->
            </ul>
            <ul class="ei-slider-thumbs">
                  <?php 
                $sql = "SELECT * FROM innerpage_slider WHERE page_id='2' ORDER BY id DESC";
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
                     <li><a href="#section1">Domain Name Registration</a></li>
                     <li><a href="#section2">Web Hosting</a></li>
                     <li><a href="#section3">Window/Linux Hosting</a></li>
                     <li><a href="#section4">Cloud Servers</a></li>
                     
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
                        <h3 class="text-uppercase color-title carsue">Domain Name Registration</h3>
                     </div>
                     <!--title-->
                     <div class="row">
                         <div class="col-md-12">
                           
                               <div class="product_img">
                                 <img src="assets/img/dwarkesh/doamin/1.jpg" class="product_img_res" alt="">  
                              </div>
                          
                           
                          
                        </div>
                        <div class="col-md-12">
                           <p >When you’re trying to come up with a good domain, it’s important to come up with one that can stand the test of time and that accurately represents you and your website or brand.</p>
                           <div class="spantitle">Benifits</div>
                           <ul class="list-icon star-o p-l-0">
                              <li> To do a domain name search, you choose the name to the left of the dot. That’s what is called the second level domain. Then you will choose the domain extension (also known as the top level domain or TLD), that comes after the dot, such as .com.</li>
                              <li> If you already have a business or brand, your domain should match your name. If the .com domain is taken, you can try an alternate TLD, but be sure not to put yourself in another company’s shadow or infringe on their trademark.</li>
                              <li> Make sure your domain is easy to spell and pronounce, and doesn’t contain any strange letter substitutions or omissions that will make it difficult to remember. Hyphens are also usually not a great idea because people forget about them.</li>
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
                        <h3 class="text-uppercase color-title carsue">Web Hosting</h3>
                     </div>
                      <div class="col-md-12">
                           
                               <div class="product_img">
                                 <img src="assets/img/dwarkesh/doamin/3.png" class="product_img_res" alt="">  
                              </div>
                          
                           
                          
                        </div>
                     <div class="col-md-12">
                        <p>Simple websites typically consist of a single web server which runs either a Content Management System (CMS), such as WordPress, an eCommerce application, such as Magento, or a development stack, like LAMP. The software makes it easy to build, update, manage, and serve the content of your website.</p>
                        <p>Simple websites are best for low to medium trafficked sites with multiple authors and more frequent content changes, such as marketing websites, content websites or blogs. They provide a simple starting point for website which might grow in the future. While typically low cost, these sites require IT administration of the web server and are not built to be highly available or scalable beyond a few servers.</p>
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
                        <h3 class="text-uppercase color-title carsue">Window/Linux Hosting</h3>
                     </div>   
                        <div class="col-md-12">
                           
                               <div class="product_img">
                                 <img src="assets/img/dwarkesh/doamin/4.jpg" class="product_img_res" alt="">  
                              </div>
                          
                           
                          
                        </div>
                        <div class="col-md-12">
                           <p>In this article, we’ll discuss the differences between Linux and Windows hosting — specifically the factors, such as stability, security, and cost of ownership, especially important to business users. As we highlight the strengths and weaknesses of these competing platforms, we’ll also showcase the tools available to developers and go over some rather new developments in the hosting business. By the time we’re finished, I hope you’ll have a better idea of which OS to choose for your project as well as which hosting company to choose and which features to look for.</p>
                           <p>
                             Ask any server administrator to identify the biggest difference between Linux and Windows, and the first thing they’ll mention is stability. Linux servers are sometimes considered more secure than Windows servers. They rarely need to be rebooted and most configuration changes can be accomplished without a restart. Windows servers, on the other hand, can get especially unstable when tasked with running multiple database, web, and file servers. When you start adding separate applications and lots of scheduled tasks, the problems tend to get worse. While a significant amount of work has gone into alleviating these issues, it is still a problem with which server administrators wrestle. If you anticipate your solution will be called upon to have near 100% uptime, going with a Linux server will likely be your best bet.
                           </p>
                           <p>
                             It should be said that the learning curve for managing a Linux server is undeniably steeper. If you have the time or background, this won’t be a problem. Those with other responsibilities outside of IT and development might find configuring and managing such an environment a daunting task. Many Windows options can be found through a user interface and the standardization of the software allows a beginner to find many answers to their problems online. If you are looking for simplicity, Windows is the way to go.<p>
                              <p>Ultimately, the biggest question you must ask yourself is, “What type of software will I be running?” Are you going to be running an Exchange server or a Sharepoint site? If so, you’d better go with a Windows server. Do you love being able to install your favorite CMS, such as WordPress or Joomla, through cPanel? For that, you’d be correct to choose Linux. We’ve gone over some of the basic factors that you should consider when deciding between Linux and Windows hosting. Let’s dig into them a little deeper below.
                           </p>
                        </div>
                     </div>
                  </div>
                  <!--feature border box end-->
               </div>
            </div>
            <!--feature-->
            <!--feature--><!--feature-->
            <div id="section4" class="page-content_tab bg_light_1 home-list-pop">
               <div class="container">
                  <!--feature border box start-->
                  <div class="row">
                     <div class=" inline-block">
                        <!--title-->
                        <div class="heading-title-alt border-short-bottom text-center">
                           <h3 class="text-uppercase color-title carsue">Cloud Servers</h3>
                        </div>
                        
                         <div class="col-md-12">
                           
                               <div class="product_img">
                                 <img src="assets/img/dwarkesh/doamin/5.jpg" class="product_img_res" alt="">  
                              </div>
                          
                           
                          
                        </div>
                        <div class="col-md-12">
                           <p>Amazon EC2’s simple web service interface allows you to obtain and configure capacity with minimal friction. It provides you with complete control of your computing resources and lets you run on Amazon’s proven computing environment. Amazon EC2 reduces the time required to obtain and boot new server instances to minutes, allowing you to quickly scale capacity, both up and down, as your computing requirements change. Amazon EC2 changes the economics of computing by allowing you to pay only for capacity that you actually use. Amazon EC2 provides developers the tools to build failure resilient applications and isolate them from common failure scenarios.</p>
                           <p>Amazon Elastic Compute Cloud (Amazon EC2) is a web service that provides secure, resizable compute capacity in the cloud. It is designed to make web-scale cloud computing easier for developers.</p>
                        </div>
                        <!--title-->
                        <div class="spantitle">Benifits</div>
                           <ul class="list-icon star-o p-l-0">
                              <li>ELASTIC WEB-SCALE COMPUTING</li>
                              <li>COMPLETELY CONTROLLED</li>
                              <li>FLEXIBLE CLOUD HOSTING SERVICES</li>
                              <li>INTEGRATED</li>
                              <li>RELIABLE</li>
                              <li>SECURE</li>
                              <li>INEXPENSIVE</li>
                              <li>EASY TO START</li>
                           </ul>
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
