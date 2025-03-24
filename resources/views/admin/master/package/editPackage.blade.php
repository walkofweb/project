
<form action="javascript:void(0);" method="post" id="editFeatureForm" enctype="multipart/form-data">
    <input type="hidden" name="updatedId" id="updatedId" value="<?php echo isset($updatedId)?$updatedId:'' ; ?>">
    {{ csrf_field() }}
    <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Package Name</label>
                         <input type="text" name="pacakagename" id="pacakagename"  class="form-control" placeholder="Package Name" value="{{$packageInfo->packege_name?$packageInfo->packege_name:''}}">
                         <span id="err_pacakagename" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form-group">
                        <label for="Manufacture">Title</label>
                         <input type="text" name="title" id="title"  class="form-control" placeholder="Enter Title" value="{{$packageInfo->title?$packageInfo->title:''}}">
                         <span id="err_title" class="err" style="color:red"></span>
                    </div>
                </div>
          <!-- <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Image</label>
                         <input type="file" name="iamge" id="iamge"  class="form-control" placeholder="iamge">
                         @if($packageInfo->image)
                       
                         <img src="{{ url('public/admin/package/images/'.$packageInfo->image) }}" alt=""/>
                         @endif
                         <span id="err_iamge" class="err" style="iamgecolor:red"></span>
                    </div>
                </div> -->
                <!-- <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Video</label>
                         <input type="file" name="video" id="video"  class="form-control" placeholder="video">
                         @if($packageInfo->video)
                         <img src="{{ url('public/admin/package/images/'.$packageInfo->video) }}" alt=""/>
                         @endif
                         <span id="err_sTitle" class="err" style="color:red"></span>
                    </div>
                </div> -->
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Post</label>

                        <select name="post" id="post"  class="form-control">
                            <option value="1" >Image<option>
                            <option value="2" >Video<option>
                            </select>
                        
                         <span id="err_post" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Time limit</label>
                         <input type="text" name="time_limit" id="time_limit"  class="form-control" placeholder="Time Limit" value="{{$packageInfo->packege_name?$packageInfo->packege_name:''}}">
                         <span id="err_timelimit" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Price</label>
                         <input type="text" name="price" id="price"  class="form-control" placeholder="Price" value="{{$packageInfo->price?$packageInfo->price:''}}">
                         <span id="err_price" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Other</label>
                         <textarea name="other" id="other" rows="200" cols="150" class="form-control" placeholder="Other" value="{{$packageInfo->others?$packageInfo->others:''}}"></textarea>
                         <span id="err_other" class="err" style="color:red"></span>
                    </div>
                </div>
                <div class="form modal-form">
                    <div class="form-group">
                        <label for="Manufacture">Description</label>
                         <textarea name="description" id="description" rows="200" cols="150" class="form-control" placeholder="Description">{{$packageInfo->description?$packageInfo->description:''}}</textarea>
                         <span id="err_description" class="err" style="color:red"></span>
                    </div>
                </div>
   
    <div class="mt-4">
        <button type="button"  onclick="updateNFor()" class="search-btn">Update</button>
        <a href="javascript:void(0);"  class="search-btn clear-btn" data-bs-dismiss="modal">Cancel</a>
    </div>

</form>

<script>
     function updateNFor(){
       
       var pacakagename=$("#pacakagename").val();
    
        var title=$('#title').val();
      
        var post=$('#post').val();
        var time_limit=$('#time_limit').val();
        var other=$('#other').val();
        var description=$('#description').val();
        var price=$('#price').val();

       
       
   
   

        //   if(pacakagename==''){
        //      $('#err_pacakagename').html('Please Enter Package Name.');

        //   }
        //       if(title==''){
        //       $('#err_title').html('Please Enter Title.');
        //    } 
        //     if(time_limit==''){
        //       $('#err_timelimit').html('Please Enter Time Limit For Package.');
        //    } 
        //     if(description==''){
        //       $('#err_description').html('Please Enter Description.');
        //    } 
        //     if(price==''){
        //       $('#err_price').html('Please Enter Price.');
        //    } 
           $.ajaxSetup({
         headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         }
     });
     var form = document.getElementById('editFeatureForm');
         var formData = new FormData(form);
     
         $.ajaxSetup({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
                // enctype: 'multipart/form-data',
                type: "POST",
                url: baseUrl +'/ChangePackage',
                  
                data:formData ,
                cache:false,
                contentType:false,
                processData:false,
                 dataType:'json',
              
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

                          statusMesage('Update successfully','success');
                      }else{
                          statusMesage(html.message,'error');
                      }
                }
            })
       
   }
    </script>