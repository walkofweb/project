
<form action="javascript:void(0);" method="post" id="editFeatureForm">
    <input type="hidden" name="updatedId" id="updatedId" value="<?php echo isset($updatedId)?$updatedId:'' ; ?>">

    <div class="form modal-form">
        <div class="form-group">
            <label for="Manufacture">Package Name</label>
         
            <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="package_name" name="package_name" value="{{$PackageInfo->id}}">
    <label class="form-check-label" for="exampleCheck1">{{$PackageInfo->packege_name}}</label> 
  </div>

             <span id="err_editSTitle" class="err" style="color:red"></span>

        </div>
    </div>

    
                
    <div class="mt-4">
        <a href="javascript:void(0);"  onclick="updateNFor()" class="search-btn">Add</a>
        <a href="javascript:void(0);"  class="search-btn clear-btn" data-bs-dismiss="modal">Cancel</a>
    </div>

</form>