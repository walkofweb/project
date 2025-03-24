<?php

namespace App\Http\Middleware;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 

use Closure;

class EnsureTokenIsValid extends Controller
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
        
        // if (!$this->auth->parser()->setRequest($request)->hasToken()) {
        // return response()->json([
        //     'code' => APIErrorCode::TOKEN_ABSENT,
        //     'message' => APIErrorCode::TOKEN_ABSENT_MSG
        // ], 401);
        // }

        //  try {
        //     $user = $this->auth->parseToken()->authenticate();
        // } catch (TokenExpiredException $e) {
        //     $token = $this->auth->parseToken()->refresh();
        //     $response = $next($request);
        //     $response->headers->set('Authorization', 'Bearer ' . $token);
        //     return $response;
        // } catch (JWTException $e) {
        //     return response()->json([
        //         'code' => APIErrorCode::TOKEN_INVALID,
        //         'message' => APIErrorCode::TOKEN_INVALID_MSG,
        //     ]);
        // }

        // if (!$user) {
        //     return response()->json([
        //         'code' => APIErrorCode::USER_NOT_FOUND,
        //         'message' => APIErrorCode::USER_NOT_FOUND_MSG
        //     ], 404);
        // }

        // return $next($request);










            $token=authguard();

            if(empty($token)){
            return $this->errorResponse("Unauthenticated",401);
            }else if($token->isTrash==1){
             return $this->errorResponse("Your account has deactivated. Please contact to administrator.",401);
            }       

         return $next($request);
    }
}



   
  