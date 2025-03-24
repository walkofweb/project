
            <div class="carManagement__wrapper">
                <div class="breadcrumbWrapper">
                    <nav aria-label="breadcrumb">
                        <h3 class="fs-5 m-0 fw-500">Checkout Page</h3>
                        <ol class="breadcrumb">
                           <li class="breadcrumb-item"><a href="{{URL::to('/')}}/sponser/dashboard#index" onclick="dashboard()" >Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Payment istory</li>
                        </ol>
                    </nav>
                </div>
                <form class="" id="n_serarchForm" action="javascript:void(0);" > 
                <div class="filterWrapper">
                    <div class="form filterWrapper__l">
                        <div class="form-group">
                            <label for="Manufacture">Hire User</label>
                            <input type="text" class="form-control" placeholder="Hire User" id="hire_user">
                        </div>
                        <div class="form-group">
                            <label for="Manufacture">Package Name</label>
                            <input type="text" class="form-control" placeholder="Package Name" id="package_name">
                        </div>
                         
                        <div class="form-group">
                            <label for="Model">Price </label>
                            <input type="text" class="form-control" id="price" placeholder="Price">
                        </div>  
                       
                          <div class="form-group">
                            <label for="oName">Time Limit</label>
                            <input type="text" class="form-control" id="time_limit" placeholder="Time Limit">
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
                                <th scope="col">Hired User</th>
                                <th scope="col">Package Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">time_limit</th>
                                <th scope="col">Start of Day</th>
                                <th scope="col">End of Day</th>
                                <th scope="col">Card Holder Name</th>
                                <th scope="col">Card Number</th>
                               <th scope="col">Transaction Id</th>
                               <th scope="col">Transaction Status</th>
                              
                               
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
                    "visible":false
                  
                },
                // {
                //     "aTargets": [3],
                //     "visible":true
                // },
                // {
                //     "aTargets": [10],
                //     "visible":false
                // },
                // {
                //     "aTargets": [1],
                //     "mRender": function(data, type, full){
                //         var response ='' ;
                //         if(full['image']!=''){
                //          response='<img src="'+full['image']+'" width="50px" height="50px" /> '+full['name'];
                //         }else{
                //             response='<img src="https://walkofweb.in/public/assets/images/favicon-32x32.png" width="50px" height="50px" /> '; 
                //         }
                //         return response ;
                //     }
                // },
                //  {
                //     "aTargets": [4],
                //     "mRender": function(data, type, full){
                //       return full['rank'];
                       
                //     }
                // }
                
                // ,{
                //     "aTargets": [2],
                //     "visible":false
                // },{
                //     "aTargets": [12],
                //     "visible":false
                // },
                        // {
                        //     "aTargets": [11],
                        //     "mRender" : function(data, type, full){ 
                        //       var action='' ;
                        //        var className='' ;

                        //     if(full['status']==1){
                        //       className='activeNFor' ;
                        //     }else{
                        //       className='inactiveNFor' ;
                        //     }

                        //     action+='<span class="'+className+'">'+full['status_']+'</span>';

                        //     return action ;
                        //     }
                        // } ,

                // {
                //     "aTargets": [13],
                //      "mRender": function(data, type, full){
                //         var response ='<td><div class="align-items-center d-flex"><div class="more_n">   <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#hire_package" onclick="hire_package('+full['id']+')" >Hire</a></div></div></td> '  ;

                      
                //         return response ;
                //     }
                // }  
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
                      url: '{!! URL::asset('sponsor/payment_datatable') !!}',
                    },
             columns : [
             
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'packege_name' },
                        { data: 'price' },
                        { data: 'time_limit'},
                        { data: 'start_date'},
                       
                        { data: 'end_date'},
                        { data: 'cardholder_name'},
                        { data: 'card_number'},
                       
                        { data: 'transaction_id' },
                        { data: 'trans_status' },
                        // { data: 'status_' },
                        // { data: 'rankN' }
          ],

        });

      $k('.input-group-addon').click(function() {
        $k(this).prev('input').focus();
    });
         

});



function resetSearchForm(){

    var table = $('#dataTable').DataTable();
    document.getElementById("n_serarchForm").reset();
  customerManagement();
     //$('#dataTable').DataTable().ajax.reload();        
  
}



    function searchNType(){
     
        var hire_user=$("#hire_user").val();
        var package_name=$("#package_name").val();
        var price=$("#price").val();
        var time_limit=$("#time_limit").val();
        //  var userName=$("#cust_username").val();
        // var rankType=$("#cust_rankType").val();
        // var country = $("#cus_Country").val();


         
            if(hire_user){
        
                $('#dataTable').DataTable().column(1).search(hire_user).draw();
            }else{
                $('#dataTable').DataTable().column(1).search('').draw();
            }


            if(package_name){
        
                $('#dataTable').DataTable().column(2).search(package_name).draw();
            }else{
                $('#dataTable').DataTable().column(2).search('').draw();
            }

            if(price){
        
                $('#dataTable').DataTable().column(3).search(price).draw();
            }else{
                $('#dataTable').DataTable().column(3).search('').draw();
            }

   
   
            if(time_limit){
        
                $('#dataTable').DataTable().column(4).search(time_limit).draw();
            }else{

                $('#dataTable').DataTable().column(4).search('').draw();
            }
   
            // if(mobileNumber){
        
            //     $('#dataTable').DataTable().column(7).search(mobileNumber).draw();
            // }else{
            //     $('#dataTable').DataTable().column(7).search('').draw();
            // }

            // if(email){
        
            //     $('#dataTable').DataTable().column(6).search(email).draw();
            // }else{
            //     $('#dataTable').DataTable().column(6).search('').draw();
            // }

            // if(cName){
        
            //     $('#dataTable').DataTable().column(1).search(cName).draw();
            // }else{
            //     $('#dataTable').DataTable().column(1).search('').draw();
            // }

   
    }       



   


 
</script>