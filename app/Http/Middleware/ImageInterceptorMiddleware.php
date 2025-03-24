<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ApiResponser;

class ImageInterceptorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!empty($request->files->all())){

              foreach ($_FILES as $file) {
                   
             $size = $file['size'] ; // $file->getClientSize(); // size in bytes!


             $onemb =55000000 * 2 ; // pow(5120, 2); // https://stackoverflow.com/a/2510446/6056864
             // print_r($size) ;
              if(is_array($size)){
                foreach ($size as $key => $value) {
                    if($value > $onemb) {
                        return response()->json([
                        'status'=>0,
                        'message' => 'You can upload file only upto 50MB.'
                        ],200);
                    }
                }
              }else{
                if($size > $onemb) {
                        return response()->json([
                        'status'=>0,
                        'message' => 'You can upload file only upto 50MB.'
                        ],200);
                    }
              }
          
          }
        }
      

        return $next($request);
    }
}