<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?php echo sitetitle(); ?></title>
      <link rel="stylesheet" type="text/css" href="{{URL::to('/public/admin')}}/css/style.css">
      <link rel="stylesheet" type="text/css" href="{{URL::to('/public/admin')}}/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
      <link rel="icon" href="{{URL::to('/public/admin')}}/images/fav.png?v={{ time() }}" >
      <meta name="csrf-token" content="{{ csrf_token() }}">
   </head>
   <body>
    <section class="lg_login__box"> 
      <div class="container-fluid">
        <div class="row">
        <div class="col-md-7 p-0">          
          <div class="lg_login_img">            
            <img src="{{URL::to('/public/admin/')}}/images/login_car.svg" class="img-fluid">
          </div>
        </div>

        <div class="col-md-5 p-5">          
          <div class="account-box">
               <div class="account-logo-box">
                  <h2 class="text-uppercase text-center">
                  <span id="errohtmlr_invalidpass"></span>
                  
                     <!-- <a href="" class="text-success">
                     <span><img src="{{URL::to('/public/admin/')}}/images/logo.png?v1" alt=""></span>
                     </a> -->
                  </h2>
               </div>
               <div class="main-login-box account-content">
                  <div class="login-bg-box login">
                     <div class="login-box-right" id="dvLogin">
                        <div id="pnl">
                          <div class="lg_login_heading">
                         
                          <h5 class="mb-4">Please sign Up  as Sponser</h5>
                          <span id="errohtmlr_invalidpass"></span>
                         
                           <div class="panel-body innerAll">
                              <form name="loginform" id="loginform" action="javascript:void(0);" method="post" onsubmit="SignupValidation()" autocomplete="off">
                               
                                 <div class="form-group form-group_copy">
                                  <label>Name</label>
                                    <!-- <i class="bi bi-envelope-fill"></i> -->
                                    <input type="text" placeholder="Enter  Name" class="form-control" id="name" onkeyup="removeError()" name="name" onclick="remove_valid(this.id)" onkeypress="remove_valid(this.id)" value="" autofocus>
                                    <span id="error_name" class="errorbox"></span>
                                 </div>
                                 <div class="form-group form-group_copy">
                                  <label>Email</label>
                                    <!-- <i class="bi bi-envelope-fill"></i> -->
                                    <input type="email" placeholder="Enter  Email" class="form-control" id="email" onkeyup="removeError()" name="email" onclick="remove_valid(this.id)" onkeypress="remove_valid(this.id)" value="" autofocus>
                                    <span id="error_email" class="errorbox"></span>
                                 </div>
                                
                                 <div class="form-group form-group_copy">
                                    <label>Password</label>
                                    <!-- <i class="bi bi-lock-fill"></i> -->
                                     
                                    <input type="password" placeholder="Password" class="form-control" id="password" onkeyup="removeError()" name="password" onclick="remove_valid(this.id)" onkeypress="remove_valid(this.id)" value="">
                                    <span id="error_password" class="errorbox"></span>
                                 </div>
                                 <div class="form-group form-group_copy">
                                    <label>Confirm Password</label>
                                    <!-- <i class="bi bi-lock-fill"></i> -->
                                     
                                    <input type="password" placeholder="Confirm Password" class="form-control" id="confirm_password" onkeyup="removeError()" name="confirm_password" onclick="remove_valid(this.id)" onkeypress="remove_valid(this.id)" value="">
                                    <span id="error_confirm_password" class="errorbox"></span>
                                 </div>
                                 <div class="form-group form-group_copy">
                                  <label>PhoneNumber</label>
                                    <!-- <i class="bi bi-envelope-fill"></i> -->
                                    <input type="text" placeholder="Enter  Phone Number" class="form-control" id="phoneNumber" onkeyup="removeError()" name="phoneNumber" onclick="remove_valid(this.id)" onkeypress="remove_valid(this.id)" value="" autofocus>
                                    <span id="error_phoneNumber" class="errorbox"></span>
                                 </div>
                                 <div class="form-group form-group_copy">
                                  <label>Country</label>
                                    <!-- <i class="bi bi-envelope-fill"></i> -->
                                    <select  class="form-control" id="countryId" onkeyup="removeError()" name="countryId" value="" autofocus>
                                   
                                        <option value="">Select Country<option>
                                          
                                        @foreach ($country as $con)
                                        <option value="{{$con->id}}">{{$con->title?$con->title:''}}<option>
                                        @endforeach
</select>
                                    <span id="error_countryId" class="errorbox"></span>
                                 </div>
                                 <input type="hidden" id="deviceType" name="deviceType"  value="3">
                                 <input type="hidden" id="userType" name="userType"  value="5">
                                                    <div class="btnlogin">
                                       <button class="btn btn-primary" style="margin-top:10px;" id="btnLogin" type="submit" style="background-color: #204d74;">
                                       Submit
                                       </button>
                                       <div id="loadingGif2" style="display: none;"><img src="{{URL::to('/public/admin')}}/images/loader.gif"></div>

                                       <div id="error_invalidpass" class="errorbox"></div>
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
         function SignupValidation(){   
                 var name = document.loginform.name.value;

                 var email = document.loginform.email.value;
                 var password=document.loginform.password.value;
                 var confirm_password = document.loginform.confirm_password.value;
                 var phoneNumberphoneNumber=document.loginform.phoneNumber.value;
                

                 $("#error_name").html("");
                 $("#error_email").html("");
                 $("#error_password").html("");
                 $("#error_confirm_password").html("");
                 $("#error_phoneNumber").html("");
               
                 document.loginform.name.style.borderColor = "";
                 document.loginform.email.style.borderColor = "";
                 document.loginform.password.style.borderColor = "";
                 document.loginform.confirm_password.style.borderColor = "";
                 document.loginform.phoneNumber.style.borderColor = "";
                 
                 if (name == "") {
                     document.loginform.name.focus() ;    
                     document.loginform.name.style.borderColor = "red";
                     $("#error_name").html("Sponser Name is required.");
                     return false;
                 }
                 else  if (email == "") {
                     document.loginform.email.focus() ;    
                     document.loginform.email.style.borderColor = "red";
                     $("#error_email").html("Email is required.");
                     return false;
                 }
                 else if (password == "") {
                     document.loginform.password.focus() ;
                     document.loginform.password.style.borderColor = "red";
                     $("#error_password").html("Password is required");
                     return false;
                 }
                 else if (confirm_password == "") {
                     document.loginform.confirm_password.focus() ;
                     document.loginform.confirm_password.style.borderColor = "red";
                     $("#error_confirm_password").html("Confirm Password is required");
                     return false;
                 }
                 else if (phoneNumber == "") {
                     document.loginform.phoneNumber.focus() ;
                     document.loginform.phoneNumber.style.borderColor = "red";
                     $("#error_phoneNumber").html("Phone Number is required");
                     return false;
                 }

                 else {   
                     signupchk();
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
         
         function signupchk() {
           
         
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         });
         
         var baseUrl = "{{ url('/') }}";
         
         $.ajax({
         type: "POST",
         url: baseUrl + '/api/v1/sponser_signup',
         data: $('#loginform').serialize(),
         cache: 'FALSE',
         beforeSend: function () {loadingGif2
                $('#loadingGif2').show();
         },
         success: function (html) {
            console.log(html);
        
         //  $('#loadingGif2').hide();
             
       
         if(html.data){
         
        
         

         alert("Sponsor Registration is created succesfuuly.");
                  
                    window.location = baseUrl + '/sponser/login';

         
         }
         else{
            // $("#success1"). focus();
            // var mess="Error in Registration";
            // $("#success1").text(mess);
            //document.getElementById("#success").innerHTML = mess;
            //$("#errohtmlr_invalidpass").html("Error in Registration");  

            alert("Error in Sponsor Registration.");
                  
                 return false;
         }
                 }
             });
         }
         
      </script>
      <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
      <script src="{{URL::to('/public/admin/js')}}/bootstrap.min.js" type="text/javascript"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
      <script src="{{URL::to('/public/admin/js')}}/custom.js" type="text/javascript"></script>
   </body>
</html>