<!DOCTYPE html>
<html lang="en">
<?php include( 'include/headerscript.php'); include( 'acess_admin/include/connection.php'); ?>
<style>
    .featured-item .desc {
       color: black;
    font-size: 16px;
    }.featured-item .desc a {
        color: black;
        font-size: 16px;
    }
    .featured-item .title h4 {
        margin-bottom: 20px;
        color: #eb1d27;
            padding-top: 20px;
        font-size: 15px !important;
        letter-spacing: 1px;
        font-weight: normal;
    }
    .featured-item .icon i {
        font-size: 36px;
        color: #eb1d27;
    }
    .breadcrumb>li+li:before {
          padding: 0 5px;
          color: #d8dde2;
          content: "/\00a0";
          }
          .bg_flow {
        background: #e2e2e2;
        padding: 25px 0;
        margin-bottom: 30px;
        border-radius: 10%;
        min-height: 300px;
    }
    .featured-item .icon i {
        font-size: 54px;
        color: black;
    }
    .contact-comments label {
 
    float: left;
 
}
</style>

<body>
    <div class="wrapper">
        <!--header start-->
        <?php include( 'include/header.php'); ?>
        <!--header end-->
       
       

            
            
              <div class="page-content tab-parallax-alt" id="p-contact" style="background: url(assets/img/asdf.jpeg) !important;">
               <div class="container opacity">
                  <div class="row p-30">
                     <div class="col-md-12">
                        <!--tabs square start-->
                        <section class="square-tabs icon-tabs light text-center " style="    background: #000000bd !important;">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="heading-title text-center m-b-10">
                                        <h3 class="text-uppercase text-white carsue ">We love to hear from you, Get a Quote !</h3>
                                        <span class="text-uppercase text-white">We'll get back to you soon.</span>
                                    </div>
                                    <form action="inquiry_action.php" method="post" class="contact-comments m-top-50">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label for="form-field-name">Name *</label>
                                                <input type="text" name="name" id="form-field-name" class="form-control" maxlength="100" required="" data-error="You must enter name">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="form-field-email">Email *</label>
                                                <input type="email" name="email" id="form-field-email" class="form-control" maxlength="100" required="" data-error="Invalid email address!">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="form-field-phone">Phone</label>
                                                <input type="text" name="phone" id="form-field-phone" class="form-control" maxlength="100">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="form-field-subject">Subject</label>
                                                <input type="text" name="subject" id="form-field-subject" class="form-control" maxlength="100">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="form-field-comments">Brief Message</label>
                                                <textarea name="comments" id="form-field-comments" class="cmnt-text form-control" rows="6" maxlength="400"></textarea>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <button type="submit" name="submit" class="btn btn-small btn-info pull-right">Send Message</button>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="FORM_ALT">
                                    </form>
                                </div>
                            </div>
                        </section>
                        <!--tabs square end-->
                     </div>
                  </div>
               </div>
            </div>
        <!--body content start-->
        <section class="body-content">
            <div class="page-content p-t-50 p-b-0">
                <div class="container ">
                    <div class="row">

                        <div class="col-md-12">



                        <div class="heading-title-alt border-short-bottom text-center ">
                            <h3 class="text-uppercase">Reach Us...</h3>
                        </div>
                        <div class="col-md-3 p-l-5 p-r-5">
                            <div class="featured-item text-center bg_flow">
                                <div class="icon"> <i class="icon-map"></i>
                                </div>
                                <div class="title text-uppercase">
                                    <h4>location</h4>
                                </div>
                                <div class="desc">Any time. We are open 24/7
                                    <br>
                                    <br>SF 207-208, Satva Avenue
                                    <br>Opp Axis Bank, Sama Savli Rd,
                                    <br>Vadodara, Gujarat, 390005</div>
                            </div>
                        </div>
                        <div class="col-md-3 p-l-5 p-r-5">
                            <div class="featured-item text-center bg_flow">
                                <div class="icon"> <i class="icon-mobile"></i>
                                </div>
                                <div class="title text-uppercase">
                                    <h4>call us</h4>
                                </div>
                                <div class="desc"> <a href="tel:+91-95862 40012" >+91 958-624-0012</a>
                                    <br> <a href="tel:+91-966-222-2272" >+91-966-222-2272</a>
                                    <br> <a href="tel:+91-942-981-2012" >+91-942-981-2012</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 p-l-5 p-r-5">
                            <div class="featured-item text-center bg_flow">
                                <div class="icon"> <i class="icon-envelope"></i>
                                </div>
                                <div class="title text-uppercase">
                                    <h4>mail us</h4>
                                </div>
                                <div class="desc" > <a href="mailto:info@dwarkeshit.com "  target="_top">info@dwarkeshit.com </a>  <a href="mailto:sales@dwarkeshit.com "  target="_top">sales@dwarkeshit.com </a> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 p-l-5 p-r-5">
                            <div class="featured-item text-center bg_flow">
                                <div class="icon">
                                    <i class="icon-layers"></i>
                                </div>
                                <div class="title text-uppercase">
                                    <h4>Career</h4>
                                </div>
                                <div class="desc">
                                   Send your resume 
                                    <a href="mailto:hr@dwarkeshit.com"  target="_top">hr@dwarkeshit.com</a> 
                                </div>
                            </div>
                        </div>
                      </div>

                       

                    </div>
                </div>

                <div class="row">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3690.179834394706!2d73.19921561534635!3d22.346837546850164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395fcf4ae64d42ff%3A0x6ddef04e4ba2a5f7!2sDwarkesh+IT!5e0!3m2!1sen!2sin!4v1561036629127!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
            </div>
    </div>
    </section>
    <?php include( 'include/footer.php'); ?>
    </div>
    <?php include( 'include/footerscript.php'); ?>
    <?php if($_GET[ "success"]){ ?>
    <script>
        toastr.info("Thank you for your Enquiry..!! Our team will look into it and will get back to you withing 24 working hours.!");
    </script>
    <?php }?>

        <div id="whatsapp" style="text-transform: uppercase;"><a href="https://api.whatsapp.com/send?phone=919586240012&text=Welcome%20To%20Dwarkesh%20Business%20Solution" target="_blank">
      <img src="assets/img/logo/whatsapp.png" style="height:36px;width:36px;"></a></div>
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5cf67173b534676f32ad4db5/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>

</body>

</html>