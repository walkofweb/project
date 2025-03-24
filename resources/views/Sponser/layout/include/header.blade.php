


<div class="headerWrapper">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <!-- <a class="navbar-brand" href="#">
                <img src="{{URL::to('/public/admin')}}/images/logo.png" alt="">
            </a> -->
            <div class="navbarContent navbar-right align-items-center w-100 justify-content-between d-flex">
                <div class="toggle_btn">
                <i class="ri-menu-line"></i>
                </div>
               
                <ul class="nav navbar-nav gap-20 align-items-center">
                    <li>
                    <div id="actions" class="dropdown btn-group btn btn-outline-secondary" role="group">
  <div id="actionToggle" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  Welcome <span class="fw-500">{{Auth::user()?Auth::user()->name:'Sponser User'}}</span>
  </div>
  <div class="dropdown-menu" role="menu" aria-labelledby="actionToggle">
  

    <a href="javascript:void(0);" class="dropdown-item" name="action1" data-bs-toggle="modal" data-bs-target="#editprofile" onclick="editprofile()"><span>Edit Profile</span></a>
    <a href="javascript:void(0);" class="dropdown-item" name="action2"  data-bs-toggle="modal" data-bs-target="#adminChangePassword" onclick="adminChangePwd()">Change Password</a>
    <a href="{{URL::to('/')}}/sponser/logout" class="dropdown-item" name="action3"> <span>Logout</span></a>
  </div>
</div>
</li>

                    <!-- <li>
                       
                        <?php //echo "test" ;  httpPost(); exit; ?>
                        Welcome <span class="fw-500">{{Auth::user()?Auth::user()->name:'Sponser User'}}</span>
                    </li>

                  <li><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#adminChangePassword" onclick="adminChangePwd()">Change Password</a></li>
                  
                    <li>
                        <a href="{{URL::to('/')}}/sponser/logout" class="fs-18 d-flex align-items-center gap-1">
                            <i class="bi bi-box-arrow-in-right fs-4"></i> -->
                            <!-- <span>Logout</span>
                        </a>
                    </li> -->
                 
                </ul>
            </div>
        </div>
    </nav>
</div>




<div class="hidden modal fade right_side" id="adminChangePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout edit_body_typ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" method="post" id="adminChangePassword_"> 
               <!--  <input type="hidden" name="changeUserPwd" id="changeUserPwd"> -->
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="">New Password</label>
                        <input type="password" placeholder="Enter New Password" id="newAdminPassword" name="newAdminPassword" class="form-control">
                        <span class="err" id="err_newAdminPassword"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" id="confirmAdminPassword" name="confirmAdminPassword" placeholder="Enter Confirm Password" class="form-control">
                         <span class="err" id="err_confirmAdminPwd"></span>
                    </div>
                </div>
                 <div class="mt-4">
                <a href="javascript:void(0);"  class="search-btn" onclick="updateAdminChangePwd()">Update</a>
                <a href="javascript:void(0);" class="search-btn clear-btn" data-bs-dismiss="modal"  onclick="cancelAdminChangePwd()" >Cancel</a>
            </div>
        </form>
            </div>
           
        </div>
    </div>
</div>

<div class="hidden modal fade right_side" id="editprofile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout edit_body_typ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form action="" method="post" id="changrprofile_" enctype="multipart/form-data"> 
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="updatedId" id="updatedId" value="<?php echo isset($profile->id)?$profile->id:'' ; ?>">
               <!--  <input type="hidden" name="changeUserPwd" id="changeUserPwd"> -->
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="">User Name</label>
                        <input type="text" placeholder="Enter User Name" id="name" name="name" class="form-control" value="{{$profile->name?$profile->name:'empty'}}">
                        <span class="err" id="error_name"></span>
                    </div>
                    <label for="">Profile</label>
                    <div class="form-group">
                    <input type="hidden" name="oldimage" id="oldimage" value="<?php echo isset($profile->image)?$profile->image:'' ; ?>">
                        @if($profile->image)
                        <img src="{{ asset('public/profile_image/'.$profile->image)}}"  alt=""/>
                        @else
                        <img src="{{ asset('public/assets/images/favicon-32x32.png')}}"  alt=""/>
                        @endif
                        <input type="file"  id="profile_image" name="profile_image" class="form-control">
                        <span class="err" id="error_profile"></span>
                    </div>
                    <div class="form-group">
                        <label for="">User Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter User Email" class="form-control" value="{{$profile->email?$profile->email:'empty'}}">
                         <span class="err" id="error_emal"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Phone No</label>
                        <input type="number" placeholder="Enter User Phone" id="phone_no" name="phone_no" class="form-control" value="{{$profile->phoneNumber?$profile->phoneNumber:'empty'}}">
                        <span class="err" id="error_phone_no"></span>
                    </div>
                  
                    <div class="form-group">
                        <label for="country">Country Name</label>
                        <select  placeholder="Enter User Country Name" id="country" name="country" class="form-control">
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                            <option value="{{ $country->id }}"  {{  $country->id==$profile->countryId ? 'selected' : ''}} > {{ $country->title }}</option>
                             @endforeach

                        </select>
                        <span class="err" id="error_country"></span>
                    </div>
                    <div class="form-group">
                        <label for="country">Address</label>
                        <textarea placeholder="Enter User Address" id="address" name="address" class="form-control">{{$profile->address?$profile->address:'empty'}}</textarea>
                        <span class="err" id="error_address"></span>
                    </div>
                </div>
                    <div class="mt-4">
                        <a href="javascript:void(0);"  class="search-btn" onclick="updateprofile()">Update</a>
                        <a href="javascript:void(0);" class="search-btn clear-btn" data-bs-dismiss="modal"  onclick="canceleupdateprofile()" >Cancel</a>
                    </div>
            </form>
            </div>
           
        </div>
    </div>
</div>




       

<script type="text/javascript">
   // $("#actionToggle").dropdown("toggle");
    function adminChangePwd(){
        $("#adminChangePassword").prependTo("body");
    }
    function cancelAdminChangePwd(){
        $('#adminChangePassword_')[0].reset() ;
    }
    function canceleupdateprofile(){
    $('#changrprofile_')[0].reset();
    }

    function updateprofile(){
        var name=$('#name').val();
        var email = $('#email').val();
        var phone_no = $('#phone_no').val();
        var country=$('#country').val();
        var user_id=$('#updatedId').val();
        var  address=$('#address').val();
        $('.err').html('') ;
        if(name==''){
            $('#error_name').html('Please enter correct name') ;
        }else if(email==''){
            $('#error_email').html('Please enter correct email') ; 
       
        }else if(phone_no==''){
            $('#error_phone_no').html('Please enter phone no') ;
        }else if(country==''){
            $('#error_country').html('Please enter country') ;
        }
        var formData=new FormData($('#changrprofile_')[0]);

        
      
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        ajaxCsrf();
        $.ajax({
            type:"POST",
            url:baseUrl+'/updateSponserProfile',
            data:formData,
            
            enctype: 'multipart/form-data',
            dataType:'json',
            cache: 'FALSE',
        contentType:false,
         processData:false,
            beforeSend:function()
            {
                 ajax_before();
            },
            success:function(res)
            {
                console.log(res);
                ajax_success() ;

if(res.status==1){
$('.modal-backdrop').hide();      
$('#changrprofile_')[0].reset();
$('#changrprofile_').modal('hide');
$('#dataTable').DataTable().ajax.reload();                
  statusMesage('Profile updated successfully','success');
}else{
   statusMesage('something went wrong','error');
}
            }
        });


    }

    function updateAdminChangePwd(){
    
        var newPwd = $('#newAdminPassword').val();
        var confirmPwd = $('#confirmAdminPassword').val();

        $('.err').html('') ;
        if(newPwd==''){
            $('#err_newAdminPassword').html('Please enter new password') ;
        }else if(newPwd.length < 6){
            $('#err_newAdminPassword').html('Please enter atleaset 6 digit password') ;
        }else if(confirmPwd==''){
            $('#err_confirmAdminPwd').html('Please enter confirm password') ;
        }else if(newPwd!=confirmPwd){
            $('#err_confirmAdminPwd').html('both password must be matched') ;
        }else{

               var formData = $('#adminChangePassword_').serialize() ;

                 ajaxCsrf();

        $.ajax({
            type:"POST",
            url:baseUrl+'/changeAdminPassword',
            data:formData,
            dataType:'json',
            beforeSend:function()
            {
                 ajax_before();
            },
            success:function(res)
            {

                 ajax_success() ;

            if(res.status==1){
            $('.modal-backdrop').hide();      
            $('#adminChangePassword_')[0].reset();
            $('#adminChangePassword').modal('hide');
            $('#dataTable').DataTable().ajax.reload();                
              statusMesage('password updated successfully','success');
            }else{
               statusMesage('something went wrong','error');
            }
            }

            });
    }
 }
  
 function editprofile(){
        $("#editprofile").prependTo("body");
    }
/* $('.toggle_btn').click(function(){
    $('.grid-container').toggleClass('collapse_grid_container');
 });*/


// test 2
$("#actionToggle").on("click", function() {
   // alert("dfsdfds");
 $(".dropdown-menu").toggle();
});

</script>