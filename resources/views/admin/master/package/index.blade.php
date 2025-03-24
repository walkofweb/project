         
          <div class="carManagement__wrapper">
                <div class="breadcrumbWrapper d-flex align-items-center justify-content-between ">
                    <nav aria-label="breadcrumb">
                        <h3 class="fs-5 m-0 fw-500">Master</h3>
                        <ol class="breadcrumb">
                         <li class="breadcrumb-item"><a href="{{URL::to('/')}}/administrator/dashboard#index" onclick="dashboard()" >Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Master</li>
                            <li class="breadcrumb-item active" aria-current="page">Package</li>

                        </ol>
                    </nav>

                    <!---== data-bs-toggle="modal" data-bs-target="#add_body" ==-->
                    <div class="rightButton">
                        <a href="javascript:void(0);" onclick="showModal('add_body456')" class="border-btn d-flax" ><i class="bi bi-plus"></i><span>Add Package</span></a>
                    </div>
                </div>
                <form action="javascript:void(0);" method="post" id="featureSearchForm">
                <div class="filterWrapper">
                    <div class="form filterWrapper__l s_I">
                       
                         <div class="form-group">
                            <label for="Manufacture">Title</label>
                            <input type="text" class="form-control" id="fState_Search" placeholder="Title">
                        </div> 
                        
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="fStatus_S" id="fStatus_S" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">In Active</option>
                            </select>
                        </div>
                        <div class="d-flex">
                            <a href="javascript:void(0);" onclick="searchNFor();"  class="search-btn">
                                <i class="bi bi-search"></i><span>Search</span>
                            </a>
                            <a href="javascript:void(0);" class="search-btn clear-btn ml-5px" onclick="clearNFor()">
                                <i class="bi bi-eraser-fill"></i><span>Clear</span>
                            </a>
                        </div>
                 
                    </div>
                </div>
                       </form>
                <div class="table-area notification_table">
                    <table class="table" id="dataTable">
                        <thead>
                            <tr>
                                <th scope="col" width="10px">#</th>
                                <th scope="col ">packege_name</th> 
                                <th scope="col ">title</th> 
                                <th scope="col ">time_limit</th>
                                <th scope="col ">price</th>
                                <th scope="col" >Status</th>
                                <th scope="col" >Action</th>
                            </tr>
                        </thead>
                        <tbody>                         
                        </tbody>
                    </table>
                    <!-- <div class="table-footer">
                        <p><span>Total Record</span>:<span>10</span></p>
                    </div> -->
                </div>
        


<div class="modal fade right_side" id="add_body456" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout add_motification_modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Package</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);" method="post" id="pacakageForm" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Package Name</label>
                         <input type="text" name="pacakagename" id="pacakagename"  class="form-control" placeholder="Package Name" value="">
                         <span id="err_pacakagename" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Title</label>
                         <input type="text" name="title" id="title"  class="form-control" placeholder=Title" value="">
                         <span id="err_title" class="err" style="color:red"></span>
                    </div>
                </div>
                <!-- <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Image</label>
                         <input type="file" name="image" id="image"  class="form-control" placeholder="image" value="">
                         <span id="err_image" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Video</label>
                         <input type="file" name="video" id="video"  class="form-control" placeholder="video" value="">
                         <span id="err_video" class="err" style="videocolor:red"></span>
                    </div>
                </div> -->
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="status">Post</label>
                         <select name="post" id="post"  class="form-control">
                            <option value="1">Image</option>
                            <option value="2">Video</option>
                            </select>
                             <span id="err_post" class="err" style="color:red"></span>
                    </div>
                </div>
                  <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Time limit</label>
                         <input type="text" name="time_limit" id="time_limit"  class="form-control" placeholder="Time Limit" value=""> 
                         <span id="err_timelimit" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Price</label>
                         <input type="number" name="price" id="price"  class="form-control" placeholder="Price" value=""> 
                         <span id="err_price" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Other</label>
                         <textarea name="other" id="other" rrows="3"  class="form-control" rows="100" cols="150"placeholder="Other" value=""></textarea>
                         <span id="err_other" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Description</label>
                         <textarea name="description" id="description" rows="100" cols="150" class="form-control" placeholder="Description" value=""></textarea>
                         <span id="err_description" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button"  onclick="submitNotifyFor()" class="search-btn">Submit</button>
                    <!-- <a href="javascript:void(0);" id="cancelBType" onclick="cancelFeature()" class="search-btn clear-btn" data-bs-dismiss="modal">Cancel</a> -->
                </div>

            </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade right_side" id="edit_body" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout edit_body_typ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Package</h5>
                <div class="cross-btn">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body" id="editBodyTypeMb">                
                
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
                  } ,
                       {
                            "aTargets": [5],
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
                            "aTargets": [6],
                            "mRender" : function(data, type, full){

                                // data-bs-toggle="modal" data-bs-target="#edit_body"

                                var action = '<div class="align-items-center d-flex"> <div class="more_n"> <i class="bi bi-three-dots-vertical" type="button" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"></i> <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <li><a class="dropdown-item" href="javascript:void(0);"  onclick="editNFor('+full["id"]+')" >Edit</a></li> <li><a class="dropdown-item" href="javascript:void(0);" onclick="ConfirmDelete('+full['id']+')" >Delete</a></li> </ul> </div> <div> <label class="switch">  ' ;

                            if(full['status']==1){
                                 action +='<input type="checkbox" onclick="changeStateStatus('+full['id']+')" checked>' ;
                             }else{
                                action +='<input type="checkbox" onclick="changeStateStatus('+full['id']+')" >' ;
                             }

                              action+='<span class="slider"></span> </label> </div> </div> '  ;

                               return action ;
                            }

                        
                        }
                        
                        ],

                       

                    ajax: {
                              url: '{!! URL::asset('allpackage_datatable') !!}',
                            },
                     columns : [            
                                { data:'id' },
                                { data:'title' },
                                { data:'packege_name' },
                               
                                { data:'time_limit' },
                                { data:'price' },
                                { data:'status_' },
                               
                  ],
                 
                });

              $k('.input-group-addon').click(function() {
                $k(this).prev('input').focus();
            });
                 
        });

    function ConfirmDelete(id) {
        
        if(confirm("Are you sure ?")) {
            delete_nfor(id);
        }
    }

    function delete_nfor(id){

             ajaxCsrf();

        $.ajax({

        type:"POST",
        url:baseUrl+'/deletePackage',
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
      
          $('#dataTable').DataTable().ajax.reload();                
          statusMesage('Deleted successfully','success');
        }else{
           statusMesage('Something went wrong','error');
        }
        }

        });

    }
 function editNFor(updatedId){   

        ajaxCsrf();
        $('#edit_body').modal('show') ;
        $.ajax({
            type: "POST",
            url: baseUrl +'/editPackage',
            data:{'updatedId':updatedId} ,           
            cache: 'FALSE',
            beforeSend: function () {
                   ajax_before();
            },
            success: function(html){
             ajax_success() ;
               $('#editBodyTypeMb').html(html) ;
                }
            });   
    }



    function submitNotifyFor(){
       
       
      //  ajaxCsrf();
       

         var pacakagename=$('#pacakagename').val();
         var title=$('#title').val();
         var image=$('#image').val();
         var video=$('#video').val();
         var post=$('#post').val();
         var time_limit=$('#time_limit').val();
         var other=$('#other').val();
         var description=$('#description').val();
         var price=$('#price').val();

         let form = document.getElementById('pacakageForm');
         let formData = new FormData(form);
        
    
    

           if(pacakagename==''){
              $('#err_pacakagename').html('Please Enter Package Name.');

           }
               if(title==''){
               $('#err_title').html('Please Enter Title.');
            } 
             if(time_limit==''){
               $('#err_timelimit').html('Please Enter Time Limit For Package.');
            } 
             if(description==''){
               $('#err_description').html('Please Enter Description.');
            } 
             if(price==''){
               $('#err_price').html('Please Enter Price.');
            } 
            else {

            
            $.ajaxSetup({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
     
      //var formData = new FormData(document.getElementById('#fuelTypeForm'));

           
              $.ajax({
                enctype: 'multipart/form-data',
                type: "POST",
                url: baseUrl + '/savePackage',
                  
               data:formData ,
                dataType:'json',
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function () {
                       ajax_before();
                },
                success: function(html){
                    console.log(html);
                  
                 ajax_success() ;

                    if(html.status==1){
                       
                        $('#add_body456').modal('hide'); 
                        $('.modal-backdrop').remove();    
                         removeModelOpen();
                        $('#dataTable').DataTable().ajax.reload();

                          statusMesage('Save successfully','success');
                      }else{
                          statusMesage(html.message,'error');
                      }
                
                         }
                    });   
                }
       // }
    }

    function cancelFeature(){

        $('#fTitle').val('');
        $('#featureIcon').val('');
    }

   
   

function changeStateStatus(id){

    ajaxCsrf();

    $.ajax({
        type:"POST",
        url:baseUrl+'/packageupdateStatus/'+id,
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
     statusMesage('Changed status successfully','success');
  }else{
     statusMesage('Something went wrong','success');
  }
}

});
}


function clearNFor(){

    var table = $('#dataTable').DataTable();
    document.getElementById("featureSearchForm").reset();
    packageList();
     //$('#dataTable').DataTable().ajax.reload();        
  
}



   function searchNFor(){

        var fTitleS=$("#fState_Search").val();
        var fStatus_S=$("#fStatus_S").val();
       

     if(fTitleS){
         $('#dataTable').DataTable().column(2).search(fTitleS).draw();
    }

     if(fStatus_S){
              $('#dataTable').DataTable().column(2).search(fStatus_S).draw();
    }
   
  }



</script>