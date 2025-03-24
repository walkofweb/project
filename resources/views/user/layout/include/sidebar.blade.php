 <script type="text/javascript">

jQuery(document).ready(function ($) {
 /* menu selection .hash*/ 
   var hash = window.location;
 $('#letsgo_sidebar li a').each(function () {

var toBactive = $(this).attr('href');

if (toBactive == hash) {
    
    //$(this).parents('.sub-menu').addClass('show in');
    $(this).parents('li').addClass('active-li');  

}});


   /* end */

$('#letsgo_sidebar').find('li a').click(function(){
   
   // $('.nav-item').removeClass('active');
   // $('#rSidemenubar').find('li a.active').removeClass('active');
   //  $('ul li .nav-item').removeClass('active');
    
   // $(this).closest('ul').hasClass('sub-menu').addClass('show in');
    if($(this).closest('ul').hasClass('sub-menu')){
     $('li').removeClass('active-li'); 
     $(this).parents('li').addClass('active-li');  
        
   }
     else{
        $('li').removeClass('active-li'); 
      $('.sub-menu').removeClass('show in');
      $(this).parent('li').addClass('active-li');
    
       } });




 /* end menu selection */
  var hash = window.location.hash;
  var res = hash.split("/");
 
        if(res!='')
        {
            $(".dashbordWrapper").css("display", "none");
        }
       
        
         if(res[0]=='#notification_for'){
            notificationFor();
        }    
        
        if(res[0]=='#customer_detail'){
            customerDetail(res[1]);
        }    

        if(res[0]=='#country_list'){
            countryList();
        } 
        
        
  

        if(res[0]=='#packae_list'){
            UserpackageList();
        }  
        
        
        if(res[0]=='#rankType'){
            rankTypeList();
        }    
        
        if(res[0]=='#interest'){
            interestList();
        }   

        if(res[0]=='#user_management'){
            userManagement();
        }  


        if(res[0]=='#sponser'){
            sponserList();
        }            


       
        if(res[0]=='#advertisement_detail'){
            advertisementDetail(res[1]);
        }

        if(res[0]=='#userPoint_detail'){
            userPointDetail(res[1]);
        }
        

        if(res[0]=='#contactSupport'){
            contactSupport();
        }

        if(res[0]=='#notification_detail'){
            notificationDetail(res[1]);
        }

        if(res[0]=='#_=_' ||res[0]==''||res[0]=='#'){
            user_dashboard();
        }

        if(res[0]=='#termCondition'){
            termCondition();
        }

        if(res[0]=='#privacyPolicy'){
            privacyPolicy();
        }

       
        if(res[0]=='#notification'){
            notificationList();
        }
        if(res[0]=='#post_management'){
        
            postManagement();
        }
        
        if(res[0]=='#adsManagement'){
            adsManagement();
        }

        if(res[0]=='#socialManagement'){
            
            socialManagement(); 
        }
        if(res[0]=='#usersocialpoint'){
            userPointList();   
        }  
        if(res[0]=='#subscriptions'){
            subscriptionList();   
        } 
        if(res[0]=='#post_detail'){
            postDetail(res[1]);   
        } 
        if(res[0]=='#social_connect'){
            social_connect();   
        } 
});
</script>
    <div class="header_logo">
    <a class="navbar-brand" href="#">
                <img src="{{URL::to('/public/admin')}}/images/sitebar_icon/logo-new.png?{{time();}}" class="un-clp-logo" alt="">
                 <img src="{{URL::to('/public/admin')}}/images/lesgo_logo-sml.png?{{time();}}" class="clp-logo" alt="">
            </a>
            </div>
    <div class="sidebarWrapper">   
    <ul class="height_navigation" id="letsgo_sidebar">
        <li><a href="{{URL::to('/')}}/user/dashboard"  onclick="user_dashboard()">
        <!-- <i class="ri-dashboard-line"></i> -->
        <img src="<?php echo e(URL::to('/')); ?>/public/admin/images/sitebar_icon/icon1.png" alt="">
        <span class="tooltip_nav">Dashboard</span></a></li>

      
     
        <li>
        <!-- <a href="#" >  -->
        <a href="{{URL::to('/')}}/user/dashboard#social_connect" onclick="social_connect()">
        <!-- <i class="ri-user-settings-line"></i> -->
        <img src="<?php echo e(URL::to('/')); ?>/public/admin/images/sitebar_icon/UsersThree.png" alt="">
           <span class="tooltip_nav">
           Social Connect
    </span></a></li>

    <li>
       
        <a href="{{URL::to('/')}}/user/dashboard#user_management" onclick="userManagement()">
        <!-- <i class="ri-user-settings-line"></i> -->
        <img src="<?php echo e(URL::to('/')); ?>/public/admin/images/sitebar_icon/UsersThree.png" alt="">
           <span class="tooltip_nav">
        Customer Management
    </span></a></li>
   
   


    
      

    <li><a href="{{URL::to('/')}}/user/dashboard#packae_list" onclick="UserpackageList()">
                <i class="ri-arrow-right-s-line"></i>
                <span class="tooltip_nav">Package</span>
                </a></li>
        
   
        <li>
            
        </li>
       
    </ul>
</div>
<script type="text/javascript">
    $("#drop_nav").click(function() { 
    $("#drop_content").delay(4000).toggleClass();
});
$("#social_drop_nav").click(function() {
    $("#drop_content12").delay(4000).toggleClass();
});
$("#master_n").click(function() {
    $("#drop_content_m").delay(4000).toggleClass();
});
$('.cms_nav #drop_nav').click(function(){
    $('.cms_nav #drop_nav').toggleClass('show_submenu');
});
$('.master_nav #master_n').click(function(){
    $('.master_nav #master_n').toggleClass('show_submenu');
});
$('.social_nav #social_drop_nav').click(function(){
    $('.social_nav #social_drop_nav').toggleClass('show_submenu');
});
  

</script>

