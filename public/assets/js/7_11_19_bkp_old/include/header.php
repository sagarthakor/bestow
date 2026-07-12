  <?php 
$name=explode(".",basename($_SERVER['PHP_SELF']));
?>
 
   <!--header start-->
         <header class="l-header <?php if($name[0]=="index"){ ?>l-header_overlay <?php } ?>" style="border-top: 2px solid #ff416c;">

            <div class="l-navbar l-navbar_compact l-navbar_t-light js-navbar-sticky">
               <div class="row">
                  <div class="col-md-12 col-xs-12 col-sm-12">
                     <!-- <div class="col-md-1 col-xs-4 col-sm-4  m-l-0 p-l-0"> -->
                        <!--logo start-->
                        <a href="index.php" class="logo-brand">
                        <img class="retina" src="assets/img/logo/logo.png" style="" alt="dwarkesh logo">
                        </a>
                        <!--logo end-->
                    <!--  </div>
                       <div class="col-md-11 col-xs-8 col-sm-8 ">
                        <div class="row"> -->
                           <nav class="menuzord js-primary-navigation" role="navigation" aria-label="Primary Navigation">
                              <!--mega menu start-->
                              <ul class="menuzord-menu menuzord-right c-nav_s-standard">
                                 <li class="active"><a href="index.php"> Home</a></li>
                                 <li>
                                    <a href="javascript:void(0)">What we do</a>
                                    <div class="megamenu" >
                                       <div class="megamenu-row">
                                          <div class="col3">
                                             <a class="title_menu">Custom Development</a>
                                             <ul class="list-unstyled">
                                                <li><a href="services.php"><i class="icon_web_iconMobile--Application-Development"></i> Mobile & Application Development</a></li>
                                               <!--  <li><a href="services.php">Custom Wordpress Development </a></li>
                                                <li><a href="services.php">Custom Laravel Development</a></li> -->
                                                <li><a href="services.php"><i class="icon_web_iconResponsive-Design--Development"></i>Responsive Design & Development</a></li>
                                                <li><a href="services.php"><i class="icon_web_iconE-commerce"></i> E-commerce</a></li>
                                                <li><a href="services.php"><i class="icon_web_iconEnterprise-Resource-Planning"></i> ERP (Enterprise Resource Planning)</a></li>
                                                <li><a href="services.php"><i class="icon_web_iconCustomer-Relationship-Management"></i> CRM (Customer Relationship Management)</a></li>
                                                </li>
                                             </ul>
                                          </div>
                                          <div class="col3">
                                             <a class="title_menu">Branding & Design </a>
                                             <ul class="list-unstyled">
                                                <li><a href="services.php"><i class="icon_web_iconIdentity--Brand-Design"></i>  Identity & Brand Design</a></li>
                                                <li><a href="services.php"><i class="icon_web_iconMarketing-Collateral-Design"></i> Marketing Collateral Design</a></li>
                                                <li><a href="services.php"><i class="icon_web_iconVideo-Production"></i> Video Production</a></li>
                                              
                                                <li><a href="services.php"><i class="icon_web_iconProduct-Packaging"></i> Product Packaging</a>
                                                
                                             </ul>
                                          </div>
                                           <div class="col3">
                                              <a class="title_menu">Digital Marketing</a>
                                                <ul class="list-unstyled">
                                                   <li><a href="services.php"><i class="icon_web_iconAnalytics--Intelligence"></i> Digital Advertising</a></li>
                                                   <li><a href="services.php"><i class="icon_web_iconSearch-Engine-Optimization"></i> Search Engine Optimization (SEO)</a></li>
                                                   <li><a href="services.php"><i class="icon_web_iconAnalytics--Intelligence"></i> Analytics & Intelligence</a></li>
                                                   <li><a href="services.php"><i class="icon_web_iconContent-Writing--Editing"></i> Content Writing & Editing</a></li>
                                                                                                   
                                                   
                                                </ul>
                                          </div>
                                           <div class="col3">
                                            <a class="title_menu">Support & Hosting</a>
                                                <ul class="list-unstyled">
                                                   <li><a href="services.php"><i class="icon_web_iconWebsite-Support--Maintenance"></i> Website Support & Maintenance</a></li>
                                                   <li><a href="services.php"><i class="icon_web_iconwordpress-logo"></i> Managed Wordpress Hosting</a></li>
                                                   <li><a href="services.php"><i class="icon_web_iconE-Commerce-Hosting"></i> E-Commerce Hosting</a></li>
                                                   <li><a href="services.php"><i class="icon_web_iconNetworking--Server-Management"></i> Networking & Server Management</a></li>
                                                   <li><a href="services.php"><i class="icon_web_iconSecurity--Survillence"></i> Security & Surveillance</a></li>
                                                   </li>
                                                </ul>
                                          </div>
                                         
                                       </div>
                                      
                                    </div>
                                 </li>
                             <li class="">
                                    <a href="javascript:void(0)">Technology</a>
                                    <div class="megamenu" style="width:600px;">
                                       <div class="megamenu-row">
                                          <div class="col6">
                                              <a class="title_menu">Web Designing</a>
                                             <ul class="list-unstyled">
                                                <li><a href="our_technology.php">Bootstrap 4</a></li>
                                                <li><a href="our_technology.php">CSS3/ css5</a></li>
                                                <li><a href="our_technology.php">HTML5</a></li>
                                                <li><a href="our_technology.php">JavaScript</a></li>
                                                <li><a href="our_technology.php">jQuery</a></li>
                                             </ul>
                                          </div>
                                          <div class="col6">
                                             <a class="title_menu">Web Development</a>
                                                <ul class="list-unstyled">
                                                   <li><a href="our_technology.php">PHP</a></li>
                                                   <li><a href="our_technology.php">Laravel</a></li>
                                                   <li><a href="our_technology.php">Wordpress</a></li>
                                                   <li><a href="our_technology.php">WooCommerce</a></li>
                                                   <li><a href="our_technology.php">Magento</a></li>
                                                </ul>
                                          </div>
                                         
                                       </div>
                                    </div>
                                 </li>
                                 <li><a href="portfolio.php">portfolio</a></li>
                                 
                                 <li><a>About Us</a>
                                   <ul class="dropdown list-unstyled" style="right: auto; display: none;">
                                       <li ><a href="aboutus.php">About Dwarkesh</a></li>
                                      
                                       <li ><a href="career.php">Careers</a></li>
                                   </ul>
                               </li>
                               <!--  <li class="">
                                    <a href="product.php">Our Products</a>
                                    <div class="megamenu" style="width:600px;">
                                       <div class="megamenu-row">
                                          <div class="col6">
                                              <a class="title_menu">Web Designing</a>
                                             <ul class="list-unstyled">
                                                <li><a href="our_technology.php">Bootstrap 4</a></li>
                                                <li><a href="our_technology.php">CSS3/ css5</a></li>
                                                <li><a href="our_technology.php">HTML5</a></li>
                                                <li><a href="our_technology.php">JavaScript</a></li>
                                                <li><a href="our_technology.php">jQuery</a></li>
                                             </ul>
                                          </div>
                                          <div class="col6">
                                             <a class="title_menu">Web Development</a>
                                                <ul class="list-unstyled">
                                                   <li><a href="our_technology.php">PHP</a></li>
                                                   <li><a href="our_technology.php">Laravel</a></li>
                                                   <li><a href="our_technology.php">Wordpress</a></li>
                                                   <li><a href="our_technology.php">WooCommerce</a></li>
                                                   <li><a href="our_technology.php">Magento</a></li>
                                                </ul>
                                          </div>
                                         
                                       </div>
                                    </div>
                                 </li> -->
                                 <li ><a href="#">Resources</a>
                                     <ul class="dropdown list-unstyled" style="right: auto; display: none;">
                                       <li ><a href="clientlist.php">Client List</a></li>
                                       <!-- <li ><a href="testimonial.php">Testimonial</a></li> -->
                                        <!-- <li ><a href="team.php">Team Dwarkesh</a></li> -->
                                       <li ><a href="news.php">Latest Blog</a></li>
                                       <li ><a href="update.php">Update</a></li>
                                       <li ><a href="support_home.php">Support</a></li>
                                       
                                   </ul>
                                 </li>
                                 <li ><a href="contactus.php">Contactus</a></li>
                              </ul>
                              <!--mega menu end-->
                           </nav>
                      <!--   </div>
                     </div> -->
                  </div>
               </div>
            </div>
         </header>
         <!--header end-->