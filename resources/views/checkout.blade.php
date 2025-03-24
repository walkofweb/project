<!doctype html>
<html lang="en">

<head>
    <style>
        .hide{
            display:block;
        }
        </style>

    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--====== Title ======-->
    <title>walkofweb | walking with stars</title>

    <!--====== Favicon Icon ======-->
   
    <link rel="shortcut icon" href="{{ asset('public/assets/images/favicon-32x32.png')}}" type="image/png">

    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="{{ asset('public/assets/css/bootstrap.min.css')}}">

    <!--====== Fontawesome css ======-->
    <link rel="stylesheet" href="{{ asset('public/assets/css/font-awesome.min.css')}}">

    <!--====== Magnific Popup css ======-->
    <link rel="stylesheet" href="{{ asset('public/assets/css/magnific-popup.css')}}">

    <!--====== Slick css ======-->
    <link rel="stylesheet" href="{{ asset('public/assets/css/slick.css')}}">

    <!--====== Default css ======-->
    <link rel="stylesheet" href="{{ asset('public/assets/css/default.css')}}">

    <!--====== Style css ======-->
    <link rel="stylesheet" href="{{ asset('public/assets/css/style.css')}}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
        <script src="https://js.stripe.com/v3/"></script>

</head>

<body style="background-color:black;">

    <!--====== OFFCANVAS MENU PART START ======-->

    <div class="off_canvars_overlay">

    </div>
    <div class="offcanvas_menu">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="offcanvas_menu_wrapper">
                        <div class="canvas_close">
                            <a href="javascript:void(0)"><i class="fal fa-times"></i></a>
                        </div>
                        <!-- <div class="offcanvas-social">
                            <ul class="text-center">
                                <li><a href="$"><i class="fab fa-facebook-square"></i></a></li>
                                <li><a href="$"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="$"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="$"><i class="fab fa-dribbble"></i></a></li>
                            </ul>
                        </div> -->
                        <div id="menu" class="text-left ">
                            <ul class="offcanvas_main_menu">
                                <li class="menu-item-has-children active">
                                    <a class="page-scroll" href="https://www.walkofweb.net#home">Home</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a class="page-scroll" href="https://www.walkofweb.net#intro">About Us</a>
                                </li>
                                <li class="menu-item-has-children ">
                                    <a class="page-scroll" href="https://www.walkofweb.net#features">Features</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link page-scroll" href="https://www.walkofweb.net/contactUs" target="_blank">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                        <div class="offcanvas_footer">
                            <!-- <span><a href="mailto:layerdrop@gmail.com"><i class="fa fa-envelope-o"></i>
                                    gfx.partner@gmail.com</a></span> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====== OFFCANVAS MENU PART ENDS ======-->

    <!--====== HEADER PART START ======-->

    <header id="home" class="header-area header-v1-area">
        <div class="header-nav">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="navigation">
                            <nav class="navbar navbar-expand-lg navbar-light ">
                                <a class="navbar-brand" href="https://www.walkofweb.net"><img src="{{ asset('public/assets/images/logo.png')}}" alt=""></a>
                                <!-- logo -->
                                <span class="side-menu__toggler canvas_open"><i class="fa fa-bars"></i></span>
                                <!-- /.side-menu__toggler -->

                                <div class="collapse navbar-collapse sub-menu-bar main-nav__main-navigation"
                                    id="navbarSupportedContent">
                                    <ul class="navbar-nav ml-auto main-nav__navigation-box">
                                        <li class="nav-item active">
                                            <a class="nav-link page-scroll" href="https://www.walkofweb.net">Home</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link page-scroll" href="https://www.walkofweb.net#intro">About Us</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link page-scroll" href="https://www.walkofweb.net#features">Features</a>
                                        </li>
                                        <li class="nav-item">
                                    <a class="nav-link page-scroll" href="https://www.walkofweb.net/contactUs" target="_blank">Contact Us</a>
                                </li>
                                    </ul>
                                </div> <!-- navbar collapse -->
                            </nav>
                        </div> <!-- navigation -->
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!--====== HEADER PART ENDS ======-->

    <!--====== HERO PART START ======-->

    <section class="hero-area bg_cover" style="background-image: url(assets/images/hero-bg.jpg);">
        <div class="container">
       
            <div class="hero-content">
                <h1 class="title"><span>Payment Details</span></h1>
                <ul class="nav">
                    <!-- <li><a class="main-btn" href="#">Learn more <i class="fas fa-angle-right"></i></a></li> -->
                    <!-- <li><a class="main-btn main-btn-2" href="#">Learn more <i
                                        class="fas fa-angle-right"></i></a></li> -->
                </ul>
            </div>
        </div>
    </section>

    <div class="container">
    
    
    <div class="alert alert-success text-center">

       

   
        <form 

role="form" 

action="{{ route('stripe.postpayment') }}" 

method="post" 

class="require-validation"

data-cc-on-file="false"

data-stripe-publishable-key="pk_test_51IUxQhHCUbph94h9lQGkPs5a7V3iDHDlJy2GBUvzXKcqVC1kS4Vc6R87zGWynEMHGaB0FklHROSv5bsrd1HZS54T00Fkj6dSdx"

id="payment-form">

@csrf

<input type="hidden" id="userpackage_id" name="userpackage_id" value="{{$userpackage->id}}">
<input type="hidden" id="userpackage_price" name="userpackage_price" value="{{$userpackage->price}}">
 
<div class='form-row row'>

<div class='col-xs-12 col-md-5 form-group cvc required'>
 
    <label class='control-label'>Name on Card</label> <input

        class='form-control' size='4' type='text' id="card_name" name="card_name">

</div>

<div class='col-xs-12 col-md-5 form-group cvc required'>

    <label class='control-label'>Card Number</label> <input

        autocomplete='off' class='form-control card-number' size='20'

        type='text' id="card_number" name="card_number">

</div>

</div>







<div class='form-row row'>

<div class='col-xs-12 col-md-4 form-group cvc required'>

    <label class='control-label'>CVC</label> <input autocomplete='off'

        class='form-control card-cvc' placeholder='ex. 311' size='4'

        type='text' id="card_cvc" name="card_cvc">

</div>

<div class='col-xs-12 col-md-4 form-group expiration required'>

    <label class='control-label'>Expiration Month</label> <input

        class='form-control card-expiry-month' placeholder='MM' size='2'

        type='text' id="card_month" name="card_month">

</div>

<div class='col-xs-12 col-md-4 form-group expiration required'>

    <label class='control-label'>Expiration Year</label> <input

        class='form-control card-expiry-year' placeholder='YYYY' size='4'

        type='text' id="card_year" name="card_year">

</div>

</div>



<div class='form-row row' id="payment_error"  >

<div class='col-md-12 error form-group hide' >

    <div class='alert-danger alert'>Please correct the errors and try

        again.</div>

</div>

</div>



<div class="row">

<div class="col-xs-12">

    <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now </button>

</div>

</div>



</form>



    </div>

    </div>

    <!--====== HERO PART ENDS ======-->



    <!--====== FOOTER PART START ======-->

    <section class="footer-area bg_cover" style="background-image: url(public/assets/images/hero-bg.jpg);">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-about mt-30">
                        <a href="https://www.walkofweb.in"><img src="{{ asset('public/assets/images/logo.png')}}" alt=""></a>
                        <p>Walk of Web is an influencer discovery and impact measurement platform to help businesses and
                            users identify the top influencers in any niche and connect with them easily.</p>
                        <!-- <ul>
                            <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                            <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                        </ul> -->
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-list mt-40">
                        <!-- <h5 class="title">Explore</h5> -->
                        <ul>
                            <li><a href="https://www.walkofweb.net/privacyPolicy" , target="_blank">Privacy Policy</a>
                            </li>
                            <li><a href="https://www.walkofweb.net/termCondition" , target="_blank">Terms &
                                    Conditions</a></li>
                            <li><a href="#">About Us</a></li>
                            <li><a href="https://www.walkofweb.net/contactUs">Contact</a></li>
                            <!-- <li><a href="#">How It Works</a></li> -->
                        </ul>
                    </div>
                </div>
                <!-- <div class="col-lg-3 col-md-6">
                    <div class="footer-list mt-40 pl-55">
                        <h5 class="title"></h5>
                        <ul>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Support</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div> -->
                <!-- <div class="col-lg-3 col-md-6">
                    <div class="footer-list footer-newsleatter mt-40">
                        <h5 class="title">Sign up for beta version</h5>
                        <form action="#">
                            <div class="input-box">
                                <input type="text" placeholder="Email address">
                                <button><i class="fas fa-arrow-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div> -->
                <div class="col-lg-12">
                    <!-- <span class="copyright"> © GFXPARTNER</span> -->
                </div>
            </div>
        </div>
    </section>

    <!--====== FOOTER PART ENDS ======-->

    <!--====== BACK TO TOP START ======-->

    <a class="back-to-top">
        <i class="fal fa-angle-up"></i>
    </a>

    <!--====== BACK TO TOP ENDS ======-->







    <!--====== jquery js ======-->
    <script src="{{ asset('public/assets/js/vendor/modernizr-3.6.0.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/vendor/jquery-1.12.4.min.js')}}"></script>

    <!--====== Bootstrap js ======-->
    <script src="{{ asset('public/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/popper.min.js')}}"></script>

    <!--====== scrolling nav js ======-->
    <script src="{{ asset('public/assets/js/jquery.easing.min.js')}}"></script>
    <script src="{{ asset('public/assets/js/scrolling-nav.js')}}"></script>

    <!--====== Slick js ======-->
    <script src="{{ asset('public/assets/js/slick.min.js')}}"></script>

    <!--====== Magnific Popup js ======-->
    <script src="{{ asset('public/assets/js/jquery.magnific-popup.min.js')}}"></script>

    <!--====== Main js ======-->
    <script src="{{ asset('public/assets/js/main.js')}}"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    

<script type="text/javascript">

  

$(function() {

  

    /*------------------------------------------

    --------------------------------------------

    Stripe Payment Code

    --------------------------------------------

    --------------------------------------------*/

    

    var $form = $(".require-validation");

     

    $('form.require-validation').bind('submit', function(e) {

        var $form = $(".require-validation"),

        inputSelector = ['input[type=email]', 'input[type=password]',

                         'input[type=text]', 'input[type=file]',

                         'textarea'].join(', '),

        $inputs = $form.find('.required').find(inputSelector),

        $errorMessage = $form.find('div.error'),

        valid = true;

        $errorMessage.addClass('hide');

    

        $('.has-error').removeClass('has-error');

        $inputs.each(function(i, el) {

          var $input = $(el);

          if ($input.val() === '') {

            $input.parent().addClass('has-error');

            $errorMessage.removeClass('hide');

            e.preventDefault();

          }

        });

     

        if (!$form.data('cc-on-file')) {

          e.preventDefault();

          Stripe.setPublishableKey($form.data('stripe-publishable-key'));

          Stripe.createToken({

            number: $('.card-number').val(),

            cvc: $('.card-cvc').val(),

            exp_month: $('.card-expiry-month').val(),

            exp_year: $('.card-expiry-year').val()

          }, stripeResponseHandler);

        }

    

    });

      

    /*------------------------------------------

    --------------------------------------------

    Stripe Response Handler

    --------------------------------------------

    --------------------------------------------*/

    function stripeResponseHandler(status, response) {

        if (response.error) {

            $('.error')

                .removeClass('hide')

                .find('.alert')

                .text(response.error.message);

        } else {

            /* token contains id, last4, and card type */

            var token = response['id'];

                 

            $form.find('input[type=text]').empty();

            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");

            $form.get(0).submit();

        }

    }

     

});

</script>

</body>

</html>