    
@extends('Home.layout.main')
    <!--====== HEADER PART ENDS ======-->

    <!--====== HERO PART START ======-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

    @section('main-container')
  

    <section class="hero-area bg_cover" style="background-image: url(public/assets/images/hero-bg.jpg);">
        <div class="container">

            <div class="hero-content">
                <h1 class="title"><span>Contact Us</span></h1>
                <ul class="nav">
                    <!-- <li><a class="main-btn" href="#">Learn more <i class="fas fa-angle-right"></i></a></li> -->
                    <!-- <li><a class="main-btn main-btn-2" href="#">Learn more <i
                                        class="fas fa-angle-right"></i></a></li> -->
                </ul>
            </div>
        </div>
    </section>

    <section class="contact-form-section overflow-hidden our-products">
        <div class="container">
            <div class="row">
            <div class="col-lg-4 col-md-7 col-sm-12">

            <div class="contact-form-margin">
                        <!-- <h6 class="autorix-text teams-text aos-init aos-animate get-in" data-aos="flip-up">get in touch</h6> -->
                        <h3 class="contact-us-title">Give Us a Call.</h3>
                        <div class="contact-info-content">
                        <h4>Address</h4>
                        <p>167-169 Great Portland  Street<br/> London, England, <br/>W1W5PF</p>
</div>
<div class="contact-info-content">
                        <h4>Phone</h4>
                        <p>+447436912201</p>
</div> 
<div class="contact-info-content">
                        <h4>Email</h4>
                        <p>postmaster@walkofweb.com</p>
</div> 

</div>


</div>
                <div class="col-lg-8 col-md-7 col-sm-12">
                    <div class="about-section-form">
                        <!-- <h6 class="autorix-text teams-text aos-init aos-animate get-in" data-aos="flip-up">get in touch</h6> -->
                        <h2 class="contact-us-title">Send us a message</h2>
                    <form id="form_id" method="post" action="javascript:void(0);">
                      <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-12">  
                            <div class="form-group contact-form-margin">
                                <input type="text" class="form-control input-text" id="wow_name" name="wow_name" placeholder="Name" >
                                 <span class="err" id="err_wow_name"></span>
                            </div>
                            
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group contact-form-margin">
                                <input type="text" class="form-control input-text" id="wow_phone" name="wow_phone" onkeypress="return onlyNumbers(event);" placeholder="Phone"  >
                                 <span class="err" id="err_wow_phone"></span>
                            </div>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">                         
                            <div class="form-group contact-form-margin">
                                <input type="text" class="form-control input-text" id="wow_email" name="wow_email" placeholder="Email"  >
                                <span class="err" id="err_wow_email"></span>
                            </div> 
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group contact-form-margin">
                                <input type="text" class="form-control input-text" id="wow_subject" name="wow_subject" placeholder="Subject"  >
                                <span class="err" id="err_wow_subject"></span>
                            </div> 
                        </div>
                       
                      </div>
                    <div class="form-group contact-form-margin-text-area">
                        <textarea name="wow_message" id="wow_message" rows="4" class="form-control input-text1" placeholder="Message"></textarea>
                        <span class="err" id="err_wow_message"></span>
                    </div>
                    <a class="main-btn" href="javascript:void(0);" onclick="contactUs()">Submit<i class="fas fa-angle-right"></i></a>
                    <span class="succ" id="succ_form_message"></span>
                    <span class="err" id="err_form_message"></span>
                    </form>  
                </div>
                </div>
            </div>               
        </div>    
    </section>

    @endsection