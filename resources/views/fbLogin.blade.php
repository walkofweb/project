<style>
   .btn-facebook {
    color: #fff;
    background-color: #3b5998;
    border-color: rgba(0,0,0,0.2);
}

.btn-social {
    position: relative;
    padding-left: 44px;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-social:hover {
    color: #eee;
}

.btn-social :first-child {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 40px;
    padding: 7px;
    font-size: 1.6em;
    text-align: center;
    border-right: 1px solid rgba(0,0,0,0.2);
}
   </style>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?php echo sitetitle(); ?></title>
      <link rel="stylesheet" type="text/css" href="{{URL::to('public/admin')}}/css/style.css">
      <link rel="stylesheet" type="text/css" href="{{URL::to('public/admin')}}/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
      <link rel="icon" href="{{URL::to('public/admin')}}/images/fav.png?v={{ time() }}" >
      <meta name="csrf-token" content="{{ csrf_token() }}">
   </head>
   <body>
    <section class="lg_login__box"> 
      <div class="container-fluid">
        <div class="row">
        <div class="col-md-7 p-0">          
          <div class="lg_login_img">            
            <img src="{{URL::to('public/admin/')}}/images/login_car.svg" class="img-fluid">
          </div>
        </div>

        <div class="col-md-5 p-5">          
          <div class="account-box">
               <div class="account-logo-box">
                  <h2 class="text-uppercase text-center">
                     <a href="" class="text-success">
                     <span><img src="{{URL::to('/public/admin/')}}/images/logo.png?v1" alt=""></span>
                     </a>
                  </h2>
               </div>
               <div class="main-login-box account-content">
                  <div class="login-bg-box login">
                     <div class="login-box-right" id="dvLogin">
                        <div id="pnl">
                          <div class="lg_login_heading">
                          <h2 class="mb-4">Please sign in to continue.</h2>
                          
                        </div>
                           <div class="panel-body innerAll">
                              <form name="loginform" id="loginform" action="javascript:void(0);" method="post" onsubmit="UserloginValidation()" autocomplete="off">
                                 <div class="form-group form-group_copy">
                                  <label>UserName</label>
                                    <!-- <i class="bi bi-envelope-fill"></i> -->
                                    <input type="text" placeholder="Enter user name" class="form-control" id="txtUserName" onkeyup="removeError()" name="txtUserName" onclick="remove_valid(this.id)" onkeypress="remove_valid(this.id)" value="" autofocus>
                                    <span id="error_txtUserName" class="errorbox"></span>
                                 </div>
                                 <div class="form-group form-group_copy">
                                    <label>Password</label>
                                    <!-- <i class="bi bi-lock-fill"></i> -->
                                     
                                    <input type="password" placeholder="Password" class="form-control" id="txtPassword" onkeyup="removeError()" name="txtPassword" onclick="remove_valid(this.id)" onkeypress="remove_valid(this.id)" value="">
                                    <span id="error_txtPassword" class="errorbox"></span>
                                 </div>
                                 <div class="form-group" style="margin:25px 0px 0px;">
                                    <div class=""><!--== case ===-->
                                       <label class="con">
                                       <span>Remember me?</span>
                                       <input type="checkbox" name="rememberMe" value="1">
                                       <span class="checkmark"></span>
                                       </label>
                                    </div>
                                    <div id="loadingGif2" style="display: none;"><img src="{{URL::to('/public/admin')}}/images/loader.gif"></div>
                                    <div class="btnlogin">
                                       <button class="btn btn-primary" style="margin-top:20px;" id="btnLogin" type="submit" style="background-color: #204d74;">
                                       Sign In
                                       </button>
                                       <div id="error_invalidpass" class="errorbox"></div>
                                    </div>
                                    <div class="container"  >
    <a href="{{ url('/facebook') }}" class="btn btn-primary" style="margin-top:20px; background-color: #6d78de; padding-top: 10px;">
    <i class="fa fa-facebook fa-fw"></i> Sign in with Facebook
    </a>
    <!-- <a href="{{ url('/instagrame') }}" class="btn btn-primary" style="margin-top:20px; background-color: #6d78de; padding-top: 10px;">
    <i class="fa fa-insta fa-fw"></i> Sign in insta
    </a> -->
</div>
                                 </div> 
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
        </div>
      </div>
      </div>
    </section> 

      <script type="text/javascript">
         function UserloginValidation(){   
                 var txtUserName = document.loginform.txtUserName.value;
                 var txtPassword = document.loginform.txtPassword.value;
                 $("#error_txtUserName").html("");
                 $("#error_txtPassword").html("");
                 document.loginform.txtUserName.style.borderColor = "";
                 document.loginform.txtPassword.style.borderColor = "";
                 if (txtUserName == "") {
                     document.loginform.txtUserName.focus() ;    
                     document.loginform.txtUserName.style.borderColor = "red";
                     $("#error_txtUserName").html("User Name is required.");
                     return false;
                 }
                 else if (txtPassword == "") {
                     document.loginform.txtPassword.focus() ;
                     document.loginform.txtPassword.style.borderColor = "red";
                     $("#error_txtPassword").html("Password is required");
                     return false;
                 }
                 else {   
                  UserloginValidation();
                 }
                 
         }
         
         function remove_error(){
         $('.err').html('');
         }
         function removeError(){
         $('.err').html('');
         }
         function remove_valid(id) {
         
                 $('#'+id).css('border-color', '');
                 $('#'+id).siblings('.errorbox').html('');
                 $('#err_msg').html('');
                 $('#error_txtfusername').html('');
           }
         
         function Userloginchk() {
         
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
         
         var baseUrl = "{{ url('/') }}";
         
         $.ajax({
         type: "POST",
         url: baseUrl + '/administrator/do_login',
         data: $('#loginform').serialize(),
         cache: 'FALSE',
         beforeSend: function () {
                $('#loadingGif2').show();
         },
         success: function (html) {
         //return false ;
           $('#loadingGif2').hide();
             
         if (html=='fail')
         {
         $("#error_invalidpass").html("Incorrect username or Password");  
         }
         else{
         
         window.location = baseUrl + '/administrator/dashboard#index';
         
         }
                 }
             });
         }
         
      </script>
      <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
      <script src="{{URL::to('public/public/admin/js')}}/bootstrap.min.js" type="text/javascript"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="{{URL::to('/public/admin/js')}}/custom.js" type="text/javascript"></script>
   </body>
</html>


<!-- <a href="{{ url('/facebook') }}" class="btn btn-facebook btn-user btn-block">
   <i class="fab fa-facebook-f fa-fw"></i>
   Login with Facebook
</a> -->
<a href="{{ url('/facebook') }}" class="btn btn-facebook btn-user btn-block">
   <i class="fab fa-facebook-f fa-fw"></i>
   Login with Facebook
</a>
