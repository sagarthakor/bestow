
@extends('includes.master')

@section('title')

@section('content')
<div class="container">


         <div class="contact-page">
            <div class="breadcrumb-area">
               <ul>
                  <li><a href="{{url('index')}}">Home</a></li>
                  <li><span>Contact</span></li>
               </ul>
            </div>
            <div class="row">
               <h2 class="page-title">Contact Us</h2>
               <div class="contact-info col-12">
                     <div class="card-body card-body-cascade text-center">
                        <!--Google map-->
                       <div id="map-container-google-9" class="z-depth-1-half map-container-5" >
                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3691.39624563078!2d73.19786991363709!3d22.300849185323276!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395fc58eeda667d7%3A0xbcfaa6a4bc6cd6e5!2sLaxmi%20Sales%20Corporation!5e0!3m2!1sen!2sin!4v1608191455385!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                       </div>
                     </div>
               </div>
            </div>
            <div class="row">
            <div class="contact-info col-12 mt-5">
               <div class="form-row">
                  <div class="col-md-3">
                     <div class="contact text-center">
                        <i class="fas fa-envelope"></i>
                        <h4>Mail Here</h4>
                        <p>info@laxmisales.in</p>
                        <p>panchalpritesh30@gmail.com</p>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="contact text-center">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Visit Here</h4>
                        <p>Laxmi Sales Corporation</p>
                        <p>Vaikunth, Brahmpuri,</p>
                        <p>Opp. Maharashtra Restuarent,</p>
                        <p>Dandia Bazar Vadodara - 390001,</p>
                        <p>Gujarat, India</p>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="contact text-center">
                        <i class="fas fa-phone"></i>
                        <h4>Call Here</h4>
                        <p>0265 - 243 7039</p>
                        <p>+(91)-9377237039</p>
                        <p>+(91)-9374986880</p>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="contact text-center">
                        <i class="fa fa-globe"></i>
                        <h4>Website</h4>
                        <p>www.laxmisales.in</p>
                     </div>
                  </div>
               </div>
            </div>
            </div>
         </div>
      @endsection