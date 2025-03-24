 
            <div class="carManagement__wrapper">
                <div class="breadcrumbWrapper">
                    <nav aria-label="breadcrumb">
                        <h3 class="fs-5 m-0 fw-500">User Package Mangement</h3>
                        <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="{{URL::to('/')}}/administrator/dashboard#packae_user" onclick="dashboard()" >Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">User Package Management</li>
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
                            <label for="Model">Email</label>
                            <input type="text" class="form-control" id="cus_email" placeholder="Email">
                        </div>  
                        <div class="form-group">
                            <label for="oName">Mobile Number</label>
                            <input type="text" class="form-control" id="cus_mobileNumber" placeholder="Mobile Number">
                        </div>
                          <!-- <div class="form-group">
                            <label for="oName">Country</label>
                            <input type="text" class="form-control" id="cus_Country" placeholder="Country">
                        </div> -->
                       
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
                                <th scope="col">Package Image</th>
                                <!-- <th scope="col">Image</ th> -->
                                <th scope="col">Username</th>
                                <th scope="col">Platform</th>
                                 <th scope="col" width="25%">Email</th>
                                <th scope="col">Contact No.</th>
                                <th scope="col">Package Name</th>
                                <th scope="col">price</th>
                                <th scope="col">TimeLimit</th>
                                <!-- <th scope="col">Followers</th> -->
                                <!-- <th scope="col">Rank</th>
                                <th scope="col">Date Joined</th>
                                <th scope="col">Country</th> -->
                                <th scope="col" width="10%">Status</th>  
                                <th scope="col" width="10%">Package Status</th>
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
                <h5 class="modal-title" id="exampleModalLabel"></h5>Package Approve For User
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" method="post" class="form-control" id="changePassword_"> 
                <input type="hidden" name="changeUserPwd" id="changeUserPwd">
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="">Status</label>

                        <select id="status" name="status" class="form-control">
                            <option value="">Select</option>
                            <option value="1">Approve</option>
                            <option value="2">Reject</option>
                        </select>
                      
                        <span class="err" id="err_status"></span>
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
                    "mRender": function(data, type, full){
                      
                        return '<th scope="row"><a href="'+baseUrl+'/administrator/dashboard#package_user/'+full['id']+'" onclick="package_user('+full['id']+')"><i class="bi bi-chevron-right"></i></a></th> ';
                    }
                },
               
                
                {
                    "aTargets": [1],
                    "visible":true,
                    "mRender": function(data, type, full){
                        var response ='' ;
                        if(full['image']!=''){
                         response='<img src=https://walkofweb.in/public/admin/package/images/'+full['image']+' width="50px" height="50px" /> <br>';
                        }
                       
                        else{
                            response='<img src="https://walkofweb.in/public/admin/package/images/dummy.jpg" width="50px" height="50px"  /> '; 
                        }
                        return response ;
                    }
                },
                
                
                {
                    "aTargets": [9],
                    "visible":false
                },
                // {
                //     "aTargets": [10],
                //     "visible":false
                // },
                {
                            "aTargets": [10],
                            "mRender" : function(data, type, full){ 
                              var action='' ;
                               var className='' ;

                            if(full['userpackage_status']==1){
                              className='activeNFor' ;
                            }else{
                              className='inactiveNFor' ;
                            }

                            action+='<span class="'+className+'">'+full['status_']+'</span>';

                            return action ;
                            }
                        } ,

                {
                    "aTargets": [11],
                     "mRender": function(data, type, full){
                        var response ='<td><div class="align-items-center d-flex"><div class="more_n"> <input type="hidden" id="upakege_id" name="upakege_id" value="'+full["upakege_id"]+'"><input type="hidden" id="pakage_id" name="pakage_id" value="'+full["package_id"]+'"><i class="bi bi-three-dots-vertical" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"></i> <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <li><a class="dropdown-item" href="javascript:void(0);" onclick="ConfirmDelete('+full['upakege_id']+')">Delete</a></li> <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#change_pass" onclick="changePassword('+full["id"]+')">Change Status</a></li> </ul> </div>  <div> <label class="switch">' ;

                        if(full['status']=='1'){
                             response +='<input type="button" class="search-btn" onclick="changeUsrStatus('+full['id']+')" checked>' ;
                            
                        }else{
                             response +='<input type="button" class="search-btn" onclick="changeUsrStatus('+full['id']+')" >' ;
                           
                        }

                        //response+='<span class="slider"></span>'; 
                        response+='</label> </div> </div> </td>'  ;

                        return response ;
                    }
                }  
                // {
                //     "aTargets": [11],
                //      "mRender": function(data, type, full){
                //         var response ='<td><div class="align-items-center d-flex"> <div class="more_n"> <i class="bi bi-three-dots-vertical" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"></i> <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <li><a class="dropdown-item" href="javascript:void(0);" onclick="ConfirmDelete('+full['id']+')">Delete</a></li> <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#change_pass" onclick="changePassword('+full["id"]+')">Change Password</a></li> </ul> </div> ' ;

                        

                //         response+=' </div> </td>'  ;

                //         return response ;
                //     }
                // }
                ],

            ajax: {
                      url: '{!! URL::asset('AddUserpackage_datatable') !!}',
                    },
             columns : [
             
                        { data: 'id' },
                        { data: 'name' },
                        // { data: 'image' },
                        { data: 'username' },
                        { data: 'rank'},
                        { data: 'email'},
                        { data: 'phoneNumber'},
                        { data: 'packege_name'},
                        { data: 'price'},
                        { data: 'time_limit'},
                        // { data: 'created_at'},
                        // { data: 'countryId' },
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
    AddUser_packageList();
     $('#dataTable').DataTable().ajax.reload();        
  
}



  function searchNType(){
  
        var status=$("#cust_status").val();
        var package_name=$("#cust_name").val();
        var mobileNumber=$("#cus_mobileNumber").val();
        var email=$("#cus_email").val();
    
        var userName=$("#cust_username").val();
    


         
    if(package_name){
   
          $('#dataTable').DataTable().column(6).search(package_name).draw();
    }else{
        $('#dataTable').DataTable().column(6).search('').draw();
    }


    if(userName){
   
          $('#dataTable').DataTable().column(2).search(userName).draw();
    }else{
        $('#dataTable').DataTable().column(2).search('').draw();
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
   
          $('#dataTable').DataTable().column(4).search(email).draw();
    }else{
        $('#dataTable').DataTable().column(4).search('').draw();
    }

    if(cName){
   
          $('#dataTable').DataTable().column(1).search(cName).draw();
    }else{
        $('#dataTable').DataTable().column(1).search('').draw();
    }

   
}
   
function ConfirmDelete(id) {
    
    if(confirm("Are you sure ?")) {
        delete_userpackage(id);
    }
}

    function delete_userpackage(id){
             ajaxCsrf();
        $.ajax({type:"POST",
        url:baseUrl+'/deleteUserPackage',
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
    
        var newPwd = $('#status').val();
        var  pakage_id=$('#pakage_id').val();

        

        $('.err').html('');
        if(newPwd==''){
            $('#err_status').html('Please enter status') ;
        }
      
        else{

               var formData = $('#changePassword_').serialize() ;
               

                 ajaxCsrf();

        $.ajax({
            type:"POST",
            url:baseUrl+'/updateStatus/'+pakage_id,
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
              statusMesage('status updated successfully','success');
            }else{
               statusMesage('something went wrong','error');
            }
             }

             });
    }
 }

function changePassword(userId){
    $('#changeUserPwd').val(userId);
    $('#pakage_id').val();
}
 function cancelChangePwd(){

    $('#changePassword_')[0].reset();


 }
 
</script>