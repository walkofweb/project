 
            <div class="carManagement__wrapper">
                <div class="breadcrumbWrapper">
                    <nav aria-label="breadcrumb">
                        <h3 class="fs-5 m-0 fw-500">User Management</h3>
                        <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="{{URL::to('/')}}/user/dashboard#user_management" onclick="dashboard()" >Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User Management</li>
                        </ol>
                    </nav>
                </div>
                <form class="" id="n_serarchForm" action="javascript:void(0);" > 
                <div class="filterWrapper">
                    <div class="form filterWrapper__l">
                        <div class="form-group">
                            <label for="Manufacture">Name</label>
                            <input type="text" class="form-control" placeholder="Name" id="cust_name">
                        </div>
                        <div class="form-group">
                            <label for="Manufacture">Username</label>
                            <input type="text" class="form-control" placeholder="Username" id="cust_username">
                        </div>
                         <div class="form-group">
                            <label for="status">Rank Type</label>
                            <select name="" id="cust_rankType" class="form-control">
                                <option value="">Select</option>
                                <option value="1">Gold Tier</option>
                                <option value="2">Silver Tier</option>
                                <option value="4">White Tier</option>
                                <option value="3">Bronze Tier</option>
                                <option value="5">Blue Tier</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Model">Email</label>
                            <input type="text" class="form-control" id="cus_email" placeholder="Email">
                        </div>  
                        <div class="form-group">
                            <label for="oName">Mobile Number</label>
                            <input type="text" class="form-control" id="cus_mobileNumber" placeholder="Mobile Number">
                        </div>
                          <div class="form-group">
                            <label for="oName">Country</label>
                            <input type="text" class="form-control" id="cus_Country" placeholder="Country">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="" id="cust_status" class="form-control">
                                <option value="">Select</option>
                                <option value="1">Active</option>
                                <option value="0">In Active</option>
                            </select>
                        </div>
                        <div class="d-flex">
                              
                            <a href="javascript:void(0);" class="search-btn" onclick="searchNType()">
                                <i class="bi bi-search"></i><span>Search</span>
                            </a>
                             <a href="javascript:void(0);" class="search-btn clear-btn ml-5px" onclick="resetSearchForm()">
                                <i class="bi bi-eraser-fill"></i><span>Clear</span>
                            </a>
                        </div>
                         
                    </div>
                </div>
            </form>
                <div class="table-area">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">User</th>
                                <th scope="col">Image</th>
                                <th scope="col">Username</th>
                                <th scope="col">Rank Type</th>
                                <th scope="col">Platform</th>
                                <th scope="col" width="25%">Email</th>
                                <th scope="col">Contact No.</th>
                                <th scope="col">Followers</th>
                                <th scope="col">Rank</th>
                                <th scope="col">Date Joined</th>
                                <th scope="col">Country</th>
                                <th scope="col" width="10%">Status</th>   
                                <th scope="col" width="10%">Status</th>  
                                <th scope="col" width="10%">rank_</th>
                                <th scope="col">Action</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


        <div class="modal fade right_side" id="change_pass" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout edit_body_typ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" method="post" class="form-control" id="changePassword_"> 
                <input type="hidden" name="changeUserPwd" id="changeUserPwd">
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="">New Password</label>
                        <input type="password" placeholder="Enter New Password" id="newPassword" name="newPassword" class="form-control">
                        <span class="err" id="err_newPassword"></span>
                    </div>

                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Enter Confirm Password" class="form-control">
                         <span class="err" id="err_confirmPwd"></span>
                    </div>
                </div>
                 <div class="mt-4">
                <a href="javascript:void(0);" onclick="updateChangePwd()" class="search-btn">Update</a>
                <a href="javascript:void(0);" class="search-btn clear-btn" data-bs-dismiss="modal" onclick="cancelChangePwd()" >Cancel</a>
            </div>
        </form>
            </div>
           
        </div>
    </div>
</div>

<script type="text/javascript">

     $(document).ready(function($k){

        datatablePagination($k); 

 $('#dataTable').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      retrieve: true,
      sDom: 'lrtip',
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      sPaginationType: "bootstrap",
      "aaSorting": [[ 0, 'desc' ]],   
      columnDefs: [  
                    {
                    "aTargets": [0],
                    "visible":false,
                    // "mRender": function(data, type, full){
                      
                    //     return '<th scope="row"><a href="'+baseUrl+'/administrator/dashboard#customer_detail/'+full['id']+'" onclick="customerDetail('+full['id']+')"><i class="bi bi-chevron-right"></i></a></th> ';
                    // }
                },
                {
                    "aTargets": [3],
                    "visible":true
                },
                {
                    "aTargets": [7],
                    "visible":false
                },
                {
                    "aTargets": [10],
                    "visible":false
                },
                {
                    "aTargets": [11],
                    "visible":false
                },

                {
                    "aTargets": [12],
                    "visible":false
                },
                {
                    "aTargets": [1],
                    "mRender": function(data, type, full){
                        var response ='' ;
                        if(full['image']!=''){
                         response='<img src="'+full['image']+'" width="50px" height="50px" /> '+full['name'];
                        }else{
                          response=full['name'];   
                        }
                        return response ;
                    }
                },
                 {
                    "aTargets": [4],
                    "mRender": function(data, type, full){
                      return full['rank'];
                       
                    }
                }
                
                ,{
                    "aTargets": [2],
                    "visible":false
                },{
                    "aTargets": [14],
                    "visible":false
                },
                {
                            "aTargets": [13],
                            "mRender" : function(data, type, full){ 
                              var action='' ;
                               var className='' ;

                            if(full['status']==1){
                              className='activeNFor' ;
                            }else{
                              className='inactiveNFor' ;
                            }

                            action+='<span class="'+className+'">'+full['status_']+'</span>';

                            return action ;
                            }
                        } ,

                {
                    "aTargets": [15],
                     "mRender": function(data, type, full){
                        var response ='<td><div class="align-items-center d-flex"><div class="more_n"> <i class="bi bi-three-dots-vertical" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"></i> <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <li><a class="dropdown-item" href="javascript:void(0);" onclick="ConfirmDelete('+full['id']+')">Delete</a></li> <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#change_pass" onclick="changePassword('+full["id"]+')">Change Password</a></li> </ul> </div>  <div> <label class="switch">' ;

                        if(full['status']=='1'){
                             response +='<input type="checkbox" onclick="changeUsrStatus('+full['id']+')" checked>' ;
                            
                        }else{
                             response +='<input type="checkbox" onclick="changeUsrStatus('+full['id']+')" >' ;
                           
                        }

                        response+='<span class="slider"></span> </label> </div> </div> </td>'  ;

                        return response ;
                    }
                }  
                // {
                //     "aTargets": [12],
                //      "mRender": function(data, type, full){
                //         var response ='<td><div class="align-items-center d-flex"> <div class="more_n"> <i class="bi bi-three-dots-vertical" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"></i> <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <li><a class="dropdown-item" href="javascript:void(0);" onclick="ConfirmDelete('+full['id']+')">Delete</a></li> <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#change_pass" onclick="changePassword('+full["id"]+')">Change Password</a></li> </ul> </div> ' ;

                        

                //         response+=' </div> </td>'  ;

                //         return response ;
                //     }
                // }
                ],

            ajax: {
                      url: '{!! URL::asset('user_datatable') !!}',
                    },
             columns : [
             
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'image' },
                        { data: 'username' },
                        { data: 'rank'},
                        { data: 'registration_from'},
                        { data: 'email'},
                        { data: 'phoneNumber'},
                        { data: 'page_followers'},
                        { data: 'rank_'},
                        { data: 'created_at'},
                        { data: 'countryId' },
                        { data: 'status' },
                        { data: 'status_' },
                        { data: 'rankN' }
          ],

        });

      $k('.input-group-addon').click(function() {
        $k(this).prev('input').focus();
    });
         

});


function changeUsrStatus(id){

    ajaxCsrf();

    $.ajax({
        type:"POST",
        url:baseUrl+'/userManagement/changeStatus',
        data:{"id":id},
        dataType:'json',
    beforeSend:function()
    {
        ajax_before();
    },
    success:function(res)
    {
         ajax_success() ;
    if(res.status==1){
    var table = $('#dataTable').DataTable();
    table.draw( false );
     statusMesage('changed status successfully','success');
    }else{
     statusMesage('something went wrong','success');
    }
    }

    });
}

function resetSearchForm(){

    var table = $('#dataTable').DataTable();
    document.getElementById("n_serarchForm").reset();
  customerManagement();
     //$('#dataTable').DataTable().ajax.reload();        
  
}



  function searchNType(){
     
        var status=$("#cust_status").val();
        var mobileNumber=$("#cus_mobileNumber").val();
        var email=$("#cus_email").val();
        var cName=$("#cust_name").val();
         var userName=$("#cust_username").val();
        var rankType=$("#cust_rankType").val();
        var country = $("#cus_Country").val();


         
    if(country){
   
          $('#dataTable').DataTable().column(11).search(country).draw();
    }else{
        $('#dataTable').DataTable().column(11).search('').draw();
    }


    if(userName){
   
          $('#dataTable').DataTable().column(3).search(userName).draw();
    }else{
        $('#dataTable').DataTable().column(3).search('').draw();
    }

     if(rankType){
   
          $('#dataTable').DataTable().column(14).search(rankType).draw();
    }else{
        $('#dataTable').DataTable().column(14).search('').draw();
    }

   
   
     if(status){
   
          $('#dataTable').DataTable().column(12).search(status).draw();
    }else{

          $('#dataTable').DataTable().column(12).search('').draw();
    }
   
     if(mobileNumber){
   
          $('#dataTable').DataTable().column(7).search(mobileNumber).draw();
    }else{
         $('#dataTable').DataTable().column(7).search('').draw();
    }

     if(email){
   
          $('#dataTable').DataTable().column(6).search(email).draw();
    }else{
        $('#dataTable').DataTable().column(6).search('').draw();
    }

    if(cName){
   
          $('#dataTable').DataTable().column(1).search(cName).draw();
    }else{
        $('#dataTable').DataTable().column(1).search('').draw();
    }

   
}
   
function ConfirmDelete(id) {
    
    if(confirm("Are you sure ?")) {
        delete_customer(id);
    }
}

    function delete_customer(id){
             ajaxCsrf();
        $.ajax({type:"POST",
        url:baseUrl+'/delete_customer',
        data:{"id":id},
        dataType:'json',
        beforeSend:function()
        {
             ajax_before();
        },
        success:function(res)
        {

             ajax_success() ;

        if(res.status==1){
        //carManagement();

          $('#dataTable').DataTable().ajax.reload();                
          statusMesage('Deleted successfully','success');
        }else{
           statusMesage('Something went wrong','error');
        }
        }

        });

    }

 function updateChangePwd(){
    
        var newPwd = $('#newPassword').val();
        var confirmPwd = $('#confirmPassword').val();

        $('.err').html('');
        if(newPwd==''){
            $('#err_newPassword').html('Please enter new password') ;
        }else if(confirmPwd==''){
            $('#err_confirmPwd').html('Please enter confirm password') ;
        }else if(newPwd!=confirmPwd){
            $('#err_confirmPwd').html('both password must be matched') ;
        }else{

               var formData = $('#changePassword_').serialize() ;

                 ajaxCsrf();

        $.ajax({
            type:"POST",
            url:baseUrl+'/changePassword',
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
           $('#changePassword_')[0].reset();
            $('#change_pass').modal('hide');
           $('#dataTable').DataTable().ajax.reload();                
              statusMesage('password updated successfully','success');
            }else{
               statusMesage('something went wrong','error');
            }
            }

            });
    }
 }

function changePassword(userId){
    $('#changeUserPwd').val(userId);
}
 function cancelChangePwd(){

    $('#changePassword_')[0].reset();


 }
 
</script>