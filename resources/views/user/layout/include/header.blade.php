<style>
    .emp-profile{
    padding: 3%;
    margin-top: 3%;
    margin-bottom: 3%;
    border-radius: 0.5rem;
    background: #fff;
}
.profile-img{
    text-align: center;
}
.profile-img img{
    width: 70%;
    height: 100%;
}
.profile-img .file {
    position: relative;
    overflow: hidden;
    margin-top: -20%;
    width: 70%;
    border: none;
    border-radius: 0;
    font-size: 15px;
    background: #212529b8;
}
.profile-img .file input {
    position: absolute;
    opacity: 0;
    right: 0;
    top: 0;
}
.profile-head h5{
    color: #333;
}
.profile-head h6{
    color: #0062cc;
}
.profile-edit-btn{
    border: none;
    border-radius: 1.5rem;
    width: 70%;
    padding: 2%;
    font-weight: 600;
    color: #6c757d;
    cursor: pointer;
}
.proile-rating{
    font-size: 12px;
    color: #818182;
    margin-top: 5%;
}
.proile-rating span{
    color: #495057;
    font-size: 15px;
    font-weight: 600;
}
.profile-head .nav-tabs{
    margin-bottom:5%;
}
.profile-head .nav-tabs .nav-link{
    font-weight:600;
    border: none;
}
.profile-head .nav-tabs .nav-link.active{
    border: none;
    border-bottom:2px solid #0062cc;
}
.profile-work{
    padding: 14%;
    margin-top: -15%;
}
.profile-work p{
    font-size: 12px;
    color: #818182;
    font-weight: 600;
    margin-top: 10%;
}
.profile-work a{
    text-decoration: none;
    color: #495057;
    font-weight: 600;
    font-size: 14px;
}
.profile-work ul{
    list-style: none;
}
.profile-tab label{
    font-weight: 600;
}
.profile-tab p{
    font-weight: 600;
    color: #0062cc;
}
    </style>
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
                        <?php //echo "test" ;  httpPost(); exit; ?>
                        Welcome <span class="fw-500">{{Auth::user()->name?Auth::user()->name:'Web User'}}</span>
                    </li>

                    <li><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editprofile" onclick="editprofile()">Edit Profile</a></li>

                  <li><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#adminChangePassword" onclick="adminChangePwd()">Change Password</a></li>
                  
                    <li>
                        <a href="{{URL::to('/')}}/user/logout" class="fs-18 d-flex align-items-center gap-1">
                            <!-- <i class="bi bi-box-arrow-in-right fs-4"></i> -->
                            <span>Logout</span>
                        </a>
                    </li>
                 
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
            <div class="container emp-profile">
            <form method="post">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-img">
                            <img src="https://walkofweb.in/public/assets/images/favicon-32x32.png" height="500" width="200" alt=""/>
                            <div class="file btn btn-lg btn-primary">
                                Change Photo
                                <input type="file" name="file"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-head">
                                    <h5>
                                    webwalkof
                                    </h5>
                                    
                                    <p class="proile-rating">Total Post : <span>3</span></p>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                                </li>
                               
                            </ul>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    
                    <div class="col-md-8">
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>User Id</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>8528501410551886</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Name</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>webwalkof</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>kshitighelani@gmail.com</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>User Name</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>webwalkof</p>
                                            </div>
                                        </div>
                                        
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Experience</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Expert</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Hourly Rate</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>10$/hr</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Total Projects</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>230</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>English Level</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>Expert</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Availability</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>6 months</p>
                                            </div>
                                        </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Your Bio</label><br/>
                                        <p>Your detail description</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>           
        </div>
    </div>

           
        </div>
    </div>
</div>
<!-- 
<script type="text/javascript">
    function adminChangePwd(){
        $("#adminChangePassword").prependTo("body");
    }
    function editprofile(){
        $("#editprofile").prependTo("body");
    }
    function cancelAdminChangePwd(){
        $('#adminChangePassword_')[0].reset() ;
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
  
/* $('.toggle_btn').click(function(){
    $('.grid-container').toggleClass('collapse_grid_container');
 });*/


</script> -->