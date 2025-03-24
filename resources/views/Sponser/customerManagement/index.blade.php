
            <div class="carManagement__wrapper">
                <div class="breadcrumbWrapper">
                    <nav aria-label="breadcrumb">
                        <h3 class="fs-5 m-0 fw-500">Customer Management</h3>
                        <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="{{URL::to('/')}}/sponser/dashboard#index" onclick="dashboard()" >Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Customer Management</li>
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
                                <option value="2">SilveTier</option>
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
                            <label for="oName">Country</label>
                            <input type="text" class="form-control" id="cus_Country" placeholder="Country">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="" id="cust_status" class="form-control">
                                <option value="">Select</option>
                                <option value="1">Active</option>r 
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
                                <th scope="col">Followers</th>
                                <th scope="col">Rank</th>
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
                <div class="modal fade right_side" id="checkout_body" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout edit_body_typ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Ads</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body" id="edicheckouttBody">                
                
            </div>
        </div>
    </div>
</div>
        <div class="modal fade right_side" id="hire_package" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout edit_body_typ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Package Details</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
            <div class="table-area">
            
          
                    <table class="table" id="dataTable_package">
                        <thead>
                            <tr>
                              
                                <th scope="col">Package Name</th>
                                <th scope="col">title</th>
                                <th scope="col">time_limit</th>
                                <th scope="col">Price</th>
                                <th scope="col">Description</th>
                               <th scope="col">Action</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
           
        </div>
    </div>
</div>


<div class="modal fade right_side" id="hire_checkout" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout edit_body_typ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Shipping Address</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
            <!-- <div class="table-area">
            
          
                    <table class="table" id="dataTable_package">
                        <thead>
                            <tr>
                              
                                <th scope="col">Package Name</th>
                                <th scope="col">title</th>
                                <th scope="col">time_limit</th>
                                <th scope="col">Price</th>
                                <th scope="col">Description</th>
                               <th scope="col">Action</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div> -->
                <div class="row">
  <div class="col-md-6 mb-4">
    <div class="card mb-4">
      <div class="card-header py-3">
        <h5 class="mb-0">Biling details</h5>
      </div>
      <div class="card-body">
        <form>
          <!-- 2 column grid layout with text inputs for the first and last names -->
          <div class="row mb-4">
            <div class="col">
              <div data-mdb-input-init class="form-outline">
              <label class="form-label" for="form7Example1"> Name</label>
                <input type="text" id="sponser_name" name="sponser_name" class="form-control" value="" />
                
              </div>
            </div>
            
          </div>

          <!-- Text input -->
         

          <!-- Text input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <textarea  id="sponser_address" name="sponser_address" class="form-control" />Address</textarea>
            <label class="form-label" for="sponser_address">Address</label>
          </div>

          <!-- Email input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" id="sponser_email" name="sponser_email" class="form-control"  value=""/>
            <label class="form-label" for="sponser_email">Email</label>
          </div>

          <!-- Number input -->
          <div data-mdb-input-init class="form-outline mb-4">
            <input type="number" id="sponser_phone" name="sponser_phone" class="form-control" />
            <label class="form-label" for="sponser_phone">Phone</label>
          </div>

          
          
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-5 mb-4">
    <div class="card mb-4">
      <div class="card-header py-3">
        <h5 class="mb-0">Summary</h5>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
            Package
            <span id="package_name"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
            Time Limit
            <span id="package_timelimit"></span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center px-0">
            Price
            <span id="package_price">Rs:</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
            <div>
              <strong>Total amount</strong>
              
            </div>
            <span id="gross_total"><strong>Rs:</strong></span>
          </li>
        </ul>

        
     
        <button id="paymentSub" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#payment">Procceed to Subscription</button>
         
       
      </div>
    </div>
  </div>
</div>
            </div>
           
        </div>
    </div>
</div>
<div class="modal fade right_side" id="payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout edit_body_typ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fill Details your Account details</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
            <div class="row">

<div class="col-md-12 col-md-offset-6">

    <div class="panel panel-default credit-card-box">

        <div class="panel-heading display-table" >

                <h3 class="panel-title" >Payment Details</h3>

        </div>

        <div class="panel-body">



            @if (Session::has('success'))

                <div class="alert alert-success text-center">

                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>

                    <p>{{ Session::get('success') }}</p>

                </div>

            @endif



            <form 

                    role="form" 

                 

                    class="require-validation"

                    data-cc-on-file="false"

                    data-stripe-publishable-key="pk_test_51IUxQhHCUbph94h9lQGkPs5a7V3iDHDlJy2GBUvzXKcqVC1kS4Vc6R87zGWynEMHGaB0FklHROSv5bsrd1HZS54T00Fkj6dSdx"

                    id="payment-form">

                @csrf

            <input type=hidden id="userpackage_id" value="">
            <input type=hidden id="userpackage_grossprice" value="">

                <div class='form-row row'>

                    <div class='col-xs-12 form-group required'>

                        <label class='control-label card_name'>Name on Card</label> <input

                            class='form-control' size='4' type='text'>

                    </div>

                </div>



                <div class='form-row row'>

                    <div class='col-xs-12 form-group card required'>

                        <label class='control-label'>Card Number</label> <input

                            autocomplete='off' class='form-control card-number' size='20'

                            type='text'>

                    </div>

                </div>



                <div class='form-row row'>

                    <div class='col-xs-12 col-md-4 form-group cvc required'>

                        <label class='control-label'>CVC</label> <input autocomplete='off'

                            class='form-control card-cvc' placeholder='ex. 311' size='4'

                            type='text'>

                    </div>

                    <div class='col-xs-12 col-md-4 form-group expiration required'>

                        <label class='control-label'>Expiration Month</label> <input

                            class='form-control card-expiry-month' placeholder='MM' size='2'

                            type='text'>

                    </div>

                    <div class='col-xs-12 col-md-4 form-group expiration required'>

                        <label class='control-label'>Expiration Year</label> <input

                            class='form-control card-expiry-year' placeholder='YYYY' size='4'

                            type='text'>

                    </div>

                </div>



                <div class='form-row row'>

                    <div class='col-md-12 error form-group hide'>

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

</div>

</div>
            </div>
</div>
</div>
</div>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
$(function() {

  

/*------------------------------------------

--------------------------------------------

Stripe Payment Code

--------------------------------------------

--------------------------------------------*/




  

/*------------------------------------------

--------------------------------------------

Stripe Response Handler

--------------------------------------------

--------------------------------------------*/

 

});

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
                    "visible":false
                  
                },
                {
                    "aTargets": [3],
                    "visible":true
                },
                {
                    "aTargets": [10],
                    "visible":false
                },
                {
                    "aTargets": [1],
                    "mRender": function(data, type, full){
                        var response ='' ;
                        if(full['image']!=''){
                         response='<img src="'+full['image']+'" width="50px" height="50px" /> '+full['name'];
                        }else{
                            response='<img src="https://walkofweb.in/public/assets/images/favicon-32x32.png" width="50px" height="50px" /> '; 
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
                    "aTargets": [12],
                    "visible":false
                },
                {
                            "aTargets": [11],
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
                    "aTargets": [13],
                     "mRender": function(data, type, full){
                        var response ='<td><div class="align-items-center d-flex"><div class="more_n">   <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#hire_package" onclick="hire_package('+full['id']+')" >Hire</a></div></div></td> '  ;

                      
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
                      url: '{!! URL::asset('sponsor/customer_datatable') !!}',
                    },
             columns : [
             
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'image' },
                        { data: 'username' },
                        { data: 'rank'},
                        { data: 'registration_from'},
                       
                        { data: 'email'},
                        { data: 'followers'},
                        { data: 'rank_'},
                       
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
function hire_package(userId)
         
{
    
    $('#dataTable_package').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 10,
      retrieve: true,
      sDom: 'lrtip',
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      sPaginationType: "bootstrap",
      "aaSorting": [[ 0, 'desc' ]],   
      columnDefs: [  
                //     {
                //     "aTargets": [0],
                //    "mRender": function(data, type, full){
                //         var response ='<td><div class="align-items-center d-flex"><div class="more_n">   <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#hire_package" onclick="changePassword('+full["id"]+')">'+full["id"]+'</a></div></div></td> '  ;

                      
                //         return response ;
                //     }
                  
                // },
                
               
                
                {
                    "aTargets": [5],
                     "mRender": function(data, type, full){


                      

                    //    var response =   ' <div class="align-items-center d-flex"><div class="more_n">   <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#edit_body1" onclick="HireStatus('+full['id']+')" >Hire</a></div></div>';


                    

                       var response ='<td><div class="align-items-center d-flex"><div class="more_n">   <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#hire_checkout" onclick="HireStatus('+full['userpackage_id']+')" >Hire</a></div></div></td> '  ;

                       
                        
                       

                      
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
                      url: ' {{ route('sponsor.package_datatable') }}',
                      data:{id:userId}
                    },
             columns : [
             
                       
                        { data: 'packege_name' },
                        { data: 'title' },
                        { data: 'time_limit' },
                        { data: 'price'},
                        { data: 'description'},
                        { data: 'rankN' }
          ],

        });

}
function changePassword(userId){
    $('#changeUserPwd').val(userId);
}
 function cancelChangePwd(){

    $('#changePassword_')[0].reset();


 }
 function stripe_payment()
 {
    ajaxCsrf();
    $('#hire_checkout').modal('hide') ;
    
    $('#payment').modal('show') ;
 }
 function HireStatus(id)
   {

         ajaxCsrf();
        $('#hire_checkout').modal('show') ;
        var x=confirm("Are you sure hire package ?");
        if(x==true)
    {
   
            $.ajax({type:"POST",
            url:baseUrl+'/sponsor/user_hire',
            data:{"id":id},
            dataType:'json',
    //   cache:false,
    //    contentType:false,
    //    processData:false,
            beforeSend:function()
            {
                ajax_before();
            },
            success:function(res)
            {
               console.log(res);
             
               if(res) 
               {
                console.log(res.id);
                $("#sponser_name").val(res.data.name);
                $("#sponser_address").val(res.data.address);
                $("#sponser_email").val(res.data.email);
                $("#sponser_phone").val(res.data.phoneNumber);
                $("#package_name").html(res.data.packege_name);
                $("#package_timelimit").html(res.data.time_limit+'Days');
                $("#package_price").html('Rs: '+res.data.price);
                $("#gross_total").html('Rs: '+res.data.price);
                $("#paymentSub").click(function(){

                    $('#payment').modal('show');
                    $("#userpackage_id").val(id);
                    $("#userpackage_grossprice").val(res.data.price);
                    
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

                            exp_year: $('.card-expiry-year').val(),
                            

                        

                        }, stripeResponseHandler);

                    }

                    
                 //console.log(stripeResponseHandler);
   



            });


                });

                
               }
               else{
                statusMesage(res.message,'error');
               }
               // var obj = JSON.parse(res);
              
                

                
            }
        });
   }
   else{
    return false;
   }
}
    function stripeResponseHandler(status, response) {
      

        var $form = $(".require-validation");

        if (response.error) {

            $('.error')

                .removeClass('hide')

                .find('.alert')

                .text(response.error.message);

        } 
        else 
        {

            /* token contains id, last4, and card type */

            var token = response['id'];
           
            $form.find('input[type=text]').empty();

            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            ajaxCsrf();
            $.ajax({
            type: 'POST',
            url: baseUrl + '/stripe',
            headers: {
            stripeToken: response.id
            },
            data: {
            card_name:$('.card-name').val(),
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val(),
            stripeToken:token,
            userpackage_id: $("#userpackage_id").val(),
            userpackage_grossprice: $("#userpackage_grossprice").val()

            },
            success: (response) => {
       
       
            if(response.status==1){
        
                $('#payment').modal('hide');
                window.location.reload(true);               
                statusMesage('This Payment Paid Successfully');
                }else{
                statusMesage('something went wrong','error');
            }
        }
    });

    

}

}


 
</script>