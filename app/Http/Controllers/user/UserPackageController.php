<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Models\Package ;
use App\Models\userpackage;
use Image ;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportUser;
use App\Exports\ExportUser;
use App\Models\User;
use PDF;
use DB , session ;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth; 

class UserPackageController extends Controller
{
    public function index(Request $request){

        $data['title']=siteTitle();
       
        $data['package']=Package::get();
        echo view('user/package/index',$data);

    } 
    public function package_datatable(Request $request){

        $data['title']=siteTitle();
       $id=Auth::user()->id;
    
    
        $carQry="select packeges.id as id,packeges.title as title,packeges.packege_name,packeges.description,packeges.time_limit,packeges.price,case when packeges.status=1 then 'Active' else 'Inactive' end as status from packeges inner join userpackages on packeges.id=userpackages.package_id where userpackages.user_id=$id" ;   
      
        $carData = DB::select($carQry); 
        $tableData = Datatables::of($carData)->make(true);  
        return $tableData; 
        
        
        
}

public function packageStatus(Request $request)
{

    $id=$request->id ;

    $qry="update packeges set status=(case when status=1 then 0 else 1 end) where id=".$id;

    try{

       DB::select($qry);    
        echo successResponse([],'changed status successfully'); 
     
    }
     catch(\Exception $e)
    {
      echo errorResponse('error occurred'); 
     
    }

}


        
       
public function addPackage(Request $request){

    
     


     $updatedId = isset($request->updatedId)?$request->updatedId:0 ;
     $packageInfo = Package::where('id',$updatedId)->first() ;
    
     $data['PackageInfo'] = $packageInfo ;
     $data['updatedId']=$updatedId ;
    
     echo view('user/package/editPackage',$data);
    
}


public function updatePackage(Request $request){
   
     $user_id= auth()->user()->id;
   
    $package_name = isset($request->package_id)?$request->package_id:'' ;
   // $editCode = isset($request->edit_apiCode)?$request->edit_apiCode:'' ;
    $insertData = array(
          "package_id"=>$package_name,
          "user_id"=>$user_id
      ) ;

  try{
    $check= userpackage::where('package_id', '=', $package_name)->where('user_id', '=', $user_id)->get()->toArray() ;

    if(empty($check)){
        userpackage::insert($insertData) ;
        echo successResponse([],'successfully added package in user.'); 
    }else{
      echo errorResponse([],'Already exist this user in this package.');        
    }
 
           
        
       
  }
   catch(\Exception $e){
       echo errorResponse('error occurred'); 
   }         
}


public function delete_package(Request $request){

   
    $user_id= auth()->user()->id;
    $package_id=$request->id;
   
    $userpackage=userpackage::where("package_id","=",$package_id)->where("user_id","=",$user_id)->first();
    $userpackage_id=$userpackage->id;
   

    
  try{
    userpackage::where('id',$userpackage_id)->delete();
          
        return $this->successResponse([],'This user package has delete successfully'); 
  }
   catch(\Exception $e){
       return $this->errorResponse('error occurred'); 
   }
}





     
}
